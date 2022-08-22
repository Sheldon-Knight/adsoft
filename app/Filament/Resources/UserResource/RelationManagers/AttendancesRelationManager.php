<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendancesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    protected static ?string $recordTitleAttribute = 'present';

 
    public static function form(Form $form): Form
    {
       
        return $form
            ->schema([                  
                Forms\Components\DatePicker::make('day')
                    ->required()
                    ->reactive()
                    ->rules([
                        function (HasRelationshipTable $livewire){                            
                          
                            $userId = $livewire->ownerRecord->id;

                            return function (string $attribute, $value, Closure $fail) use ($userId) {
                                $value = Carbon::parse($value)->format('Y-m-d');
                                $attendance = Attendance::where('user_id', $userId)->where('day', $value)->first();
                                if ($attendance) {
                                    $fail("Record Already Exist");
                                }
                            };
                        },
                    ])
                    ->default(now()->format('Y-m-d')),

                Radio::make('present')
                    ->reactive()
                    ->options([
                        false => 'Absent',
                        true => 'Present ',

                    ])
                    ->default(false)
                    ->columnspan('full'),

                Forms\Components\TimePicker::make('time_in')->default('07:00')->withoutSeconds()->before('time_out')->reactive()->required(function (Closure $get) {
                    if ($get('present') == 0 or $get('present') == "false") {
                        return false;
                    }
                    return true;
                })->visible(function (Closure $get) {
                    if ($get('present') == 0 or $get('present') == "false") {
                        return false;
                    }
                    return true;
                }),
                Forms\Components\TimePicker::make('time_out')->default('16:00')->withoutSeconds()->after('time_in')->reactive()->required(function (Closure $get) {
                    if ($get('present') == 0 or $get('present') == "false") {
                        return false;
                    }
                    return true;
                })->visible(function (Closure $get) {
                    if ($get('present') == 0 or $get('present') == "false") {
                        return false;
                    }
                    return true;
                }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\BooleanColumn::make('present'),
                Tables\Columns\TextColumn::make('day')
                    ->date(),
                Tables\Columns\TextColumn::make('time_in'),
                Tables\Columns\TextColumn::make('time_out'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
