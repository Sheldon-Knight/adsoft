<?php

namespace App\Filament\Resources\InventoryResource\RelationManagers;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Models\InventoryReadings;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class ReadingsRelationManager extends RelationManager
{
    protected static string $relationship = 'readings';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date')->required()->columnSpan('full')->disabled()->hiddenOn('create'),
                DatePicker::make('date')->required()->columnSpan('full')->hiddenOn('edit'),
                KeyValue::make('start_readings')->label('Opening Reading')->required()->columnSpan('full')->hiddenOn('edit'),
                KeyValue::make('start_readings')->label('Opening Reading')->required()->columnSpan('full')->disabled()->hiddenOn('create'),

                KeyValue::make('end_readings')->label('Closing Reading')->disabled(function (InventoryReadings $record) {
                    if ($record->end_readings === null) {
                        return false;
                    } else {
                        return true;
                    }
                })->columnSpan('full')->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')->date(),
                ViewColumn::make('start_readings')->label('Opening Reading')->view('tables.columns.attribute-viewer'),
                ViewColumn::make('end_readings')->label('Closing Reading')->view('tables.columns.attribute-viewer'),
            ])
            ->filters([
                DateFilter::make('date'),
                TextFilter::make('start_readings'),
                TextFilter::make('end_readings'),
            ])
            ->headerActions([
                CreateAction::make(),
                FilamentExportHeaderAction::make('export'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\DeleteBulkAction::make(),
                FilamentExportBulkAction::make('export'),
            ]);
    }
}
