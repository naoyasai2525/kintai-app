<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAttendanceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_attendance_list()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        Attendance::create([
            'user_id' => $admin->id,
            'work_date' => today(),
            'clock_in' => now()->setTime(9, 0),
            'clock_out' => now()->setTime(18, 0),
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/attendance/list');

        $response->assertStatus(200);
    }

    /** @test */
    public function current_date_is_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/attendance/list');

        $response->assertStatus(200);
        $response->assertSee(now()->format('Y'));
    }

    /** @test */
    public function previous_day_can_be_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/attendance/list?date=' . now()->subDay()->toDateString());

        $response->assertStatus(200);
    }

    /** @test */
    public function next_day_can_be_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/attendance/list?date=' . now()->addDay()->toDateString());

        $response->assertStatus(200);
    }

    /** @test */
    public function attendance_detail_page_can_be_opened()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        $attendance = Attendance::create([
            'user_id' => $admin->id,
            'work_date' => today(),
            'clock_in' => now()->setTime(9, 0),
            'clock_out' => now()->setTime(18, 0),
        ]);

        $response = $this->actingAs($admin)
            ->get("/admin/attendance/detail/{$attendance->id}");

        $response->assertStatus(200);
    }
}