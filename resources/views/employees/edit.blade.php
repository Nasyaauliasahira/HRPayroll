@extends('layouts.app')
@section('breadcrumb', 'Edit Employee')
@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-[20px] font-semibold text-gray-900 mb-6">Edit Employee</h1>
    <form method="POST" action="{{ route('employees.update', $employee) }}" enctype="multipart/form-data" class="bg-white border border-[#E8E6E1] rounded-xl p-6 form-grid grid grid-cols-2 gap-4">
        @csrf
        @method('PUT')
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Name</label>
            <input type="text" name="name" value="{{ old('name', $employee->name) }}" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('name') border-[#E8433A] bg-[#FEF0EE] @enderror" placeholder="Full name" />
            @error('name')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Email</label>
            <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('email') border-[#E8433A] bg-[#FEF0EE] @enderror" placeholder="Email address" />
            @error('email')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('phone') border-[#E8433A] bg-[#FEF0EE] @enderror" placeholder="Phone number" />
            @error('phone')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">NIK</label>
            <input type="text" name="nik" value="{{ old('nik', $employee->nik) }}" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('nik') border-[#E8433A] bg-[#FEF0EE] @enderror" placeholder="NIK" />
            @error('nik')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Department</label>
            <select name="department_id" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('department_id') border-[#E8433A] bg-[#FEF0EE] @enderror">
                <option value="">Select department</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" @selected(old('department_id', $employee->department_id) == $dept->id)>{{ $dept->name }}</option>
                @endforeach
            </select>
            @error('department_id')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Position</label>
            <select name="position_id" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('position_id') border-[#E8433A] bg-[#FEF0EE] @enderror">
                <option value="">Select position</option>
                @foreach($positions as $pos)
                    <option value="{{ $pos->id }}" @selected(old('position_id', $employee->position_id) == $pos->id)>{{ $pos->title }}</option>
                @endforeach
            </select>
            @error('position_id')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Base Salary</label>
            <input type="number" name="base_salary" value="{{ old('base_salary', $employee->base_salary) }}" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white numeric @error('base_salary') border-[#E8433A] bg-[#FEF0EE] @enderror" placeholder="Base salary" />
            @error('base_salary')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Join Date</label>
            <input type="date" name="join_date" value="{{ old('join_date', $employee->join_date) }}" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('join_date') border-[#E8433A] bg-[#FEF0EE] @enderror" />
            @error('join_date')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-1.5 col-span-2">
            <label class="text-[11px] font-medium text-[#666] uppercase tracking-[0.5px]">Photo</label>
            <input type="file" name="photo" class="h-[38px] px-3 border border-[#E8E6E1] rounded-lg text-sm bg-white @error('photo') border-[#E8433A] bg-[#FEF0EE] @enderror" />
            @error('photo')<p class="text-[11px] text-[#B0362E] mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="col-span-2 flex justify-end gap-2 mt-4">
            <a href="{{ route('employees.index') }}" class="bg-[#F5F4F1] border border-[#E8E6E1] rounded-lg px-4 py-2 text-sm font-medium text-[#444]">Cancel</a>
            <button class="bg-[#1a1a1a] text-white px-4 py-2 rounded-lg text-sm font-medium">Update</button>
        </div>
    </form>
</div>
@endsection
