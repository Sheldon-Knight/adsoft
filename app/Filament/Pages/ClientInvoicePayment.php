<?php

namespace App\Filament\Pages;

use App\Models\Invoice;
use App\Models\User;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Support\Actions\Concerns\HasForm;

class ClientInvoicePayment extends Page implements HasForms
{
    use HasForm;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.client-invoice-payment';

    protected static ?string $slug = '/client/invoices/{record}/make-payment';

    public Invoice $record;

    protected static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(Invoice $record): void
    {
        $this->record = $record;

        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Group::make()
                ->schema([
                    Card::make()
                        ->schema([
                            TextInput::make('invoice_number')
                                ->default(function () {
                                    return $this->record->invoice_number;
                                })
                                ->required()
                                ->disabled(),

                            Select::make('client_id')
                                ->label('Client')
                                ->default(function () {
                                    return $this->record->client->name;
                                })
                                ->required()
                                ->searchable()
                                ->options(User::query()->role('Client')->pluck('name', 'id'))
                                ->disabled(),

                            DatePicker::make('invoice_date')
                                ->default(function () {
                                    return $this->record->invoice_date;
                                })
                                ->required()
                                ->disabled(),

                            DatePicker::make('invoice_due_date')
                                ->default(function () {
                                    return $this->record->invoice_due_date;
                                })
                                ->required()
                                ->disabled(),

                            Select::make('invoice_status')
                                ->label('Status')
                                ->default(function () {
                                    return $this->record->invoice_status;
                                })
                                ->required()
                                ->searchable()
                                ->options(Invoice::invoiceStatuses())
                                ->disabled(),

                        ])->columns([
                            'sm' => 2,
                        ]),

                    Card::make()
                        ->schema([
                            Repeater::make('items')
                                ->schema([

                                    TextInput::make('item')
                                        ->required()
                                        ->disabled()
                                        ->columnSpan([
                                            'md' => 4,
                                        ]),

                                    TextInput::make('price')
                                        ->required()
                                        ->reactive()->type('number')
                                        ->prefix('R')
                                        ->numeric()
                                        ->disabled()
                                        ->minValue(0)
                                        ->extraAttributes([
                                            'step' => '0.01',
                                        ])
                                        ->columnSpan([
                                            'md' => 2,
                                        ]),

                                    TextInput::make('qty')
                                        ->required()
                                        ->numeric()
                                        ->type('number')
                                        ->default(1)
                                        ->disabled()
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
                                            'step' => '0.01',
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
                                        ->label('Sub Total')
                                        ->columnSpan([
                                            'md' => 3,
                                        ]),
                                ])
                                ->defaultItems(1)
                                ->columns([
                                    'md' => 10,
                                ])
                                ->columnSpan('full')
                                ->disableItemCreation()
                                ->disableItemDeletion()
                                ->disableItemMovement()
                                ->default(function () {
                                    return $this->record->items;
                                }),

                        ]),

                    Card::make()
                        ->schema([
                            TextInput::make('invoice_subtotal')
                            ->label('Sub Total')
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

                                        if ($field['price'] == '' or $field['price'] == null) {
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
                            ->label('Discount')
                                ->required()
                                ->reactive()
                        ->disabled()
                                ->type('number')
                                ->prefix('R')
                                ->numeric()
                                ->minValue(0)
                                ->default(0.00)
                                ->extraAttributes([
                                    'step' => '0.01',
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

                            TextInput::make('invoice_tax')
                            ->label('Tax')
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

                            TextInput::make('invoice_total')
                            ->label('Total Amount')
                            ->numeric()
                                ->type('number')
                                ->prefix('R')
                                ->disabled()
                                ->default(0)
                                ->placeholder(function (Closure $get, $set) {
                                    $tax = $get('invoice_tax');
                                    $discount = $get('invoice_discount');
                                    $subtotal = $get('invoice_subtotal');
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

                ])->columnSpan('full'),

        ];
    }
}
