@extends('layouts.app')
@section('breadcrumb', 'Leave Detail')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white border border-[#E8E6E1] rounded-xl p-6 mb-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 rounded-[10px] flex items-center justify-center font-semibold text-[12px] flex-shrink-0" style="background: {{ $leaveRequest->employee->department?->avatar_bg ?? '#EAF1FB' }}; color: {{ $leaveRequest->employee->department?->avatar_text ?? '#185FA5' }};">
                {{ $leaveRequest->employee->initials }}
            </div>
            <div>
                <div class="text-[13px] font-medium text-gray-900">{{ $leaveRequest->employee->name }}</div>
                <div class="text-[11px] text-[#999] mt-0.5">{{ $leaveRequest->employee->department?->name }}</div>
            </div>
        </div>
        <div class="flex gap-4 mb-2">
            <div>
                <div class="text-[10px] text-[#aaa] uppercase tracking-[0.4px]">Date Range</div>
                <div class="text-[12px] font-medium text-gray-900 mt-0.5">{{ $leaveRequest->start_date->format('d M Y') }} - {{ $leaveRequest->end_date->format('d M Y') }}</div>
            </div>
            <div>
                <div class="text-[10px] text-[#aaa] uppercase tracking-[0.4px]">Total Days</div>
                <div class="text-[12px] font-medium text-gray-900 mt-0.5 numeric">{{ $leaveRequest->total_days }}</div>
            </div>
            <div>
                <div class="text-[10px] text-[#aaa] uppercase tracking-[0.4px]">Status</div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium {{
                    $leaveRequest->status === 'pending' ? 'bg-[#EAF1FB] text-[#185FA5]' :
                    ($leaveRequest->status === 'approved' ? 'bg-[#EAF6F0] text-[#1B7A4E]' :
                    ($leaveRequest->status === 'rejected' ? 'bg-[#FEF0EE] text-[#B0362E]' :
                    'bg-[#F1EFE8] text-[#5F5E5A]'))
                }}">
                    {{ ucfirst($leaveRequest->status) }}
                </span>
            </div>
        </div>
        <div class="mb-2">
            <div class="text-[10px] text-[#aaa] uppercase tracking-[0.4px]">Reason</div>
            <div class="text-[12px] text-gray-900 mt-0.5">{{ $leaveRequest->reason }}</div>
        </div>
        <div class="mb-2">
            <div class="text-[10px] text-[#aaa] uppercase tracking-[0.4px]">Approver</div>
            <div class="text-[12px] text-gray-900 mt-0.5">{{ $leaveRequest->approver?->name ?? '-' }}</div>
        </div>
        <div class="mb-2">
            <div class="text-[10px] text-[#aaa] uppercase tracking-[0.4px]">Approved At</div>
            <div class="text-[12px] text-gray-900 mt-0.5">{{ $leaveRequest->approved_at?->format('d M Y H:i') ?? '-' }}</div>
        </div>
        <div class="mb-2">
            <div class="text-[10px] text-[#aaa] uppercase tracking-[0.4px]">Rejected At</div>
            <div class="text-[12px] text-gray-900 mt-0.5">{{ $leaveRequest->rejected_at?->format('d M Y H:i') ?? '-' }}</div>
        </div>
        <div class="flex justify-end gap-2 mt-4">
            @if($leaveRequest->status === 'pending')
                <form method="POST" action="{{ route('leave-requests.approve', $leaveRequest) }}">
                    @csrf
                    <button class="bg-[#2D8F5E] text-white px-4 py-2 rounded-lg text-sm font-medium">Approve</button>
                </form>
                <form method="POST" action="{{ route('leave-requests.reject', $leaveRequest) }}">
                    @csrf
                    <button class="bg-[#E8433A] text-white px-4 py-2 rounded-lg text-sm font-medium">Reject</button>
                </form>
            @endif
            <a href="{{ route('leave-requests.edit', $leaveRequest) }}" class="bg-[#F5F4F1] border border-[#E8E6E1] rounded-lg px-4 py-2 text-sm font-medium text-[#444]">Edit</a>
        </div>
    </div>
</div>
@endsection
