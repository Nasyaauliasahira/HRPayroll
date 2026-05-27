<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['employee.department']);

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        if ($request->filled('department')) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $request->department));
        }

        $attendances = $query->latest('date')->paginate(30)->withQueryString();
        $departments = Department::all();

        return view('attendance.index', compact('attendances', 'departments'));
    }

    public function create()
    {
        $employees = Employee::active()->with('department')->get();
        return view('attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date'        => 'required|date',
            'check_in'    => 'nullable|date_format:H:i',
            'check_out'   => 'nullable|date_format:H:i',
            'status'      => 'required|in:present,absent,late,leave,holiday',
            'notes'       => 'nullable|string',
        ]);

        // Combine date + time if provided
        if (!empty($data['check_in'])) {
            $data['check_in'] = $data['date'] . ' ' . $data['check_in'] . ':00';
        }
        if (!empty($data['check_out'])) {
            $data['check_out'] = $data['date'] . ' ' . $data['check_out'] . ':00';
        }

        if (!empty($data['check_in']) && !empty($data['check_out'])) {
            $data['work_hours'] = round((strtotime($data['check_out']) - strtotime($data['check_in'])) / 3600, 2);
        }

        if (!empty($data['check_in'])) {
            $scheduled         = strtotime($data['date'] . ' 09:00:00');
            $actual            = strtotime($data['check_in']);
            $data['late_minutes'] = max(0, round(($actual - $scheduled) / 60));
        } else {
            $data['late_minutes'] = 0;
        }

        $data['overtime_minutes'] = (!empty($data['work_hours']) && $data['work_hours'] > 8)
            ? (int)(($data['work_hours'] - 8) * 60) : 0;

        Attendance::create($data);

        return redirect()->route('attendance.index')->with('success', 'Attendance recorded.');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('employee.department');
        return view('attendance.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $employees = Employee::active()->with('department')->get();
        return view('attendance.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date'        => 'required|date',
            'check_in'    => 'nullable|date_format:H:i',
            'check_out'   => 'nullable|date_format:H:i',
            'status'      => 'required|in:present,absent,late,leave,holiday',
            'notes'       => 'nullable|string',
        ]);

        if (!empty($data['check_in'])) {
            $data['check_in'] = $data['date'] . ' ' . $data['check_in'] . ':00';
        }
        if (!empty($data['check_out'])) {
            $data['check_out'] = $data['date'] . ' ' . $data['check_out'] . ':00';
        }

        if (!empty($data['check_in']) && !empty($data['check_out'])) {
            $data['work_hours'] = round((strtotime($data['check_out']) - strtotime($data['check_in'])) / 3600, 2);
        }

        if (!empty($data['check_in'])) {
            $scheduled            = strtotime($data['date'] . ' 09:00:00');
            $actual               = strtotime($data['check_in']);
            $data['late_minutes'] = max(0, round(($actual - $scheduled) / 60));
        }

        $data['overtime_minutes'] = (!empty($data['work_hours']) && $data['work_hours'] > 8)
            ? (int)(($data['work_hours'] - 8) * 60) : 0;

        $attendance->update($data);

        return redirect()->route('attendance.index')->with('success', 'Attendance updated.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendance.index')->with('success', 'Record deleted.');
    }
}