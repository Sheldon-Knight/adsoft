<?php

namespace App\Filament\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Models\Leave;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;

class MyLeaves extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-copy';

    protected static string $view = 'filament.pages.my-leaves';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'My Workflow';

    protected static ?string $title = 'Leave Applications';

    protected static ?string $navigationLabel = 'My Leave Applications';

    protected static ?string $slug = 'my-leaves-applications';

    protected function getTableQuery(): Builder
    {
        return Leave::query()->where('user_id', auth()->id());
    }

    protected function getTableColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('user.name'),
            \Filament\Tables\Columns\TextColumn::make('department.name'),
            \Filament\Tables\Columns\TextColumn::make('revisionedBy.name'),
            \Filament\Tables\Columns\TextColumn::make('from')
                ->date(),
            \Filament\Tables\Columns\TextColumn::make('to')
                ->date(),
            \Filament\Tables\Columns\TextColumn::make('type'),
            \Filament\Tables\Columns\TextColumn::make('revisioned_on')
                ->date(),
            \Filament\Tables\Columns\BadgeColumn::make('status')
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
            \Filament\Tables\Columns\TextColumn::make('created_at')->label('Applied Date')
                ->date(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
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
            TrashedFilter::make(),
        ];
    }


    protected function getTableActions(): array
    {

      

        return [
            TableAction::make('Upload Files')
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
                            return 'user/' . $record->user_id . '/leave-attachments';
                        })
                        ->enableDownload()
                        ->enableOpen()
                        ->multiple()
                        ->columnSpan('full'),
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
            ViewAction::make()
                ->form([

                    Select::make('type')->options(['Annual' => 'Annual Leave', 'Sick' => 'Sick Leave', 'Family' => 'Family Leave', 'Maternity' => 'Maternity Leave', 'Unpaid' => 'Unpaid Leave', 'Study' => 'Study Leave'])->required(),

                    DatePicker::make('from')
                        ->default(now()->addDay())
                        ->before('to')
                        ->required(),

                    DatePicker::make('to')
                        ->required()
                        ->after('from'),

                    Textarea::make('user_notes')->columnSpan('full'),

                    FileUpload::make('attachments')
                        ->reactive()
                        ->directory('user/' . auth()->id() . '/leave-attachments')
                        ->enableDownload()
                        ->enableOpen()
                        ->multiple()
                        ->columnSpan('full'),
                ]),
            DeleteAction::make()->visible(function (Leave $record) {
                if (cache()->get('hasExpired') == true) {
                    return false;
                }

                if ($record->status != 'Pending') {
                    return false;
                }

                if ($record->deleted_at != null) {
                    return false;
                }

                return true;

                // return auth()->user()->can('delete leaves');
            }),
            RestoreAction::make()->visible(function (Leave $leave) {
                if (cache()->get('hasExpired') == true) {
                    return false;
                }
                if ($leave->deleted_at === null) {
                    return false;
                }

                if ($leave->status != 'Pending') {
                    return false;
                }

                return auth()->user()->can('restore leaves');
            }),
            ForceDeleteAction::make()->visible(function (Leave $leave) {
                if (cache()->get('hasExpired') == true) {
                    return false;
                }

                if ($leave->deleted_at === null) {
                    return false;
                }
                if ($leave->status != 'Pending') {
                    return false;
                }

                return auth()->user()->can('force delete leaves');
            }),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
    

        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return true;
    }

    protected function getTableBulkActions(): array
    {
        return [
            FilamentExportBulkAction::make('export'),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            FilamentExportHeaderAction::make('export'),
        ];
    }

    protected function getActions(): array
    {
        return [
            Action::make('Apply For Leave')
                ->action(function (array $data) {
                    $data['user_id'] = auth()->id();

                    $data['department_id'] = auth()->user()->department_id ?? null;

                    Leave::create($data);

                    Notification::make()
                        ->title('Saved successfully')
                        ->success()
                        ->send();
                })
                ->form([

                    Select::make('type')->options(['Annual' => 'Annual Leave', 'Sick' => 'Sick Leave', 'Family' => 'Family Leave', 'Maternity' => 'Maternity Leave', 'Unpaid' => 'Unpaid Leave', 'Study' => 'Study Leave'])->required(),

                    DatePicker::make('from')
                        ->default(now()->addDay())
                        ->before('to')
                        ->required(),

                    DatePicker::make('to')
                        ->required()
                        ->after('from'),

                    Textarea::make('user_notes')->columnSpan('full'),

                    FileUpload::make('attachments')
                        ->reactive()
                        ->directory('user/' . auth()->id() . '/leave-attachments')
                        ->enableDownload()
                        ->enableOpen()
                        ->multiple()->columnSpan('full'),
                ]),

        ];
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
