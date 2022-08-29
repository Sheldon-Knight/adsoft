<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class InstructionsRelationManager extends RelationManager
{
    protected static string $relationship = 'instructions';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('assigned_to')
                    ->label('Assign To User')
                    ->required()
                    ->searchable()
                    ->options(User::query()->pluck('name', 'id'))
                    ->visibleOn('view'),

                Select::make('created_by')
                    ->label('Created By')
                    ->required()
                    ->searchable()
                    ->options(User::query()->pluck('name', 'id'))
                    ->visibleOn('view'),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('instruction')
                    ->required(),

                Forms\Components\DatePicker::make('due_date')
                    ->required(),


                Forms\Components\DatePicker::make('date_completed')
                    ->required()
                    ->visibleOn('view'),

                Forms\Components\DatePicker::make('created_at')
                    ->required()
                    ->visibleOn('view'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('createdBy.name'),
                Tables\Columns\TextColumn::make('assignedTo.name'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('instruction'),
                Tables\Columns\TextColumn::make('due_date')
                    ->date(),
                Tables\Columns\TextColumn::make('date_completed')
                    ->date(),
                Tables\Columns\BooleanColumn::make('status')
                    ->label("Completed")
                    ->trueIcon('heroicon-o-badge-check')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                DateFilter::make("date_completed"),
                DateFilter::make("created_at"),
                DateFilter::make("due_date"),
                TextFilter::make("instruction"),
                TextFilter::make("title"),
                SelectFilter::make('status')
                    ->options([
                        0 => 'In-Completed',
                        1 => 'Completed',
                    ]),
                MultiSelectFilter::make('created_by')->relationship('createdBy', 'name'),
                MultiSelectFilter::make('assigend_to')->relationship('assignedTo', 'name'),
                TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by'] = auth()->id();

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make('date_completed')
                    ->label('Mark Complete')
                    ->form([
                        Forms\Components\DatePicker::make('date_completed')
                            ->label('Completed At')
                            ->required(),
                    ])
                    ->color("success")
                    ->icon("heroicon-o-check")
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['status'] = true;
                        return $data;
                    })

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
