<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AttendanceCorrectionRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pending_requests_are_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
        ]);

        AttendanceCorrectionRequest::create([
            'attendance_id' => $attendance->id,
            'requested_clock_in' => '09:00',
            'requested_clock_out' => '18:00',
            'note' => '承認待ち',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/request/list');

        $response->assertStatus(200);
        $response->assertSee('承認待ち');
    }

    /** @test */
    public function approved_requests_are_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
        ]);

        AttendanceCorrectionRequest::create([
            'attendance_id' => $attendance->id,
            'requested_clock_in' => '09:00',
            'requested_clock_out' => '18:00',
            'note' => '承認済み',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/request/list?status=approved');

        $response->assertStatus(200);
        $response->assertSee('承認済み');
    }

    /** @test */
    public function request_detail_page_can_be_opened()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
        ]);

        $request = AttendanceCorrectionRequest::create([
            'attendance_id' => $attendance->id,
            'requested_clock_in' => '09:00',
            'requested_clock_out' => '18:00',
            'note' => '詳細',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->get("/admin/request/detail/{$request->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function request_can_be_approved()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
        ]);

        $request = AttendanceCorrectionRequest::create([
            'attendance_id' => $attendance->id,
            'requested_clock_in' => '09:00',
            'requested_clock_out' => '18:00',
            'note' => '承認テスト',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->post("/admin/request/approve/{$request->id}");

        $response->assertStatus(302);
    }
}