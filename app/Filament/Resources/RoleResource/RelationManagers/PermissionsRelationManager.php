<?php

namespace App\Filament\Resources\RoleResource\RelationManagers;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
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

                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->using(function (Model $record, array $data): Model {
                        $record->removeRole($record->role_id);
                        Artisan::call('cache:clear');

                        return $record;
                    }),

            ])
            ->bulkActions([
                BulkAction::make('bulk detach')
                ->action(function (Collection $records) {
                    foreach ($records as $record) {
                        $record->removeRole($record->role_id);
                    }

                    Artisan::call('cache:clear');

                    return Notification::make()
                        ->title('Removed successfully')
                        ->success()
                        ->send();
                })
                ->deselectRecordsAfterCompletion(),
            ]);
    }
}
