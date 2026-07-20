<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceCorrectionRequest;

class RequestController extends Controller
{
    public function index()
    {
        $requests = AttendanceCorrectionRequest::whereHas('attendance', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->latest()
        ->get();

        return view('request.list', compact('requests'));
    }
}