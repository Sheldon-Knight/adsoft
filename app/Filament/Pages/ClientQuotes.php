<?php

namespace App\Filament\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Models\Invoice;
use App\Models\User;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\NumberFilter;

class ClientQuotes extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static string $view = 'filament.pages.client-quotes';

    protected static ?string $title = 'My Quotes';

    protected static ?string $navigationLabel = 'My Quotes';

    protected static ?string $slug = 'client/my-quotes';

    protected static ?string $navigationGroup = 'Summary';

    protected static ?int $navigationSort = 2;

    protected function getTableQuery(): Builder
    {
        return Invoice::query()->where('is_quote', true)->where('client_id', auth()->id());
    }

    public function mount()
    {
        if (! auth()->user()->Hasrole('Client')) {
            return abort(404);
        }
    }

    protected static function shouldRegisterNavigation(): bool
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return auth()->user()->Hasrole('Client');
    }

    protected function getTableColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('invoice_number')->label('Quote Number')->sortable()->searchable(),
            \Filament\Tables\Columns\TextColumn::make('invoice_date')->label('Quote Date')->sortable()->searchable(),
            \Filament\Tables\Columns\TextColumn::make('invoice_due_date')->label('Quote Due Date')->sortable()->searchable(),
            \Filament\Tables\Columns\TextColumn::make('invoice_total')->label('Quote Total')->sortable()->searchable()->money('zar', true),
            \Filament\Tables\Columns\TextColumn::make('invoice_status')->label('Quote Status')->sortable()->searchable(),

        ];
    }

    protected function getTableFilters(): array
    {
        return [
            DateFilter::make('invoice_date')->label('Quote Date'),
            DateFilter::make('invoice_due_date')->label('Quote Due Date'),
            NumberFilter::make('invoice_total')->label('Quote Total'),
            MultiSelectFilter::make('invoice_status')->label('Quote Status')
                ->options(Invoice::QuoteStatuses())
                ->column('invoice_status'),
            TrashedFilter::make(),
        ];
    }

    protected function getTableActions(): array
    {
        $invoiceStatuses = Invoice::quoteStatuses();

        unset($invoiceStatuses[Invoice::PENDING]);

        return [

            Action::make('Change Status')
                ->form([
                    Select::make('invoice_status')
                        ->label('Status')
                        ->options($invoiceStatuses)
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
                ->label('Review')
                ->color('success')
                ->visible(function (Invoice $record) {
                    if ($record->invoice_status != Invoice::PENDING) {
                        return false;
                    }

                    return true;
                }),

            Action::make('Pdf Download')
                ->label('Pdf Download')
                ->color('warning')
                ->url(function (Invoice $record) {
                    return route('pdf-download', $record);
                }),
            \Filament\Tables\Actions\ViewAction::make()
                ->form(
                    [
                        Group::make()
                            ->schema([
                                Card::make()
                                    ->schema([
                                        TextInput::make('invoice_number')->label('Quote Number')
                                            ->default('ABC-'.random_int(10000, 999999))
                                            ->required(),

                                        Select::make('client_id')
                                            ->label('Client')
                                            ->required()
                                            ->searchable()
                                            ->options(User::query()->role('Client')->pluck('name', 'id')),

                                        DatePicker::make('invoice_date')->label('Quote Label')
                                            ->default(now())
                                            ->required(),

                                        DatePicker::make('invoice_due_date')->label('Quote Due Date')
                                            ->default(now()->addDays(7))
                                            ->required(),

                                        Select::make('invoice_status')->label('Quote Status')
                                            ->label('Status')
                                            ->required()
                                            ->searchable()
                                            ->options(Invoice::QuoteStatuses()),

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
                                            ->cloneable()
                                            ->createItemButtonLabel('Add Item'),

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

                    ]
                ),

        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            FilamentExportBulkAction::make('export'),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            CreateAction::make()->url(
                route(
                    'filament.resources.jobs.create'
                )
            )->visible(auth()->user()->can('create jobs')),
            FilamentExportHeaderAction::make('export'),
        ];
    }

    protected function getTableFiltersFormColumns(): int
    {
        return 3;
    }

    protected function getTableFiltersFormWidth(): string
    {
        return '4xl';
    }

    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }
}
