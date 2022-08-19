<?php

namespace App\Filament\Resources\AccountResource\RelationManagers;

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
                Tables\Columns\TextColumn::make('description'),
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
