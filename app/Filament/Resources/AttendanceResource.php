<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Widgets\AttendanceCalendarWidget;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')->options(User::pluck('name', 'id')->toArray())->required()->searchable()->reactive(),
                Forms\Components\DatePicker::make('day')
                    ->required()
                    ->reactive()
                    ->rules([
                        function (?callable $get) {
                            $userId = $get('user_id');

                            return function (string $attribute, $value, Closure $fail) use ($userId) {
                                $value = Carbon::parse($value)->format('Y-m-d');
                                $attendance = Attendance::where('user_id', $userId)->where('day', $value)->first();
                                if ($attendance) {
                                    $fail('Record Already Exist');
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
                    if ($get('present') == 0 or $get('present') == 'false') {
                        return false;
                    }

                    return true;
                })->visible(function (Closure $get) {
                    if ($get('present') == 0 or $get('present') == 'false') {
                        return false;
                    }

                    return true;
                }),
                Forms\Components\TimePicker::make('time_out')->default('16:00')->withoutSeconds()->after('time_in')->reactive()->required(function (Closure $get) {
                    if ($get('present') == 0 or $get('present') == 'false') {
                        return false;
                    }

                    return true;
                })->visible(function (Closure $get) {
                    if ($get('present') == 0 or $get('present') == 'false') {
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
                MultiSelectFilter::make('users')
                ->relationship('user', 'name'),

            SelectFilter::make('present')
            ->options([
                0 => 'Absent',
                1 => 'Present',
            ]),
            DateFilter::make('day'),

            Filter::make('time_in')
            ->form([
                TimePicker::make('time_in')->withoutSeconds(),
            ])
                ->query(function (EloquentBuilder $query, array $data): EloquentBuilder {
                    return $query
                        ->when(
                            $data['time_in'],
                            fn (EloquentBuilder $query, $date): EloquentBuilder => $query->where('time_in', '=', Carbon::parse($date)->format('H:i:s')),
                        );
                }),

            Filter::make('time_out')
            ->form([
                TimePicker::make('time_out')->withoutSeconds(),
            ])
            ->query(function (EloquentBuilder $query, array $data): EloquentBuilder {
                return $query
                    ->when(
                        $data['time_out'],
                        fn (EloquentBuilder $query, $date): EloquentBuilder => $query->where('time_out', '=', Carbon::parse($date)->format('H:i:s')),
                    );
            }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

                Tables\Actions\DeleteAction::make()->visible(function (Attendance $record) {
                    if ($record->deleted_at != null) {
                        return false;
                    }

                    return auth()->user()->can('delete attendances', $record);
                }),

                Tables\Actions\RestoreAction::make()->visible(function (Attendance $record) {
                    if ($record->deleted_at === null) {
                        return false;
                    }

                    return auth()->user()->can('restore attendances', $record);
                }),

                Tables\Actions\ForceDeleteAction::make()->visible(function (Attendance $record) {
                    if ($record->deleted_at === null) {
                        return false;
                    }

                    return auth()->user()->can('force delete attendances', $record);
                }),

            ])
            ->bulkActions([\AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction::make('export'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
            'view' => Pages\ViewAttendance::route('/view/{record}'),
        ];
    }

    public static function getEloquentQuery(): EloquentBuilder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            AttendanceCalendarWidget::class,
        ];
    }

}
