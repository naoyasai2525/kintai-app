<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StaffController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', false)
            ->orderBy('id')
            ->get();

        return view(
            'admin.staff-list',
            compact('users')
        );
    }

    public function attendanceList(Request $request, User $user)
{
    $currentMonth = Carbon::parse(
        $request->month ?? now()
    );

    $attendances = Attendance::where('user_id', $user->id)
        ->whereYear('work_date', $currentMonth->year)
        ->whereMonth('work_date', $currentMonth->month)
        ->orderBy('work_date')
        ->get();

    return view(
        'admin.staff-attendance-list',
        compact(
            'user',
            'attendances',
            'currentMonth'
        )
    );
    }

    public function exportCsv(Request $request, User $user)
{
    $currentMonth = Carbon::parse(
        $request->month ?? now()
    );

    $attendances = Attendance::with('breakTimes')
        ->where('user_id', $user->id)
        ->whereYear('work_date', $currentMonth->year)
        ->whereMonth('work_date', $currentMonth->month)
        ->orderBy('work_date')
        ->get();

    $response = new StreamedResponse(function () use ($attendances) {

        $handle = fopen('php://output', 'w');

        fputcsv($handle, [
            '日付',
            '出勤',
            '退勤',
            '休憩',
            '合計',
        ]);

        foreach ($attendances as $attendance) {

            fputcsv($handle, [

                Carbon::parse(
                    $attendance->work_date
                )->format('Y/m/d'),

                $attendance->clock_in
                    ? Carbon::parse($attendance->clock_in)->format('H:i')
                    : '',

                $attendance->clock_out
                    ? Carbon::parse($attendance->clock_out)->format('H:i')
                    : '',

                $attendance->getBreakTime(),

                $attendance->getWorkTime(),

            ]);
        }

        fclose($handle);

    });

    $fileName = $user->name . '_' .
        $currentMonth->format('Ym') .
        '.csv';

    $response->headers->set(
        'Content-Type',
        'text/csv'
    );

    $response->headers->set(
        'Content-Disposition',
        'attachment; filename="'.$fileName.'"'
    );

    return $response;
}


}