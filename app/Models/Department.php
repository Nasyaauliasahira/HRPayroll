<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name', 'code', 'description', 'head_employee_id', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function head()
    {
        return $this->belongsTo(Employee::class, 'head_employee_id');
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    // Deterministic palette based on department id so colors are stable
    private const PALETTES = [
        ['#EAF1FB', '#185FA5'],
        ['#EAF6F0', '#1B7A4E'],
        ['#FEF3E2', '#8A5A0A'],
        ['#FEE9E8', '#A8150E'],
        ['#F0EAFB', '#5E18A5'],
        ['#FBF5EA', '#A57A18'],
        ['#EAFBF8', '#18A58A'],
    ];

    /** Background color for the initials avatar bubble */
    public function getAvatarBgAttribute(): string
    {
        return self::PALETTES[($this->id ?? 0) % count(self::PALETTES)][0];
    }

    /** Text color for the initials avatar bubble */
    public function getAvatarTextAttribute(): string
    {
        return self::PALETTES[($this->id ?? 0) % count(self::PALETTES)][1];
    }
}