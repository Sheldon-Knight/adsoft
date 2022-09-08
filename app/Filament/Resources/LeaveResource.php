<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveResource\Pages;
use App\Models\Leave;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\MultiSelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')->relationship('user', 'name')->required()->searchable()->reactive()->disabled()->hiddenOn('create'),
                Forms\Components\Select::make('user_id')->relationship('user', 'name')->required()->searchable()->reactive()->hiddenOn(['edit', 'view']),
                Forms\Components\Select::make('type')->options(['Annual' => 'Annual Leave', 'Sick' => 'Sick Leave', 'Family' => 'Family Leave', 'Maternity' => 'Maternity Leave', 'Unpaid' => 'Unpaid Leave', 'Study' => 'Study Leave'])->disabled()->required()->hiddenOn('create'),
                Forms\Components\Select::make('type')->options(['Annual' => 'Annual Leave', 'Sick' => 'Sick Leave', 'Family' => 'Family Leave', 'Maternity' => 'Maternity Leave', 'Unpaid' => 'Unpaid Leave', 'Study' => 'Study Leave'])->required()->hiddenOn(['edit', 'view']),
                Forms\Components\DatePicker::make('from')
                    ->default(now()->addDay())
                    ->required()
                    ->disabled()
                    ->before('to')
                    ->hiddenOn('create'),
                Forms\Components\DatePicker::make('from')
                    ->default(now())
                    ->required()
                    ->before('to')
                    ->hiddenOn(['edit', 'view']),
                Forms\Components\DatePicker::make('to')
                    ->required()
                    ->disabled()
                    ->default(now()->addDays(2))
                    ->after('from')
                    ->hiddenOn('create'),
                Forms\Components\DatePicker::make('to')
                    ->default(now())
                    ->default(now()->addDays(2))
                    ->required()
                    ->after('from')
                    ->hiddenOn(['edit', 'view']),

                Forms\Components\Textarea::make('user_notes')->columnSpan('full')->disabled()->hiddenOn('create'),
                Forms\Components\Textarea::make('user_notes')->columnSpan('full')->hiddenOn(['edit', 'view']),
                Forms\Components\Textarea::make('revisioned_notes')
                    ->disabled(function (Model $record) {
                        if ($record->revisioned_by !== null and $record->revisioned_on != null) {
                            return true;
                        }

                        return false;
                    })->hiddenOn('create')->columnSpan('full'),

                Forms\Components\Select::make('status')
                    ->required()
                    ->disabled(function (Model $record) {
                        if ($record->revisioned_by !== null and $record->revisioned_on != null) {
                            return true;
                        }

                        return false;
                    })
                    ->options(['Rejected' => 'Rejected', 'Approved' => 'Approved'])
                    ->hiddenOn('create')->columnSpan('full'),

                FileUpload::make('attachments')
                    ->disabled()
                    ->reactive()
                    ->directory(function (Closure $get) {
                        $user = User::find($get('user_id'));

                        return 'user/'.$user->id.'/leave-attachments';
                    })
                    ->enableDownload()
                    ->enableOpen()
                    ->hiddenOn('create')
                    ->multiple()->columnSpan('full'),

                FileUpload::make('attachments')
                    ->reactive()
                    ->directory(function (Closure $get) {
                        $user = User::find($get('user_id'));

                        return 'user/'.$user->id.'/leave-attachments';
                    })
                    ->enableDownload()
                    ->enableOpen()
                    ->hiddenOn(['edit', 'view'])
                    ->multiple()->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('department.name'),
                Tables\Columns\TextColumn::make('revisionedBy.name'),
                Tables\Columns\TextColumn::make('from')
                    ->date(),
                Tables\Columns\TextColumn::make('to')
                    ->date(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('revisioned_on')
                    ->date(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'Rejected',
                        'success' => 'Approved',
                        'warning' => 'Pending',
                    ])
                    ->icons([
                        'heroicon-o-x-circle' => 'Rejected',
                        'heroicon-o-badge-check' => 'Approved',
                        'heroicon-o-clock' => 'Pending',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->label('Applied Date')
                    ->date(),
            ])
            ->filters([
                DateFilter::make('from'),
                DateFilter::make('to'),
                DateFilter::make('created_at'),
                DateFilter::make('revisioned_on'),
                MultiSelectFilter::make('type')
                    ->options(['Annual' => 'Annual Leave', 'Sick' => 'Sick Leave', 'Family' => 'Family Leave', 'Maternity' => 'Maternity Leave', 'Unpaid' => 'Unpaid Leave', 'Study' => 'Study Leave'])
                    ->column('type'),
                MultiSelectFilter::make('status')
                    ->options(['Rejected' => 'Rejected', 'Approved' => 'Approved', 'Pending' => 'Pending'])
                    ->column('status'),
                MultiSelectFilter::make('user_id')
                    ->relationship('user', 'name'),
                MultiSelectFilter::make('department_id')
                    ->relationship('department', 'name'),
                MultiSelectFilter::make('revisioned_by')
                    ->relationship('revisionedBy', 'name'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(
                    function (Model $record) {
                        if ($record->revisioned_by !== null and $record->revisioned_on != null) {
                            return true;
                        }

                        return false;
                    }
                )->label('Revise')
                    ->color('success'),
                Action::make('Upload Files')
                    ->icon('heroicon-o-upload')->hidden(
                        function (Model $record) {
                            if ($record->user_id !== auth()->id()) {
                                return true;
                            }
                            if ($record->deleted_at != null) {
                                return true;
                            }

                            return false;
                        }
                    )->form([
                        FileUpload::make('attachments')
                            ->directory(function (Model $record) {
                                return 'user/'.$record->user_id.'/leave-attachments';
                            })
                            ->enableDownload()
                            ->enableOpen()
                            ->multiple()->columnSpan('full'),
                    ])
                    ->action(function (Model $record, $data) {
                        $attachments = $record->attachments;

                        foreach ($data['attachments'] as $attachment) {
                            array_push($attachments, $attachment);
                        }

                        $record->update(['attachments' => $attachments]);

                        Notification::make()
                            ->title('Uploaded successfully')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
