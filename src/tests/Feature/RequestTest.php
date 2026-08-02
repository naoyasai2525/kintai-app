<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AttendanceCorrectionRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pending_requests_are_displayed()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
        ]);

        AttendanceCorrectionRequest::create([
            'attendance_id' => $attendance->id,
            'requested_clock_in' => '09:00',
            'requested_clock_out' => '18:00',
            'note' => '修正申請',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->get('/request/list');

        $response->assertStatus(200);
        $response->assertSee('修正申請');
    }

    /** @test */
    public function approved_requests_are_displayed()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
        ]);

        AttendanceCorrectionRequest::create([
            'attendance_id' => $attendance->id,
            'requested_clock_in' => '09:00',
            'requested_clock_out' => '18:00',
            'note' => '承認済み申請',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)
            ->get('/request/list?status=approved');

        $response->assertStatus(200);
        $response->assertSee('承認済み申請');
    }

    /** @test */
    public function request_detail_page_can_be_opened()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
        ]);

        $request = AttendanceCorrectionRequest::create([
            'attendance_id' => $attendance->id,
            'requested_clock_in' => '09:00',
            'requested_clock_out' => '18:00',
            'note' => '詳細テスト',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->get("/request/detail/{$request->id}");

        $response->assertStatus(200);
    }
}