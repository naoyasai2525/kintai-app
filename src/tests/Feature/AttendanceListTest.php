<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_his_attendance_list()
    {
        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now()->setTime(9, 0),
            'clock_out' => now()->setTime(18, 0),
        ]);

        $response = $this->actingAs($user)
            ->get('/attendance/list');

        $response->assertStatus(200);

        $response->assertSee(
            today()->format('m/d')
        );
    }

    /** @test */
    public function current_month_is_displayed()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/attendance/list');

        $response->assertStatus(200);

        $response->assertSee(
            now()->format('Y/m')
        );
    }

    /** @test */
    public function previous_month_can_be_displayed()
    {
        $user = User::factory()->create();

        $previousMonth = now()->subMonth();

        $response = $this->actingAs($user)
            ->get(
                '/attendance/list?month=' .
                $previousMonth->format('Y-m')
            );

        $response->assertStatus(200);

        $response->assertSee(
            $previousMonth->format('Y/m')
        );
    }

    /** @test */
    public function next_month_can_be_displayed()
    {
        $user = User::factory()->create();

        $nextMonth = now()->addMonth();

        $response = $this->actingAs($user)
            ->get(
                '/attendance/list?month=' .
                $nextMonth->format('Y-m')
            );

        $response->assertStatus(200);

        $response->assertSee(
            $nextMonth->format('Y/m')
        );
    }

    /** @test */
    public function attendance_detail_page_can_be_opened()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now(),
        ]);

        $response = $this->actingAs($user)
            ->get("/attendance/detail/{$attendance->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function other_users_attendance_is_not_displayed()
    {
        $user = User::factory()->create();

        $otherUser = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(9, 0),
            'clock_out' => today()->setTime(18, 0),
        ]);

        Attendance::create([
            'user_id' => $otherUser->id,
            'work_date' => today()->subDay(),
            'clock_in' => today()->subDay()->setTime(10, 0),
            'clock_out' => today()->subDay()->setTime(17, 0),
        ]);

        $response = $this->actingAs($user)
            ->get('/attendance/list');

        $response->assertStatus(200);

        $response->assertSee(
            today()->format('m/d')
        );

        $response->assertDontSee(
            today()->subDay()->format('m/d')
        );
    }

    /** @test */
    public function attendance_times_are_displayed_correctly()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(9, 0),
            'clock_out' => today()->setTime(18, 0),
        ]);

        $attendance->breakTimes()->create([
            'break_start' => today()->setTime(12, 0),
            'break_end' => today()->setTime(13, 0),
        ]);

        $response = $this->actingAs($user)
            ->get('/attendance/list');

        $response->assertStatus(200);

        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('1:00');
        $response->assertSee('8:00');
    }
}