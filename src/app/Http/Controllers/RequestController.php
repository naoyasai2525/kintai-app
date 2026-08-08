<?php

namespace App\Http\Controllers;

use App\Models\AttendanceCorrectionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->whereHas('attendance', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('status', $status)
            ->latest()
            ->get();

        return view('request.list', compact(
            'requests',
            'status'
        ));
    }

    public function detail(AttendanceCorrectionRequest $request)
    {
        // 他ユーザーの申請詳細を見られないようにする
        if ($request->attendance->user_id !== Auth::id()) {
            abort(403);
        }

        $request->load([
            'attendance.user',
            'attendance.breakTimes',
            'requestBreaks',
        ]);

        return view('request.detail', compact('request'));
    }
}