<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Closure;
use DateTime;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class UserAttendantWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.user-attendant-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public Attendance $attendance;

    public $now;

    public static function canView(): bool
    {

        if (cache()->get('hasExpired') == true) {
            return false;
        };

        return true;
    }
    

    public function now()
    {
        $this->now = now()->format("H:i");
    }
    public function mount(): void
    {
        if (auth()->user()->getTodaysAttendance()) {

            $this->attendance = auth()->user()->getTodaysAttendance();
        }

        $this->now = now()->format("H:i");

        $this->checkinForm->fill();

        $this->checkoutForm->fill();
    }

    protected function getCheckinFormSchema(): array
    {
        return [];
    }

    protected function getCheckoutFormSchema(): array
    {
        return [
            TimePicker::make('check_in')->label('You Have Checked In At')->withoutSeconds()->default(function () {
                return $this->attendance->time_in ?? "00:00";
            })
                ->disabled(),
        ];
    }

    protected function getCheckinoutFormSchema(): array
    {
        return [
            TimePicker::make('check_in')->label('You Have Checked In At')->withoutSeconds()->default(function () {
                return $this->attendance->time_in ?? "00:00";
            })->disabled(),

            TimePicker::make('check_in')->label('You Have Checked Out At')->withoutSeconds()->default(function () {
                return $this->attendance->time_out ?? "00:00";
            })->disabled(),
        ];
    }

    protected function getForms(): array
    {
        return [
            'checkinForm' => $this->makeForm()
                ->schema($this->getCheckinFormSchema()),
            'checkoutForm' => $this->makeForm()
                ->schema($this->getCheckoutFormSchema()),
            'checkinoutForm' => $this->makeForm()
                ->schema($this->getCheckinoutFormSchema()),
        ];
    }

    public function checkIn()
    {
        auth()->user()->checkIn();

        Notification::make()
            ->title('You Have Succesfully Checked In')
            ->success()
            ->send();

            return redirect()->route('filament.pages.dashboard');
    }

    public function checkOut()
    {
        auth()->user()->checkOut();

        Notification::make()
            ->title('You Have Succesfully Checked Out')
            ->success()
            ->send();

        return redirect()->route('filament.pages.dashboard');
    }
}
