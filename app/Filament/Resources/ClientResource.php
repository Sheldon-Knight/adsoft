<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Filament\Resources\ClientResource\RelationManagers\InvoicesRelationManager;
use App\Filament\Resources\ClientResource\RelationManagers\QuotesRelationManager;
use App\Filament\Resources\ClientResource\Widgets\ClientInvoices;
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
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('postal_address')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('physical_address')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('branch_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('vat_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client_surname')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('tel_num')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('cell_num')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('fax_num')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('contact_person')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('reg_type')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('reg_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('account_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('account_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('account_type')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('branch_code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('bank_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client_status')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->searchable()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->searchable()->sortable(),
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
            QuotesRelationManager::class,
           InvoicesRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
            'view' => Pages\ViewClient::route('/view/{record}'),
        ];
    }
}
