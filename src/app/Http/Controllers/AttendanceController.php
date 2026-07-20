<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceCorrectionRequest;
use App\Models\Attendance;
use App\Models\AttendanceCorrectionRequest as AttendanceCorrection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('work_date', today())
            ->first();

        $status = '勤務外';

        if ($attendance) {

            $lastBreak = $attendance->breakTimes()
                ->latest()
                ->first();

            if ($attendance->clock_out) {

                $status = '退勤済';

            } elseif (
                $lastBreak &&
                $lastBreak->break_start &&
                !$lastBreak->break_end
            ) {

                $status = '休憩中';

            } else {

                $status = '出勤中';

            }
        }

        return view('attendance.index', compact(
            'attendance',
            'status'
        ));
    }

    public function clockIn()
    {
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('work_date', today())
            ->first();

        if (!$todayAttendance) {

            Attendance::create([
                'user_id'   => Auth::id(),
                'work_date' => today(),
                'clock_in'  => now(),
            ]);

        }

        return redirect()->back();
    }

    public function clockOut()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('work_date', today())
            ->first();

        if ($attendance && !$attendance->clock_out) {

            $attendance->update([
                'clock_out' => now(),
            ]);

        }

        return redirect()->back();
    }

    public function breakIn()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('work_date', today())
            ->first();

        if ($attendance) {

            $attendance->breakTimes()->create([
                'break_start' => now(),
            ]);

        }

        return redirect()->back();
    }

    public function breakOut()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('work_date', today())
            ->first();

        if ($attendance) {

            $break = $attendance->breakTimes()
                ->whereNull('break_end')
                ->latest()
                ->first();

            if ($break) {

                $break->update([
                    'break_end' => now(),
                ]);

            }

        }

        return redirect()->back();
    }

    public function list(Request $request)
    {
        $currentMonth = Carbon::parse(
            $request->month ?? now()
        );

        $attendances = Attendance::where('user_id', Auth::id())
            ->whereYear('work_date', $currentMonth->year)
            ->whereMonth('work_date', $currentMonth->month)
            ->orderBy('work_date')
            ->get();

        return view('attendance.list', compact(
            'attendances',
            'currentMonth'
        ));
    }

    public function detail(Attendance $attendance)
    {
        if ($attendance->user_id !== Auth::id()) {
            abort(403);
        }

        return view(
            'attendance.detail',
            compact('attendance')
        );
    }

    public function update(
        AttendanceCorrectionRequest $request,
        Attendance $attendance
    )
    {
        AttendanceCorrection::create([
            'attendance_id' => $attendance->id,
            'requested_clock_in' => $request->clock_in,
            'requested_clock_out' => $request->clock_out,
            'note' => $request->note,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('attendance.detail', $attendance)
            ->with('success', '修正申請を送信しました');
    }
}