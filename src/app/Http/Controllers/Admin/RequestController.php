<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCorrectionRequest;
use Illuminate\Http\Request;

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
            'attendance.breakTimes',
        ]);

        return view('admin.request-detail', compact('request'));
    }

    public function approve(AttendanceCorrectionRequest $request)
    {
        $request->attendance->update([
            'clock_in' => $request->attendance->work_date . ' ' . $request->requested_clock_in,
            'clock_out' => $request->attendance->work_date . ' ' . $request->requested_clock_out,
        ]);

        $request->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('admin.request.detail', $request)
            ->with('success', '申請を承認しました。');
    }
}