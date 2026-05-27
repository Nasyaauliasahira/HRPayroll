<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Employee extends Model
{
    protected $fillable = [
        'employee_code', 'name', 'email', 'phone', 'nik', 'birth_date', 'gender',
        'address', 'photo', 'join_date', 'employment_type', 'department_id',
        'position_id', 'base_salary', 'status',
    ];

    protected $casts = [
        'join_date'   => 'date',
        'birth_date'  => 'date',
        'base_salary' => 'float',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function department()     { return $this->belongsTo(Department::class); }
    public function position()       { return $this->belongsTo(Position::class); }
    public function user()           { return $this->hasOne(User::class); }
    public function attendances()    { return $this->hasMany(Attendance::class); }
    public function payrolls()       { return $this->hasMany(Payroll::class); }
    public function leaveRequests()  { return $this->hasMany(LeaveRequest::class); }
    public function salaryComponents() { return $this->hasMany(SalaryComponent::class); }

    // ─── Accessors ────────────────────────────────────────────────────────────

    /** Two-letter initials from name, e.g. "John Doe" → "JD" */
    public function getInitialsAttribute(): string
    {
        $parts = explode(' ', trim($this->name));
        $first = mb_strtoupper(mb_substr($parts[0], 0, 1));
        $last  = isset($parts[1]) ? mb_strtoupper(mb_substr($parts[1], 0, 1)) : '';
        return $first . $last;
    }

    /** Dot color for status badge */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active'     => '#2D8F5E',
            'inactive'   => '#D97706',
            'terminated' => '#E8433A',
            default      => '#aaa',
        };
    }

    /** Background color for status badge pill */
    public function getStatusBgAttribute(): string
    {
        return match ($this->status) {
            'active'     => '#EAF6F0',
            'inactive'   => '#FEF3E2',
            'terminated' => '#FEE9E8',
            default      => '#F1EFE8',
        };
    }

    /** Text color for status badge pill */
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'active'     => '#1B7A4E',
            'inactive'   => '#8A5A0A',
            'terminated' => '#A8150E',
            default      => '#5F5E5A',
        };
    }

    /** Attendance rate (%) for current month */
    public function getAttendanceRateAttribute(): int
    {
        $now          = now();
        $daysInMonth  = $now->daysInMonth;
        $present      = $this->attendances()
                             ->whereMonth('date', $now->month)
                             ->whereYear('date', $now->year)
                             ->whereIn('status', ['present', 'late', 'leave'])
                             ->count();
        return $daysInMonth > 0 ? (int) round($present / $daysInMonth * 100) : 0;
    }

    public function getFullPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/employees/' . $this->photo) : null;
    }

    public function getTotalSalaryAttribute(): float
    {
        $allowances = $this->salaryComponents()->where('type', 'allowance')->where('is_recurring', true)->sum('amount');
        return $this->base_salary + $allowances;
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }
}