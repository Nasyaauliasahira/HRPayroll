<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('department')->withCount('employees')->get();
        return view('positions.index', compact('positions'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('positions.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'level'         => 'nullable|string|max:50',
            'description'   => 'nullable|string',
        ]);

        Position::create($data);
        return redirect()->route('positions.index')->with('success', 'Position created.');
    }

    public function show(Position $position)
    {
        $position->load(['department', 'employees']);
        return view('positions.show', compact('position'));
    }

    public function edit(Position $position)
    {
        $departments = Department::all();
        return view('positions.edit', compact('position', 'departments'));
    }

    public function update(Request $request, Position $position)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'level'         => 'nullable|string|max:50',
            'description'   => 'nullable|string',
        ]);

        $position->update($data);
        return redirect()->route('positions.index')->with('success', 'Position updated.');
    }

    public function destroy(Position $position)
    {
        if ($position->employees()->exists()) {
            return back()->with('error', 'Cannot delete a position that has employees.');
        }
        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Position deleted.');
    }
}