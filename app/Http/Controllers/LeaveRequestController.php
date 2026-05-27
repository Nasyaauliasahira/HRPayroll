<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Employee;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with('employee');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('employee')) {
            $query->where('employee_id', $request->employee);
        }

        $leaveRequests = $query->latest()->paginate(30)->withQueryString();
        $employees     = Employee::active()->get();

        return view('leave_requests.index', compact('leaveRequests', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->get();
        return view('leave_requests.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type'        => 'required|in:annual,sick,personal,unpaid,maternity,paternity',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'nullable|string',
        ]);

        $start            = \Carbon\Carbon::parse($data['start_date']);
        $end              = \Carbon\Carbon::parse($data['end_date']);
        $data['total_days'] = $start->diffInDays($end) + 1;
        $data['status']   = 'pending';

        LeaveRequest::create($data);
        return redirect()->route('leave-requests.index')->with('success', 'Leave request submitted.');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['employee', 'approver']);
        return view('leave_requests.show', compact('leaveRequest'));
    }

    public function edit(LeaveRequest $leaveRequest)
    {
        $employees = Employee::active()->get();
        return view('leave_requests.edit', compact('leaveRequest', 'employees'));
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be edited.');
        }

        $data = $request->validate([
            'type'       => 'required|in:annual,sick,personal,unpaid,maternity,paternity',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'nullable|string',
        ]);

        $start             = \Carbon\Carbon::parse($data['start_date']);
        $end               = \Carbon\Carbon::parse($data['end_date']);
        $data['total_days'] = $start->diffInDays($end) + 1;

        $leaveRequest->update($data);
        return redirect()->route('leave-requests.show', $leaveRequest)->with('success', 'Request updated.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();
        return redirect()->route('leave-requests.index')->with('success', 'Request deleted.');
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status'      => 'approved',
            'approver_id' => auth()->id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'Leave request approved.');
    }

    public function reject(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status'      => 'rejected',
            'approver_id' => auth()->id(),
            'rejected_at' => now(),
        ]);
        return back()->with('success', 'Leave request rejected.');
    }
}