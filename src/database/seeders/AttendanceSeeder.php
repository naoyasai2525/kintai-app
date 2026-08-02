<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {

            for ($i = 0; $i < 10; $i++) {

                $date = Carbon::now()->subDays($i);

                Attendance::create([
                    'user_id'   => $user->id,
                    'work_date' => $date->toDateString(),
                    'clock_in'  => $date->copy()->setTime(9, 0),
                    'clock_out' => $date->copy()->setTime(18, 0),
                ]);
            }
        }
    }
}