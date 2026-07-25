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
    $request->load([
        'attendance.user',
        'attendance.breakTimes',
    ]);

    return view('admin.request-detail', compact('request'));
}

}
