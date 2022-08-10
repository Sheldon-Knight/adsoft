<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = UserResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Basic Info')
                ->description('basic info of the user')
                ->schema([
                    UserResource::getNameFormField(),
                    UserResource::getSurnameFormField(),
                    UserResource::getGenderFormField(),
                    UserResource::getIsAdminFormField(),
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
                    UserResource::getPasswordFormField()
                ]),
        ];
    }

    
}
