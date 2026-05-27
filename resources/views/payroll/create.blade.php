@extends('layouts.app')
@section('breadcrumb', 'Generate Payroll')
@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-[20px] font-semibold text-gray-900 mb-6">Generate Payroll</h1>
    <form method="POST" action="{{ route('payroll.store') }}" class="bg-white border border-[#E8E6E1] rounded-xl p-6 form-grid grid grid-cols-2 gap-4">
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
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Month</label>
            <select name="period_month" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('period_month') border-[#E8433A] bg-[#FEF0EE] @enderror">
                <option value="">Select month</option>
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" @selected(old('period_month') == $m)>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                @endforeach
            </select>
            @error('period_month')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Year</label>
            <select name="period_year" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('period_year') border-[#E8433A] bg-[#FEF0EE] @enderror">
                <option value="">Select year</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" @selected(old('period_year') == $y)>{{ $y }}</option>
                @endforeach
            </select>
            @error('period_year')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5 col-span-2">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Bonus</label>
            <input type="number" name="bonus" value="{{ old('bonus') }}" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white numeric @error('bonus') border-[#E8433A] bg-[#FEF0EE] @enderror" placeholder="Bonus (optional)" />
            @error('bonus')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="col-span-2 flex justify-end gap-2 mt-4">
            <a href="{{ route('payroll.index') }}" class="bg-[#F5F4F1] border border-[#E8E6E1] rounded-lg px-4 py-2 text-sm font-medium text-[#444]">Cancel</a>
            <button class="bg-[#1a1a1a] text-white px-4 py-2 rounded-lg text-sm font-medium">Generate</button>
        </div>
    </form>
</div>
@endsection
