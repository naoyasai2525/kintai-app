<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_name_is_displayed()
    {
        $user = User::factory()->create([
            'name' => 'テスト太郎',
        ]);

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now()->setTime(9, 0),
            'clock_out' => now()->setTime(18, 0),
        ]);

        $response = $this->actingAs($user)
            ->get("/attendance/detail/{$attendance->id}");

        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
    }

    /** @test */
    public function work_date_is_displayed()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now()->setTime(9, 0),
            'clock_out' => now()->setTime(18, 0),
        ]);

        $response = $this->actingAs($user)
            ->get("/attendance/detail/{$attendance->id}");

        $response->assertStatus(200);
        $response->assertSee(today()->format('Y'));
    }

    /** @test */
    public function clock_in_and_clock_out_are_displayed()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now()->setTime(9, 0),
            'clock_out' => now()->setTime(18, 0),
        ]);

        $response = $this->actingAs($user)
            ->get("/attendance/detail/{$attendance->id}");

        $response->assertStatus(200);
        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }

    /** @test */
    public function note_is_required()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now()->setTime(9, 0),
            'clock_out' => now()->setTime(18, 0),
        ]);

        $response = $this->actingAs($user)->post(
            "/attendance/detail/{$attendance->id}",
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

    /** @test */
    public function clock_in_must_be_before_clock_out()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now()->setTime(9, 0),
            'clock_out' => now()->setTime(18, 0),
        ]);

        $response = $this->actingAs($user)->post(
            "/attendance/detail/{$attendance->id}",
            [
                'clock_in' => '19:00',
                'clock_out' => '18:00',
                'note' => 'テスト',
            ]
        );

        $response->assertSessionHasErrors([
            'clock_in' =>
                '出勤時間もしくは退勤時間が不適切な値です',
        ]);
    }

    /** @test */
    public function break_start_cannot_be_before_clock_in()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now()->setTime(9, 0),
            'clock_out' => now()->setTime(18, 0),
        ]);

        $response = $this->actingAs($user)->post(
            "/attendance/detail/{$attendance->id}",
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
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now()->setTime(9, 0),
            'clock_out' => now()->setTime(18, 0),
        ]);

        $response = $this->actingAs($user)->post(
            "/attendance/detail/{$attendance->id}",
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
    public function correction_request_can_be_created()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => now()->setTime(9, 0),
            'clock_out' => now()->setTime(18, 0),
        ]);

        $response = $this->actingAs($user)->post(
            "/attendance/detail/{$attendance->id}",
            [
                'clock_in' => '09:30',
                'clock_out' => '18:30',
                'breaks' => [
                    [
                        'break_start' => '12:00',
                        'break_end' => '13:00',
                    ],
                ],
                'note' => '電車遅延のため',
            ]
        );

        $response->assertStatus(302);

        $this->assertDatabaseHas(
            'attendance_correction_requests',
            [
                'attendance_id' => $attendance->id,
                'requested_clock_in' => '09:30:00',
                'requested_clock_out' => '18:30:00',
                'note' => '電車遅延のため',
                'status' => 'pending',
            ]
        );
    }
}