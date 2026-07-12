<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\BreakTime;
use App\Models\AttendanceCorrectionRequest;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_date',
        'clock_in',
        'clock_out',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breakTimes()
    {
        return $this->hasMany(BreakTime::class);
    }

    public function correctionRequests()
    {
        return $this->hasMany(AttendanceCorrectionRequest::class);
    }

    public function getBreakMinutes()
    {
        return $this->breakTimes->sum(function ($break) {

            if (!$break->break_end) {
                return 0;
            }

            return Carbon::parse($break->break_start)
                ->diffInMinutes(
                    Carbon::parse($break->break_end)
                );
        });
    }

    public function getBreakTime()
    {
        $minutes = $this->getBreakMinutes();

        $hours = floor($minutes / 60);

        $minutes = $minutes % 60;

        return sprintf('%d:%02d', $hours, $minutes);
    }

    public function getWorkTime()
    {
        if (!$this->clock_in || !$this->clock_out) {
            return '';
        }

        $workMinutes = Carbon::parse($this->clock_in)
            ->diffInMinutes(
                Carbon::parse($this->clock_out)
            );

        $workMinutes -= $this->getBreakMinutes();

        $hours = floor($workMinutes / 60);

        $minutes = $workMinutes % 60;

        return sprintf('%d:%02d', $hours, $minutes);
    }
}