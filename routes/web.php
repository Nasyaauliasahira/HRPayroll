<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\LeaveRequestController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Employees
Route::resource('employees', EmployeeController::class);

// Attendance
Route::resource('attendance', AttendanceController::class);

// Payroll
Route::resource('payroll', PayrollController::class);
Route::get('payroll/{payroll}/payslip', [PayrollController::class, 'payslip'])->name('payroll.payslip');
Route::post('payroll/{payroll}/finalize', [PayrollController::class, 'finalize'])->name('payroll.finalize');
Route::post('payroll/{payroll}/pay', [PayrollController::class, 'pay'])->name('payroll.pay');
Route::get('payroll-bulk', [PayrollController::class, 'bulk'])->name('payroll.bulk');
Route::post('payroll-bulk', [PayrollController::class, 'bulkGenerate'])->name('payroll.bulk.generate');

// Departments
Route::resource('departments', DepartmentController::class);

// Positions
Route::resource('positions', PositionController::class);

// Leave Requests
Route::resource('leave-requests', LeaveRequestController::class);
Route::post('leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
Route::post('leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');