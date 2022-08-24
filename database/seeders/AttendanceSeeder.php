<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $startDate = new Carbon('2022-08-01');
        $endDate = new Carbon('2023-01-01');
        $all_dates = array();
        while ($startDate->lte($endDate)) {
            $all_dates[] = $startDate->toDateString();
            $startDate->addDay();
        }

        $data = [];

        for ($i = 0; $i < count($all_dates); $i++) {

            $present = mt_rand(0, 1);

            $timeIn = null;

            $timeOut = null;

            if ($present == 1) {
                $timeIn = "07:00";
                $timeOut = "16:00";
            }

            $data[] = [
                'user_id' => 1,
                'present' => $present,
                'day' => $all_dates[$i],
                'time_in' => $timeIn ?? null,
                'time_out' => $timeOut ?? null,
            ];
        }

        DB::table('attendances')->insert($data);
        // Attendance::create($data);
    }
}
