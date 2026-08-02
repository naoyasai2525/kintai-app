<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BreakTimeSeeder extends Seeder
{
    public function run()
    {
        $attendances = Attendance::all();

        foreach ($attendances as $attendance) {

            $date = Carbon::parse($attendance->work_date);

            BreakTime::create([
                'attendance_id' => $attendance->id,
                'break_start' => $date->copy()->setTime(12, 0),
                'break_end' => $date->copy()->setTime(13, 0),
            ]);
        }
    }
}