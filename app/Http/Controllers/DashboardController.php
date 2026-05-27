<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Attendance;
use App\Models\LeaveRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        $employeeCount = Employee::count();
        $newEmployees  = Employee::whereMonth('created_at', $now->month)
                                 ->whereYear('created_at', $now->year)
                                 ->count();

        $payrollCount = Payroll::whereMonth('period_month', $now->month)
                                ->whereYear('period_year', $now->year)
                                ->count();
        $newPayrolls  = $payrollCount;

        // Attendance rate this month
        $totalRecords    = Attendance::whereMonth('date', $now->month)->whereYear('date', $now->year)->count();
        $presentRecords  = Attendance::whereMonth('date', $now->month)->whereYear('date', $now->year)
                                      ->whereIn('status', ['present', 'late'])->count();
        $attendanceRate  = $totalRecords > 0 ? round($presentRecords / $totalRecords * 100) : 0;
        $lateCount       = Attendance::whereMonth('date', $now->month)->whereYear('date', $now->year)
                                      ->where('status', 'late')->count();

        $leaveCount    = LeaveRequest::count();
        $pendingLeaves = LeaveRequest::where('status', 'pending')->count();

        // Recent activity feed (last 10 events merged)
        $activities = collect();

        Employee::latest()->take(5)->get()->each(function ($e) use (&$activities) {
            $activities->push([
                'user' => $e->name,
                'desc' => 'was added as a new employee',
                'time' => $e->created_at->diffForHumans(),
                'dot'  => '#185FA5',
            ]);
        });

        LeaveRequest::with('employee')->latest()->take(5)->get()->each(function ($lr) use (&$activities) {
            $activities->push([
                'user' => $lr->employee->name,
                'desc' => 'submitted a leave request (' . $lr->type . ')',
                'time' => $lr->created_at->diffForHumans(),
                'dot'  => '#D97706',
            ]);
        });

        Payroll::with('employee')->latest()->take(5)->get()->each(function ($p) use (&$activities) {
            $activities->push([
                'user' => $p->employee->name,
                'desc' => 'payroll generated for ' . \DateTime::createFromFormat('!m', $p->period_month)->format('F') . ' ' . $p->period_year,
                'time' => $p->created_at->diffForHumans(),
                'dot'  => '#2D8F5E',
            ]);
        });

        $activities = $activities->sortByDesc('time')->take(10)->values();

        // Payroll breakdown
        $latestPayrolls = Payroll::whereMonth('period_month', $now->month)
                                  ->whereYear('period_year', $now->year)
                                  ->get();

        $totalNet      = $latestPayrolls->sum('net_salary');
        $totalBase     = $latestPayrolls->sum('base_salary');
        $totalBonus    = $latestPayrolls->sum('bonus');
        $totalDeduct   = $latestPayrolls->sum('total_deductions');

        $fmt = fn($v) => 'Rp ' . number_format($v, 0, ',', '.');
        $pct = fn($v) => $totalNet > 0 ? min(100, round($v / $totalNet * 100)) : 0;

        $payrollBreakdown = [
            ['label' => 'Base Salary',   'value' => $fmt($totalBase),   'percent' => $pct($totalBase),   'color' => '#185FA5'],
            ['label' => 'Bonus',         'value' => $fmt($totalBonus),  'percent' => $pct($totalBonus),  'color' => '#2D8F5E'],
            ['label' => 'Deductions',    'value' => $fmt($totalDeduct), 'percent' => $pct($totalDeduct), 'color' => '#E8433A'],
            ['label' => 'Net Payout',    'value' => $fmt($totalNet),    'percent' => 100,                'color' => '#1a1a1a'],
        ];

        return view('dashboard', compact(
            'employeeCount', 'newEmployees',
            'payrollCount', 'newPayrolls',
            'attendanceRate', 'lateCount',
            'leaveCount', 'pendingLeaves',
            'activities', 'payrollBreakdown'
        ));
    }
}
