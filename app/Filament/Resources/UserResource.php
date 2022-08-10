<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages; 
use App\Models\User; 
use Filament\Forms\Components\Card; 
use Filament\Forms\Components\Select; 
use Filament\Forms\Components\Textarea; 
use Filament\Forms\Components\TextInput; 
use Filament\Forms\Components\Toggle; 
use Filament\Resources\Form; 
use Filament\Resources\Resource; 
use Filament\Resources\Table; 
use Filament\Tables\Actions\DeleteAction; 
use Filament\Tables\Actions\DeleteBulkAction; 
use Filament\Tables\Actions\EditAction; 
use Filament\Tables\Columns\BadgeColumn; 
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn; 

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    
    protected static ?int $navigationSort = 2;  

    
    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }   

    public static function form(Form $form): Form
    {        
        return $form
            ->schema([
                Card::make([
                    static::getNameFormField(),
                    static::getSurnameFormField(),
                    static::getGenderFormField(),
                    static::getEmailFormField(),
                    static::getPhoneFormField(),
                    static::getAddressFormField(),
                    static::getPasswordFormField(),
                    static::getIsAdminFormField(),
                ])
            ]);
    }

    public static function getPasswordFormField()
    {
        return TextInput::make('password')
            ->required()
            ->password()
            ->disableAutocomplete();
    }

    public static function getEmailFormField()
    {
        return TextInput::make('email')
            ->required()
            ->email();
    }

    public static function getPhoneFormField()
    {
        return TextInput::make('phone')
            ->required()
            ->numeric()
            ->minValue(10);
    }

    public static function getAddressFormField()
    {
        return Textarea::make('address')            
            ->rows(12)
            ->cols(20);
    }

    public static function getNameFormField()
    {
        return TextInput::make('name')
            ->required();
    }

    public static function getSurnameFormField()
    {
        return TextInput::make('surname')
            ->required();
    }

    public static function getGenderFormField()
    {
        return Select::make('gender')
            ->required()
            ->options([
                'male' => 'male',
                'female' => 'female',
                'other' => 'other',
            ]);
    }

    public static function getIsAdminFormField()
    {
        return Toggle::make('is_admin')
            ->onIcon('heroicon-s-lightning-bolt')
            ->offIcon('heroicon-s-user');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('surname')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('address')->getStateUsing(function (User $record) {
                    return substr($record->address, 0, 10);
                })->sortable(),
                TextColumn::make('phone')->sortable()->searchable(),
                TextColumn::make('gender')->sortable(),
                BadgeColumn::make('Role')
                    ->getStateUsing(function (User $record) {
                        return $record->is_admin ? "Admin" : "Users";
                    })->colors([
                        'success'  => "Admin",
                        'warning'  => "Users",
                    ]),
                BooleanColumn::make('is_admin'),
            ])
            ->defaultSort('name')
            ->actions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),           
        ];
    }    
}
