<?php

namespace App\Filament\Resources\AccountResource\RelationManagers;

use App\Models\Statement;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;


class StatementsRelationManager extends RelationManager
{
    protected static string $relationship = 'statements';

    protected static ?string $recordTitleAttribute = 'description';

    protected $listeners = [
        'refreshTable' => '$refresh',     
    ];
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([             
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([Tables\Columns\TextColumn::make('created_at')->date()->label('Date'),
                Tables\Columns\TextColumn::make('description')->label('Description'),
                Tables\Columns\BadgeColumn::make('credit')
                    ->colors(['success'])
                    ->prefix('R')
                    ->getStateUsing(function (Statement $record) {
                        return number_format($record->credit, 2);
                    })
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('debit')
                    ->colors(['danger'])
                    ->prefix('-R')
                    ->getStateUsing(function (Statement $record) {
                        return number_format($record->debit, 2);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('opening_balance')
                    ->prefix('R')
                    ->getStateUsing(function (Statement $record) {
                        return number_format($record->opening_balance, 2);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('closing_balance')
                    ->prefix('R')
                    ->getStateUsing(function (Statement $record) {
                        return number_format($record->closing_balance, 2);
                    })
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                
            ])
            ->actions([])
            ->bulkActions([]);
    }
}
