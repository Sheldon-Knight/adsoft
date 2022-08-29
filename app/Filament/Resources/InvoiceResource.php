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
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\MultiSelectFilter;
use Illuminate\Contracts\Mail\Attachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use League\CommonMark\Input\MarkdownInput;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Spatie\Permission\Models\Permission;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\NumberFilter;

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
                                    ->options(Status::pluck('name', 'name')),

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
                                                'md' => 4,
                                            ]),

                                        TextInput::make('price')
                                            ->required()
                                            ->reactive()
                                            ->type('number')
                                            ->prefix('R')
                                            ->numeric()
                                            ->minValue(0)
                                            ->extraAttributes([
                                                "step" => "0.01"
                                            ])
                                            ->columnSpan([
                                                'md' => 2,
                                            ]),

                                        TextInput::make('qty')
                                            ->required()
                                            ->numeric()
                                            ->type('number')
                                            ->default(1)
                                            ->reactive()
                                            ->minValue(1)
                                            ->columnSpan([
                                                'md' => 1,
                                            ]),

                                        TextInput::make('subtotal')
                                            ->numeric()
                                            ->type('number')
                                            ->prefix('R')
                                            ->required()
                                            ->disabled()
                                            ->reactive()
                                            ->extraAttributes([
                                                "step" => "0.01"
                                            ])
                                            ->placeholder(function (Closure $get, $set) {
                                                $price = 0;
                                                $qty = 1;

                                                if ($get('price') == null) {
                                                    $price = 0.00;
                                                }

                                                if ($get('qty') == null) {
                                                    $qty = 1;
                                                }

                                                $price = $get('price');
                                                $qty = $get('qty');

                                                $set('subtotal', number_format(floatval($price) * intval($qty), 2));
                                            })
                                            ->label("Sub Total")
                                            ->columnSpan([
                                                'md' => 3,
                                            ])
                                    ])
                                    ->defaultItems(1)
                                    ->columns([
                                        'md' => 10,
                                    ])
                                    ->columnSpan('full')
                                    ->cloneable()
                                    ->createItemButtonLabel('Add Item'),


                            ]),


                        Card::make()
                            ->schema([
                                TextInput::make("invoice_subtotal")
                                    ->label("Sub Total")
                                    ->numeric()
                                    ->type('number')
                                    ->prefix('R')
                                    ->disabled()
                                    ->placeholder(function (Closure $get, $set) {
                                        if (isset($get('items')[key($get('items'))]['price'])) {
                                            if ($get('items')[key($get('items'))]['price'] == null) {
                                                return number_format(0, 2);
                                            }
                                        }

                                        $fields = $get('items');
                                        $sum = 0;

                                        foreach ($fields as $field) {
                                            $value = floatval($field['price']) * intval($field['qty']);

                                            if ($field['price'] == "" or $field['price'] == null) {
                                                $value = 0;
                                            }

                                            $sum += $value;
                                        }

                                        $set('invoice_subtotal', number_format($sum, 2));
                                    })
                                    ->columnSpan([
                                        'md' => 3,
                                    ]),


                                TextInput::make('invoice_discount')
                                    ->label("Discount")
                                    ->required()
                                    ->reactive()
                                    ->type('number')
                                    ->prefix('R')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0.00)
                                    ->extraAttributes([
                                        "step" => "0.01"
                                    ])
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        if ($state == null) {
                                            $state = 0;
                                        }
                                        $set('invoice_total', number_format($get('invoice_total') - $state, 2));
                                    })
                                    ->placeholder(fn () => 0.00)
                                    ->columnSpan([
                                        'md' => 3,
                                    ]),





                                TextInput::make("invoice_tax")
                                    ->label("Tax")
                                    ->numeric()
                                    ->type('number')
                                    ->prefix('R')
                                    ->disabled()
                                    ->default(0)
                                    ->placeholder(function (Closure $get, $set) {
                                        $tax = $get('invoice_subtotal') * 0.15 ?? 0;
                                        $set('invoice_tax', number_format($tax, 2));
                                        return number_format($tax, 2);
                                    })
                                    ->columnSpan([
                                        'md' => 3,
                                    ]),

                                TextInput::make("invoice_total")
                                    ->label("Total Amount")
                                    ->numeric()
                                    ->type('number')
                                    ->prefix('R')
                                    ->disabled()
                                    ->default(0)
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
                Tables\Columns\TextColumn::make('invoice_status')->sortable()->searchable(),


            ])
            ->filters([
                DateFilter::make('invoice_date'),
                DateFilter::make('invoice_due_date'),
                NumberFilter::make('invoice_total'),
                MultiSelectFilter::make('invoice_status')
                    ->options(Invoice::where('is_quote',false)->get()->pluck("invoice_status", "invoice_status")->toArray())
                    ->column('invoice_status'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Action::make('Pdf Downlaod')
                    ->label("Pdf Download")
                    ->color('warning')
                    ->url(function (Invoice $record) {
                        return route('pdf-download', $record);
                    })
                    ->visible(function (Invoice $record) {

                        if (auth()->user()->can("download pdf invoices") and $record->deleted_at === null) {
                            return true;
                        }
                        return true;
                    }),
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
                            ->options(Status::pluck('name', 'name'))
                            ->required(),
                    ]),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),

            ])
            ->headerActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction::make('export')
            ])
            ->bulkActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction::make('export')
            ]);
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
