<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Filament\Resources\QuoteResource\RelationManagers;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceBasicInfo;
use App\Models\InvoiceStatus;
use Closure;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class QuoteResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Quotes';

    protected static ?string $title = 'Quotes';
  
    protected static ?string $slug = 'quotes';

    protected static ?string $navigationGroup = 'Finance';


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_quote', true);
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
                                ->options(Client::query()->pluck('client_name', 'id')),

                                Select::make('invoice_status')
                                ->label('Status')
                                ->required()                               
                                ->options(InvoiceStatus::where('is_quote',true)->get()->pluck('name', 'id')),

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
                                            'md' => 5,
                                        ]),

                                    TextInput::make('price')
                                    ->required()
                                        ->numeric()
                                        ->prefix('R')
                                        ->reactive()
                                        ->label('price')
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

        $defualtConvertedStatus =  InvoiceBasicInfo::first()->default_converted_status;
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->label('Quote Number'),
                Tables\Columns\TextColumn::make('invoice_date')->label('Quote Invoice Date')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('invoice_due_date')->label('Quote Due Date')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('invoice_total')->label('Quote Total')->sortable()->searchable()->money('zar', true),
                Tables\Columns\TextColumn::make('invoiceStatus.name')->label('Quote Status')->sortable()->searchable(),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), 
                 Action::make('Convert To Invoice')->icon('heroicon-o-arrow-circle-down')->action(fn (Invoice $record) => $record->update(['is_quote' => false, 'invoice_status' => $defualtConvertedStatus]))->requiresConfirmation(),
            Tables\Actions\DeleteAction::make(),                
            ])
            ->bulkActions([

            BulkAction::make('Convert To Invoice')
            ->action(fn (Collection $records) => $records->each->update(['is_quote' => false,'invoice_status' => $defualtConvertedStatus]))
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
