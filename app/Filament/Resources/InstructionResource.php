<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstructionResource\Pages;
use App\Filament\Resources\InstructionResource\RelationManagers;
use App\Filament\Resources\InstructionResource\Widgets\Comments;
use App\Models\Instruction;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\SelectFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\BooleanFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class InstructionResource extends Resource
{
    protected static ?string $model = Instruction::class;

    protected static ?string $navigationIcon = 'heroicon-o-support';

    protected static ?string $navigationGroup = 'Instructions';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('assigned_to')
                    ->label('Assign To User')
                    ->required()
                    ->searchable()
                    ->options(User::query()->pluck('name', 'id')),

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
            ->filters([DateFilter::make("date_completed"),
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
            ->headerActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction::make('export')
            ])
            ->bulkActions([\AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction::make('export')
            ]);
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Comments::class,
        ];
    }
   

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstructions::route('/'),
            'create' => Pages\CreateInstruction::route('/create'),
            'view' => Pages\ViewInstruction::route('/view/{record}'),
        ];
    }
}
