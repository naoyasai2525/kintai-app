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
        $response->assertSee('詳細テスト');
    }

    /** @test */
    public function other_users_requests_are_not_displayed()
    {
        $user = User::factory()->create();

        $otherUser = User::factory()->create();

        $myAttendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
        ]);

        $otherAttendance = Attendance::create([
            'user_id' => $otherUser->id,
            'work_date' => today()->subDay(),
        ]);

        AttendanceCorrectionRequest::create([
            'attendance_id' => $myAttendance->id,
            'requested_clock_in' => '09:00',
            'requested_clock_out' => '18:00',
            'note' => '自分の申請',
            'status' => 'pending',
        ]);

        AttendanceCorrectionRequest::create([
            'attendance_id' => $otherAttendance->id,
            'requested_clock_in' => '10:00',
            'requested_clock_out' => '17:00',
            'note' => '他人の申請',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->get('/request/list');

        $response->assertStatus(200);

        $response->assertSee('自分の申請');
        $response->assertDontSee('他人の申請');
    }

    /** @test */
    public function pending_request_detail_cannot_be_edited()
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
            'note' => '承認待ちテスト',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->get("/request/detail/{$request->id}");

        $response->assertStatus(200);

        $response->assertSee(
            '承認待ちのため修正はできません。'
        );
    }
}