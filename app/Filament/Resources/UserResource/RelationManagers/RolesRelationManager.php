<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Role;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';

    protected static ?string $recordTitleAttribute = 'name';

    protected function canAttach(): bool
    {
        if ($this->ownerRecord->hasAnyRole(Role::all())) {
            return false;
        }
        
        return true;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                TextFilter::make('name'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction::make('export'),
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }

    protected function getTableFiltersFormColumns(): int
    {
        return 3;
    }

    protected function getTableFiltersFormWidth(): string
    {
        return '4xl';
    }

    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }
}
