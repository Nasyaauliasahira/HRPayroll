<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('employee_code', 'like', "%$q%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        $employees   = $query->latest()->paginate(30)->withQueryString();
        $departments = Department::all();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        $positions   = Position::all();
        return view('employees.create', compact('departments', 'positions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_code'   => 'required|string|unique:employees',
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:employees',
            'phone'           => 'nullable|string|max:20',
            'nik'             => 'nullable|string|max:20',
            'birth_date'      => 'nullable|date',
            'gender'          => 'nullable|in:male,female',
            'address'         => 'nullable|string',
            'join_date'       => 'required|date',
            'employment_type' => 'required|in:permanent,contract,internship,intern,fulltime,parttime',
            'department_id'   => 'required|exists:departments,id',
            'position_id'     => 'required|exists:positions,id',
            'base_salary'     => 'required|numeric|min:0',
            'status'          => 'required|in:active,inactive,terminated',
        ]);

        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['department', 'position', 'attendances' => function ($q) {
            $q->latest('date')->take(30);
        }, 'payrolls' => function ($q) {
            $q->latest()->take(12);
        }]);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        $positions   = Position::all();
        return view('employees.edit', compact('employee', 'departments', 'positions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'employee_code'   => 'required|string|unique:employees,employee_code,' . $employee->id,
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:employees,email,' . $employee->id,
            'phone'           => 'nullable|string|max:20',
            'nik'             => 'nullable|string|max:20',
            'birth_date'      => 'nullable|date',
            'gender'          => 'nullable|in:male,female',
            'address'         => 'nullable|string',
            'join_date'       => 'required|date',
            'employment_type' => 'required|in:permanent,contract,internship,intern,fulltime,parttime',
            'department_id'   => 'required|exists:departments,id',
            'position_id'     => 'required|exists:positions,id',
            'base_salary'     => 'required|numeric|min:0',
            'status'          => 'required|in:active,inactive,terminated',
        ]);

        $employee->update($data);

        return redirect()->route('employees.show', $employee)->with('success', 'Employee updated.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted.');
    }
}