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
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => '田中太郎',
            'email' => 'tanaka@example.com',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/staff/list');

        $response->assertStatus(200);
    }

    /** @test */
    public function all_staff_are_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => '田中太郎',
            'email' => 'tanaka@example.com',
            'is_admin' => false,
        ]);

        User::factory()->create([
            'name' => '山田花子',
            'email' => 'yamada@example.com',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/staff/list');

        $response->assertStatus(200);

        $response->assertSee('田中太郎');
        $response->assertSee('tanaka@example.com');

        $response->assertSee('山田花子');
        $response->assertSee('yamada@example.com');
    }

    /** @test */
    public function admin_is_not_displayed_in_staff_list()
    {
        $admin = User::factory()->create([
            'name' => '管理者テスト',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => '一般スタッフ',
            'email' => 'staff@example.com',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/staff/list');

        $response->assertStatus(200);

        $response->assertSee('一般スタッフ');
        $response->assertSee('staff@example.com');

        $response->assertDontSee('管理者テスト');
        $response->assertDontSee('admin@example.com');
    }

    /** @test */
    public function admin_can_view_staff_attendance_list()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'name' => '田中太郎',
            'is_admin' => false,
        ]);

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(9, 0),
            'clock_out' => today()->setTime(18, 0),
        ]);

        $response = $this->actingAs($admin)
            ->get("/admin/staff/attendance/list/{$user->id}");

        $response->assertStatus(200);

        $response->assertSee('田中太郎');
        $response->assertSee(today()->format('Y/m'));
        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }

    /** @test */
    public function previous_month_can_be_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $previousMonth = now()->subMonth();

        $response = $this->actingAs($admin)
            ->get(
                "/admin/staff/attendance/list/{$user->id}?month=" .
                $previousMonth->format('Y-m')
            );

        $response->assertStatus(200);

        $response->assertSee(
            $previousMonth->format('Y/m')
        );
    }

    /** @test */
    public function next_month_can_be_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $nextMonth = now()->addMonth();

        $response = $this->actingAs($admin)
            ->get(
                "/admin/staff/attendance/list/{$user->id}?month=" .
                $nextMonth->format('Y-m')
            );

        $response->assertStatus(200);

        $response->assertSee(
            $nextMonth->format('Y/m')
        );
    }

    /** @test */
    public function only_selected_month_attendances_are_displayed()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $currentDate = now()
            ->startOfMonth()
            ->addDays(4);

        $previousDate = now()
            ->subMonth()
            ->startOfMonth()
            ->addDays(9);

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => $currentDate->toDateString(),
            'clock_in' => $currentDate->copy()->setTime(9, 0),
            'clock_out' => $currentDate->copy()->setTime(18, 0),
        ]);

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => $previousDate->toDateString(),
            'clock_in' => $previousDate->copy()->setTime(10, 0),
            'clock_out' => $previousDate->copy()->setTime(17, 0),
        ]);

        $response = $this->actingAs($admin)
            ->get(
                "/admin/staff/attendance/list/{$user->id}?month=" .
                $currentDate->format('Y-m')
            );

        $response->assertStatus(200);

        $response->assertSee(
            $currentDate->format('m/d')
        );

        $response->assertSee('09:00');
        $response->assertSee('18:00');

        $response->assertDontSee(
            $previousDate->format('m/d')
        );
    }

    /** @test */
    public function attendance_detail_page_can_be_opened()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(9, 0),
            'clock_out' => today()->setTime(18, 0),
        ]);

        $response = $this->actingAs($admin)
            ->get(
                "/admin/attendance/detail/{$attendance->id}"
            );

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_export_staff_attendance_csv()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'name' => '田中太郎',
            'is_admin' => false,
        ]);

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in' => today()->setTime(9, 0),
            'clock_out' => today()->setTime(18, 0),
        ]);

        $attendance->breakTimes()->create([
            'break_start' => today()->setTime(12, 0),
            'break_end' => today()->setTime(13, 0),
        ]);

        $response = $this->actingAs($admin)
            ->get(
                "/admin/staff/attendance/csv/{$user->id}?month=" .
                now()->format('Y-m')
            );

        $response->assertStatus(200);

        $response->assertHeader(
            'Content-Type',
            'text/csv; charset=UTF-8'
        );

        $response->assertHeader(
            'Content-Disposition',
            'attachment; filename="' .
            $user->name . '_' .
            now()->format('Ym') .
            '.csv"'
        );
    }
}