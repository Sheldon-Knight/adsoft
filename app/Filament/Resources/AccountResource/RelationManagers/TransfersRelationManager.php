<?php

namespace App\Filament\Resources\AccountResource\RelationManagers;

use App\Models\Transfer;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;


class TransfersRelationManager extends RelationManager
{
    protected static string $relationship = 'transfersFrom';

    protected static ?string $recordTitleAttribute = 'description';

    protected static ?string $title = 'Transfers';


    public static function form(Form $form): Form
    {    
        return $form
            ->schema([
                         

           
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([            
                Tables\Columns\TextColumn::make('fromAccount.account_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('toAccount.account_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->prefix('R')
                    ->getStateUsing(function (Transfer $record) {
                        return number_format($record->amount , 2);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Transfer Date')
                    ->dateTime()
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction.transaction_id')->searchable()
                    ->label('Transaction ID')->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([       
                
            ])
            ->actions([
    
            ])
            ->bulkActions([
                
            ]);
    }    
}
