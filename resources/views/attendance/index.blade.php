@extends('layouts.app')
@section('breadcrumb', 'Attendance')
@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-[18px] font-semibold text-gray-900">Attendance Board</h1>
    <a href="{{ route('attendance.create') }}" class="btn-action-primary">
        <x-heroicon-o-plus class="w-4 h-4" />
        <span>Add Attendance</span>
    </a>
</div>
<form method="GET" class="mb-4 flex gap-2">
    <input type="date" name="date" value="{{ request('date') }}" class="h-[34px] px-3 border border-[#E8E6E1] rounded-lg text-[12px] bg-white outline-none" />
    <select name="department" class="h-[34px] px-3 border border-[#E8E6E1] rounded-lg text-[12px] bg-white">
        <option value="">All Departments</option>
        @foreach($departments as $dept)
            <option value="{{ $dept->id }}" @selected(request('department') == $dept->id)>{{ $dept->name }}</option>
        @endforeach
    </select>
    <button class="btn-action-soft h-[34px]">Filter</button>
</form>

<div class="bg-white border border-[#E8E6E1] rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-[#E8E6E1]">
                <th class="text-left text-[10px] text-[#999] uppercase tracking-[0.5px] px-4 py-2.5 font-medium">Employee</th>
                <th class="text-left text-[10px] text-[#999] uppercase tracking-[0.5px] px-4 py-2.5 font-medium">Date</th>
                <th class="text-left text-[10px] text-[#999] uppercase tracking-[0.5px] px-4 py-2.5 font-medium">Status</th>
                <th class="text-right text-[10px] text-[#999] uppercase tracking-[0.5px] px-4 py-2.5 font-medium">Check In → Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $att)
            <tr class="border-b border-[#F9F8F6] hover:bg-[#FAFAF8] transition-colors">
                <td class="px-4 py-2.5">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-[6px] flex items-center justify-center font-semibold text-[9px] flex-shrink-0" style="background: {{ $att->employee->department?->avatar_bg ?? '#EAF1FB' }}; color: {{ $att->employee->department?->avatar_text ?? '#185FA5' }};">
                            {{ $att->employee->initials }}
                        </div>
                        <div>
                            <div class="text-[12px] font-medium text-gray-900 leading-tight">{{ $att->employee->name }}</div>
                            <div class="text-[10px] text-[#bbb]">{{ $att->employee->department?->name }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-2.5">
                    <div class="text-[12px] font-medium text-gray-900 leading-tight numeric">{{ $att->date->format('d M Y') }}</div>
                    <div class="text-[10px] text-[#bbb]">{{ $att->date->diffForHumans() }}</div>
                </td>
                <td class="px-4 py-2.5">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium" style="background: {{ $att->status_bg }}; color: {{ $att->status_text }};">
                        {{ ucfirst($att->status) }}
                    </span>
                </td>
                <td class="px-4 py-2.5 text-right text-[12px] font-medium numeric text-gray-700">
                    {{ $att->check_in ? date('H:i', strtotime($att->check_in)) : '-' }}
                    <span class="text-[#ccc]">→</span>
                    {{ $att->check_out ? date('H:i', strtotime($att->check_out)) : '-' }}
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center text-[#aaa] text-[12px] py-8">No attendance records found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4 flex items-center justify-between">
    <p class="text-[11px] text-[#aaa]">Showing {{ $attendances->firstItem() ?? 0 }}-{{ $attendances->lastItem() ?? 0 }} of {{ $attendances->total() }} results</p>
    {{ $attendances->links() }}
</div>
@endsection