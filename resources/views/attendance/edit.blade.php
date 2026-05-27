@extends('layouts.app')
@section('breadcrumb', 'Edit Attendance')
@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-[20px] font-semibold text-gray-900 mb-6">Edit Attendance</h1>
    <form method="POST" action="{{ route('attendance.update', $attendance) }}" class="bg-white border border-[#E8E6E1] rounded-xl p-6 form-grid grid grid-cols-2 gap-4">
        @csrf
        @method('PUT')
        <div class="flex flex-col gap-1.5 col-span-2">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Employee</label>
            <select name="employee_id" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('employee_id') border-[#E8433A] bg-[#FEF0EE] @enderror">
                <option value="">Select employee</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" @selected(old('employee_id', $attendance->employee_id) == $emp->id)>{{ $emp->name }} ({{ $emp->department?->name }})</option>
                @endforeach
            </select>
            @error('employee_id')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Date</label>
            <input type="date" name="date" value="{{ old('date', $attendance->date->format('Y-m-d')) }}" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('date') border-[#E8433A] bg-[#FEF0EE] @enderror" />
            @error('date')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Status</label>
            <select name="status" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('status') border-[#E8433A] bg-[#FEF0EE] @enderror">
                <option value="">Select status</option>
                <option value="present" @selected(old('status', $attendance->status) == 'present')>On Time</option>
                <option value="late" @selected(old('status', $attendance->status) == 'late')>Late</option>
                <option value="absent" @selected(old('status', $attendance->status) == 'absent')>Absent</option>
                <option value="leave" @selected(old('status', $attendance->status) == 'leave')>Leave</option>
                <option value="holiday" @selected(old('status', $attendance->status) == 'holiday')>Holiday</option>
            </select>
            @error('status')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Check In</label>
            <input type="datetime-local" name="check_in" value="{{ old('check_in', $attendance->check_in ? date('Y-m-d\TH:i', strtotime($attendance->check_in)) : '') }}" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('check_in') border-[#E8433A] bg-[#FEF0EE] @enderror" />
            @error('check_in')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Check Out</label>
            <input type="datetime-local" name="check_out" value="{{ old('check_out', $attendance->check_out ? date('Y-m-d\TH:i', strtotime($attendance->check_out)) : '') }}" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('check_out') border-[#E8433A] bg-[#FEF0EE] @enderror" />
            @error('check_out')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5 col-span-2">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Notes</label>
            <input type="text" name="notes" value="{{ old('notes', $attendance->notes) }}" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('notes') border-[#E8433A] bg-[#FEF0EE] @enderror" placeholder="Notes (optional)" />
            @error('notes')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="col-span-2 flex justify-end gap-2 mt-4">
            <a href="{{ route('attendance.index') }}" class="bg-[#F5F4F1] border border-[#E8E6E1] rounded-lg px-4 py-2 text-sm font-medium text-[#444]">Cancel</a>
            <button class="bg-[#1a1a1a] text-white px-4 py-2 rounded-lg text-sm font-medium">Update</button>
        </div>
    </form>
</div>
@endsection
