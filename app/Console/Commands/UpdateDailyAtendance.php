<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateDailyAtendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating Daily Attendance';

    /**
     * Execute the console command.
     *
     * @return int
     */
    
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $yesterDaysAttendance = $user->getYesterdaysAttendance();

            if ($yesterDaysAttendance == null) {
                $now = $user->freshTimestamp();

                $user->attendances()->create([
                    'day' => $now->subDay()->format('Y-m-d'),
                    'time_in' => null,
                    'time_out' => null,
                    'present' => false,
                ]);
            }
        }
    }
}
