<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('employees')->get();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:20|unique:departments',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        Department::create($data);
        return redirect()->route('departments.index')->with('success', 'Department created.');
    }

    public function show(Department $department)
    {
        $department->load(['employees.position', 'positions']);
        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:20|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        $department->update($data);
        return redirect()->route('departments.index')->with('success', 'Department updated.');
    }

    public function destroy(Department $department)
    {
        if ($department->employees()->exists()) {
            return back()->with('error', 'Cannot delete a department that has employees.');
        }
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted.');
    }
}