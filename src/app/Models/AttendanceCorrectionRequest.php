<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceCorrectionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'requested_clock_in',
        'requested_clock_out',
        'note',
        'status',
        'approved_at',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}