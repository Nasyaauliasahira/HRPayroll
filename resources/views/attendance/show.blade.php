@extends('layouts.app')
@section('breadcrumb', 'Attendance Detail')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white border border-[#E8E6E1] rounded-xl p-6 mb-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 rounded-[10px] flex items-center justify-center font-semibold text-[12px] flex-shrink-0" style="background: {{ $attendance->employee->department?->avatar_bg ?? '#EAF1FB' }}; color: {{ $attendance->employee->department?->avatar_text ?? '#185FA5' }};">
                {{ $attendance->employee->initials }}
            </div>
            <div>
                <div class="text-[13px] font-medium text-gray-900">{{ $attendance->employee->name }}</div>
                <div class="text-[11px] text-[#999] mt-0.5">{{ $attendance->employee->department?->name }}</div>
            </div>
        </div>
        <div class="flex gap-4 mb-2">
            <div>
                <div class="text-[10px] text-[#aaa] uppercase tracking-[0.4px]">Date</div>
                <div class="text-[12px] font-medium text-gray-900 mt-0.5">{{ $attendance->date->format('d M Y') }}</div>
            </div>
            <div>
                <div class="text-[10px] text-[#aaa] uppercase tracking-[0.4px]">Status</div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium" style="background: {{ $attendance->status_bg }}; color: {{ $attendance->status_text }};">
                    {{ ucfirst($attendance->status) }}
                </span>
            </div>
        </div>
        <div class="flex gap-4 mb-2">
            <div>
                <div class="text-[10px] text-[#aaa] uppercase tracking-[0.4px]">Check In</div>
                <div class="text-[12px] font-medium text-gray-900 mt-0.5">{{ $attendance->check_in ? date('H:i', strtotime($attendance->check_in)) : '-' }}</div>
            </div>
            <div>
                <div class="text-[10px] text-[#aaa] uppercase tracking-[0.4px]">Check Out</div>
                <div class="text-[12px] font-medium text-gray-900 mt-0.5">{{ $attendance->check_out ? date('H:i', strtotime($attendance->check_out)) : '-' }}</div>
            </div>
            <div>
                <div class="text-[10px] text-[#aaa] uppercase tracking-[0.4px]">Work Hours</div>
                <div class="text-[12px] font-medium text-gray-900 mt-0.5 numeric">{{ $attendance->work_hours ?? '-' }}</div>
            </div>
        </div>
        <div class="mt-4 text-[12px] text-[#aaa]">Notes: {{ $attendance->notes ?: '-' }}</div>
    </div>
    <div class="flex justify-end gap-2">
        <a href="{{ route('attendance.edit', $attendance) }}" class="bg-[#F5F4F1] border border-[#E8E6E1] rounded-lg px-4 py-2 text-sm font-medium text-[#444]">Edit</a>
        <form method="POST" action="{{ route('attendance.destroy', $attendance) }}" onsubmit="return confirm('Delete this record?')">
            @csrf
            @method('DELETE')
            <button class="bg-[#E8433A] text-white px-4 py-2 rounded-lg text-sm font-medium">Delete</button>
        </form>
    </div>
</div>
@endsection
