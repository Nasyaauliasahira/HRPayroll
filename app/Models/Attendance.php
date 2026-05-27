<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id', 'date', 'check_in', 'check_out',
        'work_hours', 'late_minutes', 'overtime_minutes', 'status', 'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getWorkHoursAttribute($value)
    {
        if ($value !== null) return $value;
        if ($this->check_in && $this->check_out) {
            return round((strtotime($this->check_out) - strtotime($this->check_in)) / 3600, 2);
        }
        return null;
    }

    public function getLateMinutesAttribute($value)
    {
        if ($value !== null) return $value;
        if ($this->check_in) {
            $scheduled = strtotime($this->getRawOriginal('date') . ' 09:00:00');
            $actual    = strtotime($this->check_in);
            return max(0, round(($actual - $scheduled) / 60));
        }
        return 0;
    }

    /** Badge background color */
    public function getStatusBgAttribute(): string
    {
        return match ($this->status) {
            'present' => '#EAF6F0',
            'late'    => '#FEF3E2',
            'absent'  => '#FEE9E8',
            'leave'   => '#EAF1FB',
            'holiday' => '#F1EFE8',
            default   => '#F5F4F1',
        };
    }

    /** Badge text color */
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'present' => '#1B7A4E',
            'late'    => '#8A5A0A',
            'absent'  => '#A8150E',
            'leave'   => '#185FA5',
            'holiday' => '#5F5E5A',
            default   => '#444',
        };
    }
}