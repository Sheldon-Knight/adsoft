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
                    TextInput::make('name')
                        ->required(),
                    TextInput::make('surname')
                        ->required(),
                    Select::make('gender')
                        ->required()
                        ->options([
                            'male' => 'male',
                            'female' => 'female',
                            'other' => 'other',
                        ]),
                    Toggle::make('is_admin')
                        ->onIcon('heroicon-s-lightning-bolt')
                        ->offIcon('heroicon-s-user')
                ]),
            Step::make('Contact Info')
                ->description('contact info of the user')
                ->schema([
                    TextInput::make('email')

                        ->required()
                        ->email(),
                    TextInput::make('phone')
                    ->required()
                        ->numeric()
                        ->minValue(10),
                    Textarea::make('address')
                    ->required()
                        ->rows(12)
                        ->cols(20)
                ]),

            Step::make('Security Info')
                ->description('registering your login credentials')
                ->schema([
                    TextInput::make('password')
                    ->required()
                    ->password()
                    ->disableAutocomplete(),
                ]),
        ];
    }
}
