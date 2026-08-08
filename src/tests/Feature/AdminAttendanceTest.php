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
            'is_admin' => true,
        ]);

        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(9, 0),
            'clock_out' => today()->setTime(18, 0),
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/attendance/list');

        $response->assertStatus(200);
    }

    /** @test */
    public function current_date_is_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/attendance/list');

        $response->assertStatus(200);

        $response->assertSee(
            now()->format('Y/m/d')
        );
    }

    /** @test */
    public function previous_day_can_be_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $previousDay = now()->subDay();

        $response = $this->actingAs($admin)
            ->get(
                '/admin/attendance/list?date=' .
                $previousDay->toDateString()
            );

        $response->assertStatus(200);

        $response->assertSee(
            $previousDay->format('Y/m/d')
        );
    }

    /** @test */
    public function next_day_can_be_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $nextDay = now()->addDay();

        $response = $this->actingAs($admin)
            ->get(
                '/admin/attendance/list?date=' .
                $nextDay->toDateString()
            );

        $response->assertStatus(200);

        $response->assertSee(
            $nextDay->format('Y/m/d')
        );
    }

    /** @test */
    public function attendance_detail_page_can_be_opened()
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

        $response = $this->actingAs($admin)
            ->get("/admin/attendance/detail/{$attendance->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function all_users_attendances_for_the_day_are_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user1 = User::factory()->create([
            'name' => 'テスト太郎',
        ]);

        $user2 = User::factory()->create([
            'name' => 'テスト花子',
        ]);

        $attendance1 = Attendance::create([
            'user_id' => $user1->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(9, 0),
            'clock_out' => today()->setTime(18, 0),
        ]);

        $attendance1->breakTimes()->create([
            'break_start' => today()->setTime(12, 0),
            'break_end' => today()->setTime(13, 0),
        ]);

        $attendance2 = Attendance::create([
            'user_id' => $user2->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(10, 0),
            'clock_out' => today()->setTime(19, 0),
        ]);

        $attendance2->breakTimes()->create([
            'break_start' => today()->setTime(14, 0),
            'break_end' => today()->setTime(15, 0),
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/attendance/list');

        $response->assertStatus(200);

        $response->assertSee('テスト太郎');
        $response->assertSee('テスト花子');

        $response->assertSee('09:00');
        $response->assertSee('18:00');

        $response->assertSee('10:00');
        $response->assertSee('19:00');

        $response->assertSee('1:00');
        $response->assertSee('8:00');
    }
}