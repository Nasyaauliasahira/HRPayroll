@extends('layouts.app')
@section('breadcrumb', $employee->name)
@section('content')
<div class="flex gap-8">
    <div class="flex-1">
        <div class="bg-white border border-[#E8E6E1] rounded-xl p-6 mb-6 flex items-center gap-6">
            <div class="w-16 h-16 rounded-[10px] flex items-center justify-center font-semibold text-[22px]" style="background: {{ $employee->department?->avatar_bg ?? '#EAF1FB' }}; color: {{ $employee->department?->avatar_text ?? '#185FA5' }};">
                {{ $employee->initials }}
            </div>
            <div>
                <div class="text-[20px] font-semibold text-gray-900">{{ $employee->name }}</div>
                <div class="text-[13px] text-[#999] mt-1">{{ $employee->position?->title }} · {{ $employee->department?->name }}</div>
                <div class="flex gap-2 mt-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium" style="background: {{ $employee->status_bg }}; color: {{ $employee->status_text }};">
                        {{ ucfirst($employee->status) }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-[#F5F4F1] text-[#999]">Joined {{ $employee->join_date->format('M Y') }}</span>
                </div>
            </div>
            <div class="ml-auto flex flex-col gap-2">
                <a href="{{ route('employees.edit', $employee) }}" class="bg-[#F5F4F1] border border-[#E8E6E1] rounded-lg px-4 py-2 text-sm font-medium text-[#444]">Edit</a>
            </div>
        </div>
        <div class="bg-white border border-[#E8E6E1] rounded-xl p-6">
            <div class="text-[13px] font-medium text-gray-900 mb-3">Attendance</div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-[13px]">
                    <thead>
                        <tr class="border-b border-[#F1EFE8] bg-[#F5F4F1]/50">
                            <th class="text-left px-4 py-2 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Date</th>
                            <th class="text-left px-4 py-2 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Status</th>
                            <th class="text-left px-4 py-2 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Check In</th>
                            <th class="text-left px-4 py-2 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Check Out</th>
                            <th class="text-right px-4 py-2 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Work Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employee->attendances()->latest()->limit(10)->get() as $att)
                        <tr class="border-b border-[#F8F7F4] hover:bg-[#F5F4F1]/50 transition-all">
                            <td class="px-4 py-2">{{ $att->date->format('d M Y') }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium"
                                    style="background: {{ $att->status_bg }}; color: {{ $att->status_text }};">
                                    {{ ucfirst($att->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $att->check_in ? date('H:i', strtotime($att->check_in)) : '-' }}</td>
                            <td class="px-4 py-2">{{ $att->check_out ? date('H:i', strtotime($att->check_out)) : '-' }}</td>
                            <td class="px-4 py-2 text-right numeric">{{ $att->work_hours ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @section('right')
    <aside class="w-64 flex-shrink-0">
        <div class="bg-white border border-[#E8E6E1] rounded-xl p-4 mb-6">
            <div class="text-[13px] font-medium text-gray-900 mb-2">Employee Info</div>
            <div class="text-[12px] text-gray-900 mb-1"><span class="text-[#999]">Department:</span> {{ $employee->department?->name }}</div>
            <div class="text-[12px] text-gray-900 mb-1"><span class="text-[#999]">Position:</span> {{ $employee->position?->title }}</div>
            <div class="text-[12px] text-gray-900 mb-1"><span class="text-[#999]">Join Date:</span> {{ $employee->join_date->format('d M Y') }}</div>
            <div class="text-[12px] text-gray-900 mb-1"><span class="text-[#999]">Status:</span> {{ ucfirst($employee->status) }}</div>
        </div>
        <div class="bg-white border border-[#E8E6E1] rounded-xl p-4 mb-6">
            <div class="text-[13px] font-medium text-gray-900 mb-2">Pinned Payslips</div>
            <div class="text-[12px] text-[#aaa]">(Payslip list here)</div>
        </div>
    </aside>
    @show
</div>
@endsection
