<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers\PermissionsRelationManager;
use App\Filament\Resources\RoleResource\RelationManagers\UsersRelationManager;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\MultiSelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'User Management';




    public static function form(Form $form): Form
    {       
        return $form
            ->schema([
                TextInput::make('name')->label('Name')->required()->unique()->columnSpan('full'),

                MultiSelect::make('permissions')
                    ->options(
                        Permission::all()->pluck('name', 'id')->toArray(),
                    )->columnSpan('full')->hiddenOn(['edit','view']),

                MultiSelect::make('users')
                    ->options(
                        User::doesntHave('roles')->get()->pluck('name', 'id')->toArray(),
                    )->columnSpan('full')->hiddenOn(['edit','view']),

                MultiSelect::make('permissions')                
                    ->relationship(
                        'permissions',
                        'name'
                    )->columnSpan('full')->hiddenOn(['edit','create']),

              MultiSelect::make('users')
                    ->relationship(
                        'users',
                        'name'
                    )->columnSpan('full')->hiddenOn(['edit','create']),                       

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Role Name')->searchable()->sortable(),
                TagsColumn::make('permissions.name')->separator(','),
                TextColumn::make('users_count')->counts('users')->sortable(),
            ])
            ->filters([
                TextFilter::make('name'),
                MultiSelectFilter::make('permissions')->relationship('permissions', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction::make('export'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PermissionsRelationManager::class,
            UsersRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('name', '!=', 'Super Admin')
            ->where('name', '!=', 'Client')
            ->withoutGlobalScopes();
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
