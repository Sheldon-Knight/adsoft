<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OmsSettingResource\Pages;
use App\Filament\Resources\OmsSettingResource\RelationManagers;
use App\Models\OmsSetting;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OmsSettingResource extends Resource
{
    protected static ?string $model = OmsSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Settings';

    public static function canCreate(): bool
    {
        if (OmsSetting::count() == 0 && auth()->user()->is_admin == true) {
            return true;
        }      

        return false;
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('oms_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('oms_company_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('oms_email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('oms_company_tel')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('oms_company_address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('oms_company_vat')
                    ->maxLength(255),
                Forms\Components\TextInput::make('oms_company_registration')
                    ->maxLength(255),         
            FileUpload::make('oms_logo'),
                Forms\Components\Toggle::make('oms_status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('oms_name'),
                Tables\Columns\TextColumn::make('oms_company_name'),
                Tables\Columns\TextColumn::make('oms_email'),
                Tables\Columns\TextColumn::make('oms_company_tel'),
                Tables\Columns\TextColumn::make('oms_company_address'),
                Tables\Columns\TextColumn::make('oms_company_vat'),
                Tables\Columns\TextColumn::make('oms_company_registration'),
                Tables\Columns\TextColumn::make('oms_logo'),
                Tables\Columns\BooleanColumn::make('oms_status'),
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
            'index' => Pages\ListOmsSettings::route('/'),
            'create' => Pages\CreateOmsSetting::route('/create'),
            'edit' => Pages\EditOmsSetting::route('/{record}/edit'),
        ];
    }
}
