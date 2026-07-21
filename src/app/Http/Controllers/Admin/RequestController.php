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
    return view('admin.request-detail', compact('request'));
    }
}