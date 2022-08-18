<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatementResource\Pages;
use App\Filament\Resources\StatementResource\RelationManagers;
use App\Models\Statement;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatementResource extends Resource
{
    protected static ?string $model = Statement::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    protected static ?string $navigationGroup = 'Banking';

    protected static ?int $navigationSort = 4;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('transaction_id'),
                Forms\Components\TextInput::make('account_id'),
                Forms\Components\TextInput::make('debit')
                    ->required(),
                Forms\Components\TextInput::make('credit')
                    ->required(),
                Forms\Components\TextInput::make('opening_balance')
                    ->required(),
                Forms\Components\TextInput::make('closing_balance')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_id'),
                Tables\Columns\TextColumn::make('account_id'),
                Tables\Columns\TextColumn::make('debit')
                    ->prefix('R')
                    ->getStateUsing(function (Statement $record) {
                        return number_format($record->debit / 100, 2);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('credit')
                    ->prefix('R')
                    ->getStateUsing(function (Statement $record) {
                        return number_format($record->credit / 100, 2);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('opening_balance')
                    ->prefix('R')
                    ->getStateUsing(function (Statement $record) {
                        return number_format($record->opening_balance / 100, 2);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('closing_balance')
                    ->prefix('R')
                    ->getStateUsing(function (Statement $record) {
                        return number_format($record->closing_balance / 100, 2);
                    })
                    ->searchable(),          
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Opened At")
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([])
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
            'index' => Pages\ListStatements::route('/'),
        ];
    }
}
