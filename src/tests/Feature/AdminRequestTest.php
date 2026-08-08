<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AttendanceCorrectionRequest;
use App\Models\AttendanceCorrectionRequestBreak;
use App\Models\BreakTime;
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
    public function requested_break_times_are_displayed_on_request_detail()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => '2026-08-06',
            'clock_in' => '2026-08-06 09:00:00',
            'clock_out' => '2026-08-06 18:00:00',
        ]);

        $request = AttendanceCorrectionRequest::create([
            'attendance_id' => $attendance->id,
            'requested_clock_in' => '09:10',
            'requested_clock_out' => '18:00',
            'note' => '休憩修正テスト',
            'status' => 'pending',
        ]);

        AttendanceCorrectionRequestBreak::create([
            'attendance_correction_request_id' => $request->id,
            'break_start' => '12:00',
            'break_end' => '12:30',
        ]);

        AttendanceCorrectionRequestBreak::create([
            'attendance_correction_request_id' => $request->id,
            'break_start' => '15:00',
            'break_end' => '15:10',
        ]);

        $response = $this->actingAs($admin)
            ->get("/admin/request/detail/{$request->id}");

        $response->assertStatus(200);

        $response->assertSee('休憩1');
        $response->assertSee('12:00');
        $response->assertSee('12:30');

        $response->assertSee('休憩2');
        $response->assertSee('15:00');
        $response->assertSee('15:10');
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
            'work_date' => '2026-08-06',
            'clock_in' => '2026-08-06 09:00:00',
            'clock_out' => '2026-08-06 18:00:00',
        ]);

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => '2026-08-06 12:00:00',
            'break_end' => '2026-08-06 13:00:00',
        ]);

        $request = AttendanceCorrectionRequest::create([
            'attendance_id' => $attendance->id,
            'requested_clock_in' => '09:10',
            'requested_clock_out' => '18:00',
            'note' => '承認テスト',
            'status' => 'pending',
        ]);

        AttendanceCorrectionRequestBreak::create([
            'attendance_correction_request_id' => $request->id,
            'break_start' => '12:00',
            'break_end' => '12:30',
        ]);

        AttendanceCorrectionRequestBreak::create([
            'attendance_correction_request_id' => $request->id,
            'break_start' => '15:00',
            'break_end' => '15:10',
        ]);

        $response = $this->actingAs($admin)
            ->post("/admin/request/approve/{$request->id}");

        $response->assertStatus(302);

        // 申請が承認済みになっている
        $this->assertDatabaseHas(
            'attendance_correction_requests',
            [
                'id' => $request->id,
                'status' => 'approved',
            ]
        );

        // 出勤・退勤が申請内容に更新されている
        $this->assertDatabaseHas(
            'attendances',
            [
                'id' => $attendance->id,
                'clock_in' => '2026-08-06 09:10:00',
                'clock_out' => '2026-08-06 18:00:00',
            ]
        );

        // 休憩1が反映されている
        $this->assertDatabaseHas(
            'break_times',
            [
                'attendance_id' => $attendance->id,
                'break_start' => '2026-08-06 12:00:00',
                'break_end' => '2026-08-06 12:30:00',
            ]
        );

        // 休憩2が反映されている
        $this->assertDatabaseHas(
            'break_times',
            [
                'attendance_id' => $attendance->id,
                'break_start' => '2026-08-06 15:00:00',
                'break_end' => '2026-08-06 15:10:00',
            ]
        );

        $this->assertDatabaseMissing(
            'break_times',
            [
                'attendance_id' => $attendance->id,
                'break_start' => '2026-08-06 12:00:00',
                'break_end' => '2026-08-06 13:00:00',
            ]
        );

        
        $this->assertEquals(
            2,
            BreakTime::where('attendance_id', $attendance->id)->count()
        );
    }
}