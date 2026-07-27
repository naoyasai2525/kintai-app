<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminAttendanceRequest;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $currentDate = Carbon::parse(
            $request->date ?? today()
        );

        $attendances = Attendance::with([
            'user',
            'breakTimes',
        ])
            ->whereDate('work_date', $currentDate)
            ->orderBy('clock_in')
            ->get();

        return view(
            'admin.attendance-list',
            compact(
                'attendances',
                'currentDate'
            )
        );
    }

    public function detail(Attendance $attendance)
    {
        $attendance->load([
            'user',
            'breakTimes',
        ]);

        return view(
            'admin.attendance-detail',
            compact('attendance')
        );
    }

    public function update(
        AdminAttendanceRequest $request,
        Attendance $attendance
    ) {
        DB::transaction(function () use ($request, $attendance) {

            $workDate = Carbon::parse(
                $attendance->work_date
            )->format('Y-m-d');

            $attendance->update([
                'clock_in' => $workDate . ' ' . $request->clock_in,
                'clock_out' => $workDate . ' ' . $request->clock_out,
                'note' => $request->note,
            ]);

            foreach ($request->input('breaks', []) as $breakData) {

                $breakId = $breakData['id'] ?? null;

                $breakStart = $breakData['break_start'] ?? null;

                $breakEnd = $breakData['break_end'] ?? null;

                if (!$breakStart && !$breakEnd) {
                    continue;
                }

                if ($breakId) {

                    $breakTime = $attendance->breakTimes()
                        ->where('id', $breakId)
                        ->first();

                    if ($breakTime) {
                        $breakTime->update([
                            'break_start' => $workDate . ' ' . $breakStart,
                            'break_end' => $workDate . ' ' . $breakEnd,
                        ]);
                    }

                } else {

                    $attendance->breakTimes()->create([
                        'break_start' => $workDate . ' ' . $breakStart,
                        'break_end' => $workDate . ' ' . $breakEnd,
                    ]);

                }
            }
        });

        return redirect()
            ->route('admin.attendance.detail', $attendance)
            ->with(
                'success',
                '勤怠を修正しました。'
            );
    }
}