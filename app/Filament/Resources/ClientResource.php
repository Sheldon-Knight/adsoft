<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Filament\Resources\ClientResource\RelationManagers\InvoicesRelationManager;
use App\Filament\Resources\ClientResource\RelationManagers\JobsRelationManager;
use App\Filament\Resources\ClientResource\RelationManagers\QuotesRelationManager;
use App\Filament\Resources\ClientResource\Widgets\ClientInvoices;
use App\Models\Client;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'User Management';

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


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
                    ->maxLength(255)
                    ->required(),
                Forms\Components\TextInput::make('client_surname')
                    ->maxLength(255)
                    ->required(),
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client_surname')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('tel_num')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('contact_person')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client_status')->searchable()->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),
                Tables\Actions\DeleteAction::make()->visible(function (Client $record) {

                    if ($record->deleted_at != null) {
                        return false;
                    }

                    return auth()->user()->can('delete clients', $record);
                }),      

                Tables\Actions\RestoreAction::make()->visible(function (Client $record) {
                   
                    if ($record->deleted_at === null) {
                        return false;
                    }

                    return auth()->user()->can('restore clients', $record);
                }),

                Tables\Actions\ForceDeleteAction::make()->visible(function (Client $record) {

                    if ($record->deleted_at === null) {
                        return false;
                    }

                    return auth()->user()->can('force delete clients', $record);
                }),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            QuotesRelationManager::class,
            InvoicesRelationManager::class,
            JobsRelationManager::class,
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

    public static function getEloquentQuery(): EloquentBuilder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
