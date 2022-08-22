<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\RelationManagers\JobsRelationManager;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\AttendancesRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\DepartmentRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\InstructionsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\JobsRelationManager as RelationManagersJobsRelationManager;
use App\Models\Department;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'User Management';




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
                TextColumn::make('department.name')->sortable()->searchable(),
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
                DeleteAction::make(),
                ViewAction::make(),
                Action::make('Assign To Department')->action(function (User $record, $data) {
                    $record->update(['department_id' => $data['department_id']]);

                    Notification::make()
                        ->title("User Assigned To Department")
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
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagersJobsRelationManager::class,
            InstructionsRelationManager::class,
            DepartmentRelationManager::class,
            AttendancesRelationManager::class,
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
