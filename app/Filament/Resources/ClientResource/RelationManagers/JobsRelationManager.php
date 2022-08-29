<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Models\Invoice;
use App\Models\Job;
use App\Models\Status;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class JobsRelationManager extends RelationManager
{
    protected static string $relationship = 'jobs';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Assign To User')
                    ->required()
                    ->searchable()
                    ->options(User::query()->pluck('name', 'id'))
                    ->visibleOn('view'),

                Select::make('created_by')
                    ->label('Created by User')
                    ->options(User::query()->pluck('name', 'id'))
                    ->visibleOn('view'),

                DatePicker::make('created_at')
                    ->label('Created At')
                    ->visibleOn('view'),

                DatePicker::make('date_completed')
                    ->label('Comepleted At')
                    ->visibleOn('view'),

                Select::make('invoice_id')
                    ->label('Job Invoice')
                    ->required()
                    ->searchable()
                    ->options(Invoice::query()->where('is_quote', false)->pluck('invoice_number', 'id')),

                Select::make('status_id')
                    ->label('Job Status')
                    ->required()
                    ->searchable()
                    ->options(Status::query()->pluck('name', 'id')),
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {


        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.client_name')->searchable(),
                Tables\Columns\TextColumn::make('user.name')->searchable(),
                Tables\Columns\TextColumn::make('department.name')->searchable(),
                Tables\Columns\TextColumn::make('invoice.invoice_number')->searchable(),
                Tables\Columns\BadgeColumn::make('status.name')->searchable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('description')->searchable(),
                Tables\Columns\TextColumn::make('date_completed')
                    ->date()->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->searchable(),
            ])
            ->filters([MultiSelectFilter::make('status')->relationship('status', 'name'),
            MultiSelectFilter::make('user')->relationship('user', 'name'),
            MultiSelectFilter::make('client')->relationship('client', 'client_name'),
            MultiSelectFilter::make('deaprtment')->relationship('department', 'name'),
            TextFilter::make('title'),
            TextFilter::make('description'),
            DateFilter::make('date_completed'),
            DateFilter::make('created_at'),
            TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make('date_completed')
                    ->label('Mark Complete')
                    ->form([
                        Forms\Components\DatePicker::make('date_completed')
                            ->label('Completed At')
                            ->required(),

                        Forms\Components\Select::make('status_id')
                            ->label('Status')
                            ->options(Status::pluck('name', 'id'))
                            ->required(),
                    ])
                    ->color("success")
                    ->icon("heroicon-o-check")
                    ->visible(function (Model $record) {
                        if ($record->date_completed != null) {
                            return false;
                        }
                        return true;
                    }),

                Tables\Actions\EditAction::make('status_id')
                    ->label('Change Status')
                    ->form([
                        Forms\Components\Select::make('status_id')
                            ->label('Status')
                            ->options(Status::pluck('name', 'id'))
                            ->required(),
                    ])
                    ->visible(function (Model $record) {
                        if ($record->date_completed != null) {
                            return false;
                        }
                        return true;
                    }),
                Tables\Actions\EditAction::make('user_id')
                    ->label('Reasign To')
                    ->color('warning')
                    ->action(function (Job $record, $data) {
                        $user = User::find($data["user_id"]);

                    $record->update([
                            'user_id' => $data["user_id"],
                            'department_id' => $user->department_id ?? null,
                            'department_id' => $user->department_id ?? null,
                        ]);
                        
                   
                        Notification::make()
                            ->title('Updated successfully')
                            ->success()
                            ->send();
                    })
                    ->form([
                        Forms\Components\Select::make('user_id')
                            ->label('Assign To User')
                            ->options(User::pluck('name', 'id'))
                            ->required(),
                    ])
                    ->visible(function (Model $record) {
                        if ($record->date_completed != null) {
                            return false;
                        }
                        return true;
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();

                        return $data;
                    }),
                \AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction::make('export')
            ])
            ->bulkActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction::make('export')
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
