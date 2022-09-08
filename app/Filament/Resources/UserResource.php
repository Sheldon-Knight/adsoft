<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\AttendancesRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\DepartmentRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\InstructionsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\JobsRelationManager as RelationManagersJobsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\RolesRelationManager;
use App\Models\Department;
use App\Models\Job;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $modelLabel = 'Employee';

    public static function getEloquentQuery(): EloquentBuilder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes()->whereHas('roles', function ($q) {
                $q->where('name', '!=', 'Client');
            });
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                        ->required(),
                    TextInput::make('surname')
                        ->required(),
                    TextInput::make('email')
                        ->required()
                        ->email(),

                    Select::make('role')->options(Role::where('name', '!=', 'Client')->pluck('name', 'id')->toArray())->columnSpan('full')->visibleOn('create'),

                    Select::make('gender')
                        ->required()
                        ->options([
                            'male' => 'male',
                            'female' => 'female',
                            'other' => 'other',
                        ]),
                    TextInput::make('phone')
                        ->required()
                        ->numeric()
                        ->minValue(10),
                    Textarea::make('address')
                        ->rows(12)
                        ->cols(20),
                    TextInput::make('password')
                        ->required()
                        ->password()
                        ->disableAutocomplete(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('surname')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('department.name')->sortable()->searchable(),
                TextColumn::make('phone')->sortable()->searchable(),
                TextColumn::make('gender')->sortable(),
                TextColumn::make('roles.name')->sortable()->searchable(),
            ])
            ->filters([
                TextFilter::make('name'),
                SelectFilter::make('department')->relationship('department', 'name'),
                SelectFilter::make('roles')->relationship('roles', 'name', fn (EloquentBuilder $query) => $query->where('name', '!=', 'Client')),
                SelectFilter::make('gender')->options(['male' => 'male', 'female' => 'female', 'other' => 'other']),
                TextFilter::make('phone'),
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make(),
                Action::make('Remove from Department')
                    ->visible(function (User $record) {
                        if ($record->department_id != null and auth()->user()->can('remove users from departments', $record)) {
                            return true;
                        }

                        return false;
                    })
                    ->color('danger')
                    ->action(function (User $record) {
                        Job::where('user_id', $record->id)->where('department_id', $record->department_id)->update(['department_id' => null]);

                        $record->update(['department_id' => null]);

                        Notification::make()
                            ->title('User Removed From Department')
                            ->body('send')
                            ->danger()
                            ->send();
                    }),
                Action::make('Assign To Department')
                    ->visible(function (User $record) {
                        if ($record->deleted_at === null and auth()->user()->can('assign users to departments', $record)) {
                            return true;
                        }

                        return false;
                    })
                    ->label(function (User $record) {
                        if ($record->department_id == null) {
                            return 'Assign to department';
                        }

                        return 'Reassign to another department';
                    })
                    ->action(function (User $record, $data) {
                        Job::where('user_id', $record->id)->where('department_id', $record->department_id)->update(['department_id' => $data['department_id']]);

                        $record->update(['department_id' => $data['department_id']]);

                        Notification::make()
                            ->title('User Assigned To Department')
                            ->body('send')
                            ->success()
                            ->send();
                    })->form([
                        Select::make('department_id')
                            ->required()
                            ->options(function (User $record) {
                                if ($record->department_id != null) {
                                    return Department::where('id', '!=', $record->department_id)->get()->pluck('name', 'id')->toArray();
                                } else {
                                    return Department::get()->pluck('name', 'id')->toArray();
                                }
                            }),
                    ]),

                DeleteAction::make(),

                RestoreAction::make()->visible(function ($record) {
                    if ($record->deleted_at === null) {
                        return false;
                    }

                    return auth()->user()->can('restore users', $record);
                }),

                ForceDeleteAction::make()->visible(function ($record) {
                    if ($record->deleted_at === null) {
                        return false;
                    }

                    return auth()->user()->can('force delete users', $record);
                }),

            ])
            ->headerActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction::make('export'),
            ])
            ->bulkActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction::make('export'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagersJobsRelationManager::class,
            InstructionsRelationManager::class,
            DepartmentRelationManager::class,
            AttendancesRelationManager::class,
            RolesRelationManager::class,
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
