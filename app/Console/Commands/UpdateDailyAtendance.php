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

        $this->info("Geting All Users Total:{$users->count()}");

        foreach ($users as $user) {

            $this->info("Geting User {$user->name}");

            $yesterDaysAttendance = $user->getYesterdaysAttendance();

            $this->info("Checking yesterdays attendance for user {$user->name}");

            if ($yesterDaysAttendance == null) {

                $this->info("found no entry for yesterday user {$user->name}");

                $now = $user->freshTimestamp();

                $user->attendances()->create([
                    'day' => $now->subDay()->format('Y-m-d'),
                    'time_in' => null,
                    'time_out' => null,
                    'present' => false,
                ]);

                $this->info("updated {$user->name} attendance yesterday to absent");
            } else {
                $this->info("nothing to update");
            }
        }
        $this->info("Attendance Command Completed");
    }
}
