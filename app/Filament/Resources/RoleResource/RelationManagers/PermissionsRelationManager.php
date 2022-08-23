<?php

namespace App\Filament\Resources\RoleResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    protected static ?string $recordTitleAttribute = 'name';

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
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->using(function (HasRelationshipTable $livewire, array $data) {
                        $permission = Permission::find($data['recordId']);
                        $livewire->ownerRecord->givePermissionTo($permission->name);

                        Artisan::call('cache:clear');
                        return;
                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->using(function (Model $record, array $data): Model {
                        $record->removeRole($record->role_id);
                        Artisan::call('cache:clear');
                        return $record;
                    })

            ])
            ->bulkActions([]);
    }
}
