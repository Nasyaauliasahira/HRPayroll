@extends('layouts.app')
@section('breadcrumb', 'Leave Requests')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-[18px] font-semibold text-gray-900">Leave Requests</h1>
    <a href="{{ route('leave-requests.create') }}" class="btn-action-primary">
        <x-heroicon-o-plus class="w-4 h-4" />
        <span>New Request</span>
    </a>
</div>
<form method="GET" class="mb-4 flex gap-3">
    <select name="status" class="h-[34px] px-3 border border-[#E8E6E1] rounded-lg text-[12px] bg-white">
        <option value="">All Status</option>
        <option value="pending" @selected(request('status') == 'pending')>Pending</option>
        <option value="approved" @selected(request('status') == 'approved')>Approved</option>
        <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
    </select>
    <select name="employee" class="h-[34px] px-3 border border-[#E8E6E1] rounded-lg text-[12px] bg-white">
        <option value="">All Employees</option>
        @foreach($employees as $emp)
            <option value="{{ $emp->id }}" @selected(request('employee') == $emp->id)>{{ $emp->name }}</option>
        @endforeach
    </select>
    <button class="btn-action-soft h-[34px]">Filter</button>
</form>
<div class="bg-white border border-[#E8E6E1] rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-[#E8E6E1]">
                <th class="text-left px-4 py-2.5 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Employee</th>
                <th class="text-left px-4 py-2.5 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Date Range</th>
                <th class="text-right px-4 py-2.5 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Total Days</th>
                <th class="text-left px-4 py-2.5 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Status</th>
                <th class="text-right px-4 py-2.5 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaveRequests as $leave)
            <tr class="border-b border-[#F9F8F6] hover:bg-[#FAFAF8] transition-colors">
                <td class="px-4 py-2.5">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-[6px] flex items-center justify-center font-semibold text-[9px] flex-shrink-0" style="background: {{ $leave->employee->department?->avatar_bg ?? '#EAF1FB' }}; color: {{ $leave->employee->department?->avatar_text ?? '#185FA5' }};">
                            {{ $leave->employee->initials }}
                        </div>
                        <div>
                            <div class="text-[12px] font-medium text-gray-900 leading-tight">{{ $leave->employee->name }}</div>
                            <div class="text-[10px] text-[#bbb]">{{ $leave->employee->department?->name }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-2.5">
                    <div class="text-[12px] font-medium text-gray-900 leading-tight">{{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}</div>
                    <div class="text-[10px] text-[#bbb]">{{ $leave->type ? ucfirst($leave->type) : 'Leave' }}</div>
                </td>
                <td class="px-4 py-2.5 text-right text-[12px] font-medium text-gray-900 numeric">{{ $leave->total_days }}</td>
                <td class="px-4 py-2.5">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium" style="background: {{ $leave->status === 'pending' ? '#EAF1FB' : ($leave->status === 'approved' ? '#EAF6F0' : ($leave->status === 'rejected' ? '#FEF0EE' : '#F1EFE8')) }}; color: {{ $leave->status === 'pending' ? '#185FA5' : ($leave->status === 'approved' ? '#1B7A4E' : ($leave->status === 'rejected' ? '#B0362E' : '#5F5E5A')) }};">
                        {{ ucfirst($leave->status) }}
                    </span>
                </td>
                <td class="px-4 py-2.5 text-right">
                    <div class="inline-flex items-center gap-2">
                        <a href="{{ route('leave-requests.show', $leave) }}" class="btn-action-link-blue">Detail</a>
                        @if($leave->status === 'pending')
                        <form method="POST" action="{{ route('leave-requests.approve', $leave) }}" class="inline">
                            @csrf
                            <button class="btn-action-link-green">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('leave-requests.reject', $leave) }}" class="inline">
                            @csrf
                            <button class="btn-action-link-red">Reject</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-[#aaa] text-[12px] py-8">No leave requests found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4 flex items-center justify-between">
    <p class="text-[11px] text-[#aaa]">Showing {{ $leaveRequests->firstItem() ?? 0 }}-{{ $leaveRequests->lastItem() ?? 0 }} of {{ $leaveRequests->total() }} results</p>
    {{ $leaveRequests->links() }}
</div>
@endsection
