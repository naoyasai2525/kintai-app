<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAttendanceDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function attendance_detail_is_displayed_correctly()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'テスト太郎',
        ]);

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(9, 0),
            'clock_out' => today()->setTime(18, 0),
            'note' => '通常勤務',
        ]);

        $attendance->breakTimes()->create([
            'break_start' => today()->setTime(12, 0),
            'break_end' => today()->setTime(13, 0),
        ]);

        $response = $this->actingAs($admin)
            ->get("/admin/attendance/detail/{$attendance->id}");

        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('12:00');
        $response->assertSee('13:00');
        $response->assertSee('通常勤務');
    }

    /** @test */
    public function admin_can_update_attendance_directly()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(9, 0),
            'clock_out' => today()->setTime(18, 0),
            'note' => '修正前',
        ]);

        $response = $this->actingAs($admin)->post(
            "/admin/attendance/detail/{$attendance->id}",
            [
                'clock_in' => '08:30',
                'clock_out' => '17:30',
                'note' => '管理者修正',
            ]
        );

        $response->assertRedirect(
            "/admin/attendance/detail/{$attendance->id}"
        );

        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'clock_in' => today()->format('Y-m-d') . ' 08:30:00',
            'clock_out' => today()->format('Y-m-d') . ' 17:30:00',
            'note' => '管理者修正',
        ]);
    }

    /** @test */
    public function admin_can_update_break_time()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(9, 0),
            'clock_out' => today()->setTime(18, 0),
            'note' => '修正前',
        ]);

        $break = $attendance->breakTimes()->create([
            'break_start' => today()->setTime(12, 0),
            'break_end' => today()->setTime(13, 0),
        ]);

        $response = $this->actingAs($admin)->post(
            "/admin/attendance/detail/{$attendance->id}",
            [
                'clock_in' => '09:00',
                'clock_out' => '18:00',

                'breaks' => [
                    [
                        'id' => $break->id,
                        'break_start' => '12:30',
                        'break_end' => '13:30',
                    ],
                ],

                'note' => '休憩修正',
            ]
        );

        $response->assertRedirect(
            "/admin/attendance/detail/{$attendance->id}"
        );

        $this->assertDatabaseHas('break_times', [
            'id' => $break->id,
            'attendance_id' => $attendance->id,
            'break_start' => today()->format('Y-m-d') . ' 12:30:00',
            'break_end' => today()->format('Y-m-d') . ' 13:30:00',
        ]);
    }

    /** @test */
    public function clock_out_must_be_after_clock_in()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(9, 0),
            'clock_out' => today()->setTime(18, 0),
        ]);

        $response = $this->actingAs($admin)->post(
            "/admin/attendance/detail/{$attendance->id}",
            [
                'clock_in' => '19:00',
                'clock_out' => '18:00',
                'note' => 'テスト',
            ]
        );

        $response->assertSessionHasErrors([
            'clock_out' =>
                '出勤時間もしくは退勤時間が不適切な値です',
        ]);
    }

    /** @test */
    public function break_start_cannot_be_outside_working_hours()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(9, 0),
            'clock_out' => today()->setTime(18, 0),
        ]);

        $response = $this->actingAs($admin)->post(
            "/admin/attendance/detail/{$attendance->id}",
            [
                'clock_in' => '09:00',
                'clock_out' => '18:00',

                'breaks' => [
                    [
                        'break_start' => '08:00',
                        'break_end' => '10:00',
                    ],
                ],

                'note' => 'テスト',
            ]
        );

        $response->assertSessionHasErrors([
            'breaks.0.break_start' =>
                '休憩時間が不適切な値です',
        ]);
    }

    /** @test */
    public function break_end_cannot_be_after_clock_out()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(9, 0),
            'clock_out' => today()->setTime(18, 0),
        ]);

        $response = $this->actingAs($admin)->post(
            "/admin/attendance/detail/{$attendance->id}",
            [
                'clock_in' => '09:00',
                'clock_out' => '18:00',

                'breaks' => [
                    [
                        'break_start' => '17:00',
                        'break_end' => '19:00',
                    ],
                ],

                'note' => 'テスト',
            ]
        );

        $response->assertSessionHasErrors([
            'breaks.0.break_end' =>
                '休憩時間もしくは退勤時間が不適切な値です',
        ]);
    }

    /** @test */
    public function note_is_required()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(9, 0),
            'clock_out' => today()->setTime(18, 0),
        ]);

        $response = $this->actingAs($admin)->post(
            "/admin/attendance/detail/{$attendance->id}",
            [
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'note' => '',
            ]
        );

        $response->assertSessionHasErrors([
            'note' => '備考を記入してください',
        ]);
    }
}