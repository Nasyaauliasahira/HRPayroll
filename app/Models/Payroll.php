<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id', 'period_month', 'period_year', 'base_salary', 'transport_allowance', 'meal_allowance', 'position_allowance', 'other_allowance', 'gross_salary', 'late_deduction', 'absence_deduction', 'tax_deduction', 'bpjs_deduction', 'other_deduction', 'total_deductions', 'overtime_pay', 'bonus', 'net_salary', 'status', 'generated_at', 'paid_at', 'notes',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Methods (stubs)
    public function calculate() {}
    public function generatePdf() {}
    public function finalize() {}
}
