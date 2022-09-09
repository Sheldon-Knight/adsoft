<?php

namespace App\Filament\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Models\Invoice;
use App\Models\Status;
use App\Models\User;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\NumberFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class ClientInvoices extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-cash';

    protected static string $view = 'filament.pages.client-invoices';

    protected static ?string $title = 'My Invoices';

    protected static ?string $navigationLabel = 'My Invoices';

    protected static ?string $slug = 'client/my-invoices';

    protected static ?string $navigationGroup = 'Summary';

    protected static ?int $navigationSort = 2;

    protected function getTableQuery(): Builder
    {
        return Invoice::query()->where('is_quote', false)->where('client_id', auth()->id());
    }

    public function mount()
    {
        if (!auth()->user()->Hasrole('Client')) {
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
            \Filament\Tables\Columns\TextColumn::make('invoice_number')->sortable()->searchable(),
            \Filament\Tables\Columns\TextColumn::make('invoice_date')->sortable()->searchable(),
            \Filament\Tables\Columns\TextColumn::make('invoice_due_date')->sortable()->searchable(),
            \Filament\Tables\Columns\TextColumn::make('invoice_total')->sortable()->searchable()->money('zar', true),
            \Filament\Tables\Columns\TextColumn::make('invoice_status')->sortable()->searchable(),

        ];
    }

    protected function getTableFilters(): array
    {
        return [
            DateFilter::make('invoice_date'),
            DateFilter::make('invoice_due_date'),
            NumberFilter::make('invoice_total'),
            MultiSelectFilter::make('invoice_status')
                ->options(Invoice::where('is_quote', false)->get()->pluck('invoice_status', 'invoice_status')->toArray())
                ->column('invoice_status'),
            TrashedFilter::make(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('Pdf Downlaod')
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
                                        TextInput::make('invoice_number')
                                            ->default('ABC-' . random_int(10000, 999999))
                                            ->required(),

                                        Select::make('client_id')
                                            ->label('Client')
                                            ->required()
                                            ->searchable()
                                            ->options(User::query()->role('Client')->pluck('name', 'id')),

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
