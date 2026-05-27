@extends('layouts.app')
@section('breadcrumb', 'New Leave Request')
@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-[20px] font-semibold text-gray-900 mb-6">New Leave Request</h1>
    <form method="POST" action="{{ route('leave-requests.store') }}" class="bg-white border border-[#E8E6E1] rounded-xl p-6 form-grid grid grid-cols-2 gap-4">
        @csrf
        <div class="flex flex-col gap-1.5 col-span-2">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Employee</label>
            <select name="employee_id" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('employee_id') border-[#E8433A] bg-[#FEF0EE] @enderror">
                <option value="">Select employee</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" @selected(old('employee_id') == $emp->id)>{{ $emp->name }} ({{ $emp->department?->name }})</option>
                @endforeach
            </select>
            @error('employee_id')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Start Date</label>
            <input type="date" name="start_date" value="{{ old('start_date') }}" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('start_date') border-[#E8433A] bg-[#FEF0EE] @enderror" />
            @error('start_date')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">End Date</label>
            <input type="date" name="end_date" value="{{ old('end_date') }}" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('end_date') border-[#E8433A] bg-[#FEF0EE] @enderror" />
            @error('end_date')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5 col-span-2">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Reason</label>
            <input type="text" name="reason" value="{{ old('reason') }}" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('reason') border-[#E8433A] bg-[#FEF0EE] @enderror" placeholder="Reason for leave" />
            @error('reason')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="col-span-2 flex justify-end gap-2 mt-4">
            <a href="{{ route('leave-requests.index') }}" class="bg-[#F5F4F1] border border-[#E8E6E1] rounded-lg px-4 py-2 text-sm font-medium text-[#444]">Cancel</a>
            <button class="bg-[#1a1a1a] text-white px-4 py-2 rounded-lg text-sm font-medium">Submit</button>
        </div>
    </form>
</div>
@endsection
