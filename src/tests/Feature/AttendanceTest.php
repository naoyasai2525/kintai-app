<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_clock_in()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post('/attendance/clock-in');

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_clock_out()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now(),
        ]);

        $this->actingAs($user);

        $this->post('/attendance/clock-out');

        $this->assertNotNull(
            $attendance->fresh()->clock_out
        );
    }

    /** @test */
    public function user_can_clock_in_only_once_per_day()
    {
        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now(),
        ]);

        $this->actingAs($user);

        $this->post('/attendance/clock-in');

        $this->assertEquals(
            1,
            Attendance::where('user_id', $user->id)
                ->whereDate('work_date', today())
                ->count()
        );
    }

    /** @test */
    public function user_can_start_break()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now(),
        ]);

        $this->actingAs($user);

        $this->post('/attendance/break-in');

        $this->assertDatabaseHas('break_times', [
            'attendance_id' => $attendance->id,
        ]);
    }

    /** @test */
    public function user_can_finish_break()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now(),
        ]);

        $break = $attendance->breakTimes()->create([
            'break_start' => now()->subHour(),
        ]);

        $this->actingAs($user);

        $this->post('/attendance/break-out');

        $this->assertNotNull(
            $break->fresh()->break_end
        );
    }

    /** @test */
    public function user_can_take_multiple_breaks()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now(),
        ]);

        $this->actingAs($user);

        $this->post('/attendance/break-in');
        $this->post('/attendance/break-out');

        $this->post('/attendance/break-in');
        $this->post('/attendance/break-out');

        $this->assertEquals(
            2,
            $attendance->fresh()->breakTimes()->count()
        );
    }
}