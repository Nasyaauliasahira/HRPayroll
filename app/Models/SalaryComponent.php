<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    protected $fillable = [
        'employee_id', 'type', 'name', 'amount', 'is_recurring', 'effective_date', 'end_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
