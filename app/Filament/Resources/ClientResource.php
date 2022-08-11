<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('postal_address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('physical_address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('branch_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('vat_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('client_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('client_surname')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tel_num')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cell_num')
                    ->maxLength(255),
                Forms\Components\TextInput::make('fax_num')
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_person')
                    ->maxLength(255),
                Forms\Components\TextInput::make('reg_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('reg_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('branch_code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('bank_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('client_status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('postal_address'),
                Tables\Columns\TextColumn::make('physical_address'),
                Tables\Columns\TextColumn::make('branch_name'),
                Tables\Columns\TextColumn::make('vat_number'),
                Tables\Columns\TextColumn::make('client_name'),
                Tables\Columns\TextColumn::make('client_surname'),
                Tables\Columns\TextColumn::make('tel_num'),
                Tables\Columns\TextColumn::make('cell_num'),
                Tables\Columns\TextColumn::make('fax_num'),
                Tables\Columns\TextColumn::make('contact_person'),
                Tables\Columns\TextColumn::make('reg_type'),
                Tables\Columns\TextColumn::make('reg_number'),
                Tables\Columns\TextColumn::make('account_name'),
                Tables\Columns\TextColumn::make('account_number'),
                Tables\Columns\TextColumn::make('account_type'),
                Tables\Columns\TextColumn::make('branch_code'),
                Tables\Columns\TextColumn::make('bank_name'),
                Tables\Columns\TextColumn::make('client_status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\ViewClient::route('/{record}/edit'),
        ];
    }    
}
