<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers\InvoicesRelationManager;
use App\Filament\Resources\ClientResource\RelationManagers\JobsRelationManager;
use App\Filament\Resources\ClientResource\RelationManagers\QuotesRelationManager;
use App\Models\User;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class ClientResource extends Resource
{
    protected static bool $isGloballySearchable = false;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $recordTitleAttribute = 'Client';

    protected static ?string $modelLabel = 'Client';

    protected static ?string $slug = 'clients';

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): EloquentBuilder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes()->whereHas('roles', function ($q) {
                $q->where('name', '=', 'Client');
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('surname')
                    ->required(),
                TextInput::make('email')
                    ->required()
                    ->email(),
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
                TextInput::make('password')
                    ->required()
                    ->password()
                    ->disableAutocomplete(),
                Textarea::make('address')
                    ->rows(12)
                    ->cols(20)
                    ->columnSpan('full'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Client Name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('surname')->label('Client Surname')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('address')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('gender')->searchable()->sortable(),
            ])
            ->filters([
                TextFilter::make('name'),
                TextFilter::make('surname'),
                SelectFilter::make('gender')->options(['male' => 'male', 'female' => 'female', 'other' => 'other']),
                TextFilter::make('phone'),
                TextFilter::make('email'),
                TextFilter::make('address'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Action::make('sms')
                    ->mountUsing(fn (ComponentContainer $form, User $record) => $form->fill([
                        'phone_number' => $record->phone,
                    ]))
                    ->color('success')->icon('heroicon-o-phone-outgoing')->action(function (User $record, $data) {
                        try {
                            $record->sendMessage($data['phone_number'], $data['message']);
                            Notification::make()
                                ->title('Sms Send')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Sms Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })->form([
                        Textarea::make('message')->required()->maxLength(400),
                        TextInput::make('phone_number')->required()->placeholder(function (callable $set, User $record) {
                            $set('phone_number', $record->phone);
                        }),
                    ]),
                EditAction::make(),
                ViewAction::make(),
                Tables\Actions\DeleteAction::make()->visible(function (User $record) {
                    if ($record->deleted_at != null) {
                        return false;
                    }

                    return auth()->user()->can('delete clients', $record);
                }),

                Tables\Actions\RestoreAction::make()->visible(function (User $record) {
                    if ($record->deleted_at === null) {
                        return false;
                    }

                    return auth()->user()->can('restore clients', $record);
                }),

                Tables\Actions\ForceDeleteAction::make()->visible(function (User $record) {
                    if ($record->deleted_at === null) {
                        return false;
                    }

                    return auth()->user()->can('force delete clients', $record);
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
            QuotesRelationManager::class,
            InvoicesRelationManager::class,
            JobsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
            'view' => Pages\ViewClient::route('/view/{record}'),
        ];
    }
}
