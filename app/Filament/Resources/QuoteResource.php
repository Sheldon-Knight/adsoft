<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Status;
use App\Services\PdfInvoice;
use Closure;
use Exception;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\MultiSelectFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\NumberFilter;

class QuoteResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Quotes';


    protected static ?string $title = 'Quotes';

    protected static ?string $slug = 'quotes';

    protected static ?string $navigationGroup = 'Finance';


    public static function canViewAny(): bool
    {
        return auth()->user()->can('view any quotes');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete quotes');
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can('view quotes');
    }

    public static function canRestore(Model $record): bool
    {
        return auth()->user()->can('restore quotes');
    }
    public static function canForceDelete(Model $record): bool
    {
        return auth()->user()->can('force delete quotes');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create quotes');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('is_quote', true)
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
                                    ->required()
                                    ->label('Quote Number'),

                                Select::make('client_id')
                                    ->label('Client')
                                    ->required()
                                    ->searchable()
                                    ->options(Client::query()->pluck('client_name', 'id')),

                                Select::make('invoice_status')
                                    ->label('Status')
                                    ->required()
                                    ->searchable()
                                    ->options(Status::pluck('name', 'name')),

                                DatePicker::make('invoice_date')
                                    ->default(now())
                                    ->required()
                                    ->label('Quote Date'),

                                DatePicker::make('invoice_due_date')
                                    ->default(now()->addDays(7))
                                    ->required()
                                    ->label('Quote Due Date'),

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
                                                // return number_format(intval($price) * intval($qty));
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
                    ])
                    ->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->label('Quote Number'),
                Tables\Columns\TextColumn::make('invoice_date')->label('Quote Invoice Date')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('invoice_due_date')->label('Quote Due Date')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('invoice_total')->label('Quote Total')->sortable()->searchable()->money('zar', true),
                Tables\Columns\TextColumn::make('invoice_status')->label('Quote Status')->sortable()->searchable(),
            ])
            ->filters([
                DateFilter::make('invoice_date'),
                DateFilter::make('invoice_due_date'),
                NumberFilter::make('invoice_total'),
                MultiSelectFilter::make('invoice_status')
                    ->options(Invoice::where('is_quote', true)->get()->pluck("invoice_status", "invoice_status")->toArray())
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

                        if (auth()->user()->can("download pdf quotes") and $record->deleted_at === null) {
                            return true;
                        }
                        return false;
                    }),

                Tables\Actions\Action::make('email')
                    ->color('success')
                    ->visible(function (Invoice $record) {
                        if (auth()->user()->can("email quotes") and $record->deleted_at === null) {
                            return true;
                        }
                        return false;
                    })
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

                                $pdfInvoice = new PdfInvoice();

                                $attachement =  $pdfInvoice->GetAttachedInvoice($record, $isInvoice = false);

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
                    ->label('Email Quote')
                    ->icon('heroicon-o-mail')
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
                                    ->label('Automatic Attach Quote')
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




                Action::make('View')
                    ->url(function (Invoice $record) {
                        return route('filament.resources.quotes.view', $record);
                    })
                    ->visible(function (Invoice $record) {

                        if (auth()->user()->can("view quotes") and $record->deleted_at === null) {
                            return true;
                        }
                        return false;
                    }),


                Action::make('Change Status')
                    ->icon('heroicon-o-arrows-expand')
                    ->visible(function (Invoice $record) {
                        if (auth()->user()->can("update quotes") and $record->deleted_at === null) {
                            return true;
                        }
                        return false;
                    })
                    ->form([
                        Select::make('invoice_status')
                            ->label('Status')
                            ->options(Status::pluck('name', 'name'))
                            ->required(),
                    ])
                    ->action(function (Invoice $record, $data) {
                        $record->update(['invoice_status' => $data['invoice_status']]);
                        return Notification::make()
                            ->title('Status Updated')
                            ->success()
                            ->send();
                    })



                    ->requiresConfirmation()
                    ->color('secondary'),


                Action::make('Convert To Invoice')
                    ->icon('heroicon-o-arrow-circle-down')
                    ->visible(function (Invoice $record) {
                        if (auth()->user()->can("convert quotes to invoices") and $record->deleted_at === null) {
                            return true;
                        }
                        return false;
                    })
                    ->form([
                        Select::make('invoice_status')
                            ->label('Status')
                            ->options(Status::pluck('name', 'name'))
                            ->required(),
                    ])
                    ->action(fn (Invoice $record, $data) => $record->update(['is_quote' => false, 'invoice_status' => $data['invoice_status']]))
                    ->requiresConfirmation()
                    ->color('warning'),
                Tables\Actions\DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->headerActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction::make('export')
            ])
            ->bulkActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction::make('export'),
                BulkAction::make('Convert To Invoice')
                    ->form([
                        Select::make('invoice_status')
                            ->label('Status')
                            ->options(Status::pluck('name', 'name'))
                            ->required(),
                    ])
                    ->visible(function () {
                        return auth()->user()->can("convert quotes to invoices");
                    })
                    ->action(fn (Collection $records, $data) => $records->each->update(['is_quote' => false, 'invoice_status' => $data['invoice_status']]))
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation()
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
            'index' => Pages\ListQuotes::route('/'),
            'create' => Pages\CreateQuote::route('/create'),
            'view' => Pages\ViewQuote::route('/{record}'),
        ];
    }
}
