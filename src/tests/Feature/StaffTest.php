<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_staff_list()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        User::factory()->create([
            'name' => '田中太郎',
            'email' => 'tanaka@example.com',
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/staff/list');

        $response->assertStatus(200);
    }

    /** @test */
    public function staff_name_is_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        User::factory()->create([
            'name' => '田中太郎',
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/staff/list');

        $response->assertSee('田中太郎');
    }

    /** @test */
    public function staff_email_is_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        User::factory()->create([
            'email' => 'tanaka@example.com',
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/staff/list');

        $response->assertSee('tanaka@example.com');
    }

    /** @test */
    public function admin_can_view_staff_attendance_list()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now()->setTime(9, 0),
            'clock_out' => now()->setTime(18, 0),
        ]);

        $response = $this->actingAs($admin)
            ->get("/admin/staff/attendance/list/{$user->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function previous_month_can_be_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($admin)
            ->get("/admin/staff/attendance/list/{$user->id}?month=" . now()->subMonth()->format('Y-m'));

        $response->assertStatus(200);
    }

    /** @test */
    public function next_month_can_be_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($admin)
            ->get("/admin/staff/attendance/list/{$user->id}?month=" . now()->addMonth()->format('Y-m'));

        $response->assertStatus(200);
    }
}