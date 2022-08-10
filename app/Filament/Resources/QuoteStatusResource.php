<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteStatusResource\Pages;
use App\Filament\Resources\QuoteStatusResource\RelationManagers;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuoteStatusResource extends Resource
{
    protected static ?string $model = InvoiceStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Quotes Statuses';

    protected static ?string $title = 'Quotes Statuses';

    protected static ?string $slug = 'quotes-statuses';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 4;



    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_quote', true);
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->maxLength(255)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ->visible(function (){
                if(InvoiceStatus::where('is_quote',true)->count() == 1){return false;}

                return true;
            }),
            ])
            ->bulkActions([
              
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
            'index' => Pages\ListQuoteStatuses::route('/'),
            'create' => Pages\CreateQuoteStatus::route('/create'),
            'view' => Pages\ViewQuoteStatus::route('/{record}'),
            'edit' => Pages\EditQuoteStatus::route('/{record}/edit'),
        ];
    }
}
