<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCorrectionRequest;
use App\Models\BreakTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        if (!in_array($status, ['pending', 'approved'], true)) {
            $status = 'pending';
        }

        $requests = AttendanceCorrectionRequest::with([
            'attendance.user',
        ])
            ->where('status', $status)
            ->latest()
            ->get();

        return view('admin.request-list', compact(
            'requests',
            'status'
        ));
    }

    public function detail(AttendanceCorrectionRequest $request)
    {
        $request->load([
            'attendance.user',
            'requestBreaks',
        ]);

        return view('admin.request-detail', compact('request'));
    }

    public function approve(AttendanceCorrectionRequest $request)
    {
        if ($request->status === 'approved') {
            return redirect()
                ->route('admin.request.detail', $request)
                ->with('success', 'この申請は承認済みです。');
        }

        $request->load([
            'attendance',
            'requestBreaks',
        ]);

        DB::transaction(function () use ($request) {

            $attendance = $request->attendance;
            $workDate = $attendance->work_date;

            // 出勤・退勤を申請内容に更新
            $attendance->update([
                'clock_in' => $workDate . ' ' . $request->requested_clock_in,
                'clock_out' => $workDate . ' ' . $request->requested_clock_out,
            ]);

            // 元の休憩データを削除
            $attendance->breakTimes()->delete();

            // 申請された休憩データを登録
            foreach ($request->requestBreaks as $requestBreak) {

                BreakTime::create([
                    'attendance_id' => $attendance->id,
                    'break_start' => $workDate . ' ' . $requestBreak->break_start,
                    'break_end' => $requestBreak->break_end
                        ? $workDate . ' ' . $requestBreak->break_end
                        : null,
                ]);
            }

            // 申請を承認済みにする
            $request->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.request.detail', $request)
            ->with('success', '申請を承認しました。');
    }
}