<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $user = static::getModel()::create($data);

        $role = Role::find($data['role']);

        $user->assignRole($role->name);

        return $user;
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Basic Info')
                ->description('basic info of the user')
                ->schema([
                    Select::make('role')->options(Role::all()->pluck('name','id')->toArray()),
                    UserResource::getNameFormField(),
                    UserResource::getSurnameFormField(),
                    UserResource::getGenderFormField(),
                ]),
            Step::make('Contact Info')
                ->description('contact info of the user')
                ->schema([
                    UserResource::getEmailFormField(),
                    UserResource::getPhoneFormField(),
                    UserResource::getAddressFormField(),
                    
                ]),

            Step::make('Security Info')
                ->description('registering your login credentials')
                ->schema([
                    UserResource::getPasswordFormField(),
                ]),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make($data['password']);

        return $data;
    }
}
