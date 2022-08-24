<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\Status;
use App\Services\PdfInvoice;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Contracts\Mail\Attachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use League\CommonMark\Input\MarkdownInput;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Spatie\Permission\Models\Permission;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-cash';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Finance';


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('is_quote', false)
            ->withoutGlobalScopes();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Card::make()
                            ->schema([
                                TextInput::make('invoice_number')
                                    ->default('ABC-' . random_int(10000, 999999))
                                    ->required(),

                                Select::make('client_id')
                                    ->label('Client')
                                    ->required()
                                    ->searchable()
                                    ->options(Client::query()->pluck('client_name', 'id')),

                                DatePicker::make('invoice_date')
                                    ->default(now())
                                    ->required(),

                                DatePicker::make('invoice_due_date')
                                    ->default(now()->addDays(7))
                                    ->required(),

                                Select::make('invoice_status')
                                    ->label('Status')
                                    ->required()
                                    ->searchable()
                                    ->options(Status::pluck('name', 'id')),

                            ])->columns([
                                'sm' => 2,
                            ]),

                        Card::make()
                            ->schema([

                                Placeholder::make('Products'),

                                Repeater::make('items')
                                    ->schema([
                                        TextInput::make('item')
                                            ->required()
                                            ->columnSpan([
                                                'md' => 5,
                                            ]),

                                        TextInput::make('price')
                                            ->required()
                                            ->numeric()
                                            ->prefix('R')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $set('amount', number_format($state * $get('qty'), 2));
                                            })
                                            ->columnSpan([
                                                'md' => 2,
                                            ]),

                                        TextInput::make('qty')
                                            ->required()
                                            ->numeric()
                                            ->default(1)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $set('amount', number_format($get('price') * $state, 2));
                                            })
                                            ->columnSpan([
                                                'md' => 1,
                                            ]),

                                        TextInput::make('amount')
                                            ->disabled()
                                            ->numeric()
                                            ->prefix('R')
                                            ->columnSpan([
                                                'md' => 2,
                                            ]),


                                    ])
                                    ->defaultItems(1)
                                    ->columns([
                                        'md' => 10,
                                    ])
                                    ->columnSpan('full')
                                    ->createItemButtonLabel('Add Item'),


                            ]),


                        Card::make()
                            ->schema([
                                TextInput::make("invoice_subtotal")
                                    ->label("Sub Total")
                                    ->numeric()
                                    ->prefix('R')
                                    ->disabled()
                                    ->placeholder(function (Closure $get, $set) {
                                        $fields = $get('items');
                                        $sum = 0;
                                        foreach ($fields as $field) {
                                            $value = $field['price'] * $field['qty'];


                                            if ($field['price'] == "" or $field['price'] == null) {
                                                $value = 0;
                                            }

                                            $sum += $value;
                                        }
                                        $set('invoice_subtotal', number_format($sum, 2));
                                        return number_format($sum, 2);
                                    })
                                    ->columnSpan([
                                        'md' => 3,
                                    ]),

                                TextInput::make("invoice_discount")
                                    ->label("Discount")
                                    ->numeric()
                                    ->prefix('R')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $set('invoice_total', number_format($get('invoice_total') - $state, 2));
                                    })
                                    ->placeholder(function (Closure $get, $set) {
                                        $discount =  $get('invoice_discount') ?? 0;
                                        $set('invoice_discount', number_format($discount, 2));
                                        return number_format($discount, 2);
                                    })
                                    ->columnSpan([
                                        'md' => 3,
                                    ]),

                                TextInput::make("invoice_tax")
                                    ->label("Tax")
                                    ->numeric()
                                    ->prefix('R')
                                    ->disabled()
                                    ->placeholder(function (Closure $get, $set) {
                                        $tax =  $get('invoice_subtotal') * 0.15 ?? 0;
                                        $set('invoice_tax', number_format($tax, 2));
                                        return number_format($tax, 2);
                                    })
                                    ->columnSpan([
                                        'md' => 3,
                                    ]),

                                TextInput::make("invoice_total")
                                    ->label("Total Amount")
                                    ->numeric()
                                    ->prefix('R')
                                    ->disabled()
                                    ->placeholder(function (Closure $get, $set) {
                                        $tax =  $get('invoice_tax');
                                        $discount =  $get('invoice_discount');
                                        $subtotal =  $get('invoice_subtotal');
                                        $total = $subtotal + $tax - $discount;
                                        $set('invoice_total', number_format($total, 2));
                                        return number_format($total, 2);
                                    })
                                    ->columnSpan([
                                        'md' => 3,
                                    ]),

                            ])->columns([
                                'md' => 12,
                            ]),


                    ])->columnSpan('full')

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('invoice_date')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('invoice_due_date')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('invoice_total')->sortable()->searchable()->money('zar', true),
                Tables\Columns\TextColumn::make('status.name')->sortable()->searchable(),
            ])
            ->filters([Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('email')
                    ->color('success')
                    ->action(
                        function (Invoice $record, $data) {

                            $removedItems = [];

                            foreach ($data['cc'] as $key => $email) {

                                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    $removedItems[] = $email;
                                    unset($data['cc'][$key]);
                                }
                            }





                            if ($data['attached_invoice'] == true) {




                                $pdfInvoice = new pdfInvoice();

                                $attachement =  $pdfInvoice->GetAttachedInvoice($record);

                                Mail::send(
                                    'mails.invoice',
                                    ['body' => $data['body']],
                                    function ($message) use ($data, $attachement) {


                                        $message->from('john@johndoe.com', 'John Doe');
                                        $message->to($data['to']);
                                        $message->cc(array_values($data['cc']));
                                        $message->subject($data['subject']);
                                        $message->attach($attachement);

                                        if ($data['attachments']) {

                                            foreach ($data["attachments"] as $key => $at) {

                                                $at = $message->attach(public_path("storage/{$data["attachments"][$key]}"));
                                            }
                                        };
                                    }
                                );

                                unlink($attachement);
                            } else {
                                Mail::send('mails.invoice', ['body' => $data['body']], function ($message) use ($data) {
                                    $message->from('john@johndoe.com', 'John Doe');
                                    $message->to($data['to']);
                                    $message->cc(array_values($data['cc']));
                                    $message->subject($data['subject']);
                                    if ($data['attachments']) {

                                        foreach ($data["attachments"] as $key => $at) {

                                            $message->attach(public_path("storage/{$data["attachments"][$key]}"));
                                        }
                                    };
                                });
                            }

                            if ($data['attachments']) {

                                try {
                                    foreach ($data["attachments"] as $key => $at) {
                                        unlink(public_path("storage/{$data["attachments"][$key]}"));
                                    }
                                } catch (\Exception $e) {
                                    Log::error($e->getMessage());
                                }
                            }




                            Notification::make()
                                ->title("Emails Send Succesfully")
                                ->body('send')
                                ->success()
                                ->send();


                            if (count($removedItems) > 0) {
                                Notification::make()
                                    ->title("Some Emails Were Removed From The CC")
                                    ->body('the folowing emails were not valid so it had been removed from the cc:' . implode(PHP_EOL, $removedItems))
                                    ->danger()
                                    ->persistent()
                                    ->send();

                                return;
                            }
                        }


                    )
                    ->label('Email Invoice')
                ->visible(function (Invoice $record) {

                    if (auth()->user()->can("email invoices") and $record->deleted_at === null) {
                        return true;
                    }
                    return false;
                })
                    ->form([
                        Card::make()
                            ->schema([
                                TextInput::make('to')
                                    ->label('Email')
                                    ->default(function (Invoice $record) {
                                        return $record->client->email;
                                    })
                                    ->email()
                                    ->required(),

                                TagsInput::make('cc')
                                    ->label('CC')
                                    ->placeholder(fn () => auth()->user()->email),



                                TextInput::make('subject')
                                    ->label('Subject')
                                    ->placeholder('Enter A Subject')
                                    ->required()
                                    ->columnSpan('full'),

                                Toggle::make('attached_invoice')
                                    ->label('Automatic Attach Invoice')
                                    ->onIcon('heroicon-s-lightning-bolt')
                                    ->offIcon('heroicon-s-user')
                                    ->default(true),

                                FileUpload::make('attachments')
                                    ->multiple()
                                    ->preserveFilenames()
                                    ->directory('form-attachments'),

                                RichEditor::make('body')
                                    ->toolbarButtons([
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'undo',
                                    ])->label('Email Body')
                                    ->placeholder('Enter email body')
                                    ->required()
                                    ->columnSpan('full'),


                            ])

                            ->columns(2),

                    ]),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->label('Change Status')
                    ->form([
                        Forms\Components\Select::make('invoice_status')
                            ->label('Status')
                            ->options(Status::pluck('name', 'id'))
                            ->required(),
                    ]),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),

            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view' => Pages\ViewInvoice::route('/{record}'),
        ];
    }
}
