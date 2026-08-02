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

        $response = $this->actingAs($user)
            ->get('/attendance/list?month=' . now()->subMonth()->format('Y-m'));

        $response->assertStatus(200);
    }

    /** @test */
    public function next_month_can_be_displayed()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/attendance/list?month=' . now()->addMonth()->format('Y-m'));

        $response->assertStatus(200);
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
}