<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('employee.department');

        if ($request->filled('month')) {
            $query->where('period_month', $request->month);
        }
        if ($request->filled('year')) {
            $query->where('period_year', $request->year);
        }
        if ($request->filled('employee')) {
            $query->where('employee_id', $request->employee);
        }

        $payrolls  = $query->latest()->paginate(30)->withQueryString();
        $employees = Employee::active()->get();
        $years     = Payroll::selectRaw('DISTINCT period_year')->orderByDesc('period_year')->pluck('period_year');

        return view('payroll.index', compact('payrolls', 'employees', 'years'));
    }

    public function create()
    {
        $employees = Employee::active()->with('department')->get();
        $years = collect(range(now()->year + 1, now()->year - 5))->sortDesc()->values();

        return view('payroll.create', compact('employees', 'years'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id'  => 'required|exists:employees,id',
            'period_month' => 'required|integer|min:1|max:12',
            'period_year'  => 'required|integer|min:2000',
            'bonus'        => 'nullable|numeric|min:0',
            'notes'        => 'nullable|string',
        ]);

        if (Payroll::where('employee_id', $data['employee_id'])
                   ->where('period_month', $data['period_month'])
                   ->where('period_year', $data['period_year'])->exists()) {
            return back()->with('error', 'Payroll already exists for this period.');
        }

        $this->generatePayroll(
            Employee::findOrFail($data['employee_id']),
            $data['period_month'],
            $data['period_year'],
            $data['bonus'] ?? 0,
            $data['notes'] ?? null
        );

        return redirect()->route('payroll.index')->with('success', 'Payroll generated.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee.department');
        return view('payroll.show', compact('payroll'));
    }

    public function edit(Payroll $payroll)
    {
        return view('payroll.edit', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $data = $request->validate([
            'bonus'  => 'nullable|numeric|min:0',
            'notes'  => 'nullable|string',
            'status' => 'required|in:draft,finalized,paid',
        ]);

        $payroll->update($data);

        return redirect()->route('payroll.show', $payroll)->with('success', 'Payroll updated.');
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->status !== 'draft') {
            return back()->with('error', 'Only draft payrolls can be deleted.');
        }
        $payroll->delete();
        return redirect()->route('payroll.index')->with('success', 'Payroll deleted.');
    }

    public function finalize(Payroll $payroll)
    {
        $payroll->update(['status' => 'finalized']);
        return back()->with('success', 'Payroll finalized.');
    }

    public function pay(Payroll $payroll)
    {
        $payroll->update(['status' => 'paid', 'paid_at' => now()]);
        return back()->with('success', 'Payroll marked as paid.');
    }

    public function payslip(Payroll $payroll)
    {
        $payroll->load('employee.department');
        $pdf      = Pdf::loadView('payroll.payslip', compact('payroll'));
        $filename = 'Payslip_' . $payroll->employee->name . '_' . $payroll->period_month . '_' . $payroll->period_year . '.pdf';
        return $pdf->download($filename);
    }

    public function bulk(Request $request)
    {
        $employees = Employee::active()->with('department')->get();
        $month     = $request->month ?? now()->month;
        $year      = $request->year  ?? now()->year;
        $years     = collect(range(now()->year + 1, now()->year - 5))->sortDesc()->values();

        return view('payroll.create', compact('employees', 'month', 'year', 'years'));
    }

    public function bulkGenerate(Request $request)
    {
        $data = $request->validate([
            'period_month' => 'required|integer|min:1|max:12',
            'period_year'  => 'required|integer|min:2000',
        ]);

        $employees = Employee::active()->get();
        $count     = 0;

        foreach ($employees as $employee) {
            if (!Payroll::where('employee_id', $employee->id)
                        ->where('period_month', $data['period_month'])
                        ->where('period_year', $data['period_year'])->exists()) {
                $this->generatePayroll($employee, $data['period_month'], $data['period_year']);
                $count++;
            }
        }

        return redirect()->route('payroll.index',
            ['month' => $data['period_month'], 'year' => $data['period_year']]
        )->with('success', "Bulk generated $count payroll(s).");
    }

    // ─── Private helper ──────────────────────────────────────────────────────

    private function generatePayroll(Employee $employee, int $month, int $year, float $bonus = 0, ?string $notes = null): Payroll
    {
        $attendances = $employee->attendances()
            ->whereMonth('date', $month)->whereYear('date', $year)->get();

        $working_days         = \Carbon\Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $present_days         = $attendances->whereIn('status', ['present', 'late', 'leave'])->count();
        $absent_days          = max(0, $working_days - $present_days);
        $total_late_minutes   = $attendances->sum('late_minutes');
        $total_overtime_minutes = $attendances->sum('overtime_minutes');

        $hourly_rate = $employee->base_salary / $working_days / 8;
        $daily_rate  = $employee->base_salary / $working_days;

        $late_deduction    = ($total_late_minutes / 60) * $hourly_rate;
        $absence_deduction = $absent_days * $daily_rate;
        $overtime_pay      = ($total_overtime_minutes / 60) * $hourly_rate * 1.5;
        $bpjs_deduction    = $employee->base_salary * 0.01;
        $tax_deduction     = $this->calculateTax($employee->base_salary);

        $allowances  = $employee->salaryComponents()->where('type', 'allowance')->where('is_recurring', true)->sum('amount');
        $deductions  = $employee->salaryComponents()->where('type', 'deduction')->where('is_recurring', true)->sum('amount');

        $gross_salary    = $employee->base_salary + $allowances;
        $total_deductions = $late_deduction + $absence_deduction + $bpjs_deduction + $tax_deduction + $deductions;
        $net_salary      = $gross_salary + $overtime_pay + $bonus - $total_deductions;

        return Payroll::create([
            'employee_id'       => $employee->id,
            'period_month'      => $month,
            'period_year'       => $year,
            'base_salary'       => $employee->base_salary,
            'transport_allowance' => 0,
            'meal_allowance'    => 0,
            'position_allowance' => 0,
            'other_allowance'   => $allowances,
            'gross_salary'      => $gross_salary,
            'late_deduction'    => $late_deduction,
            'absence_deduction' => $absence_deduction,
            'tax_deduction'     => $tax_deduction,
            'bpjs_deduction'    => $bpjs_deduction,
            'other_deduction'   => $deductions,
            'total_deductions'  => $total_deductions,
            'overtime_pay'      => $overtime_pay,
            'bonus'             => $bonus,
            'net_salary'        => $net_salary,
            'status'            => 'draft',
            'generated_at'      => now(),
            'notes'             => $notes,
        ]);
    }

    private function calculateTax(float $annualSalary): float
    {
        $annual = $annualSalary * 12;
        if ($annual <= 60_000_000)      return 0;
        if ($annual <= 250_000_000)     return ($annual - 60_000_000) * 0.05 / 12;
        if ($annual <= 500_000_000)     return (($annual - 250_000_000) * 0.15 + 9_500_000) / 12;
        return (($annual - 500_000_000) * 0.25 + 47_000_000) / 12;
    }
}