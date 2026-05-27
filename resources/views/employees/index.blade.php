@extends('layouts.app')
@section('breadcrumb', 'Employees')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-[18px] font-semibold text-gray-900">Employees</h1>
    <a href="{{ route('employees.create') }}" class="btn-action-primary">
        <x-heroicon-o-plus class="w-4 h-4" />
        <span>Add Employee</span>
    </a>
</div>
<form method="GET" class="mb-4 flex gap-3">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name, email..." class="h-[34px] px-3 border border-[#E8E6E1] rounded-lg text-[12px] bg-white focus:ring-2 focus:ring-[#1a1a1a]/10 outline-none transition-all" />
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
                <th class="text-left text-[10px] uppercase tracking-[0.5px] text-[#999] py-2.5 px-4 font-medium">Employee</th>
                <th class="text-left text-[10px] uppercase tracking-[0.5px] text-[#999] py-2.5 px-4 font-medium">Role</th>
                <th class="text-right text-[10px] uppercase tracking-[0.5px] text-[#999] py-2.5 px-4 font-medium">Salary</th>
                <th class="text-right text-[10px] uppercase tracking-[0.5px] text-[#999] py-2.5 px-4 font-medium">Attend.</th>
                <th class="text-left text-[10px] uppercase tracking-[0.5px] text-[#999] py-2.5 px-4 font-medium">Status</th>
                <th class="text-right text-[10px] uppercase tracking-[0.5px] text-[#999] py-2.5 px-4 font-medium">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
            <tr class="border-b border-[#F9F8F6] hover:bg-[#FAFAF8] {{ $employee->status !== 'active' ? 'opacity-60' : '' }}">
                <td class="px-4 py-2.5">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-[6px] flex items-center justify-center font-semibold text-[9px] flex-shrink-0" style="background: {{ $employee->department?->avatar_bg ?? '#EAF1FB' }}; color: {{ $employee->department?->avatar_text ?? '#185FA5' }};">
                            {{ $employee->initials }}
                        </div>
                        <div>
                            <div class="text-[12px] font-medium text-gray-900 leading-tight">{{ $employee->name }}</div>
                            <div class="text-[10px] text-[#bbb]">{{ $employee->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-2.5">
                    <div class="text-[12px] font-medium text-gray-900 leading-tight">{{ $employee->position?->title ?? '-' }}</div>
                    <div class="text-[10px] text-[#bbb]">{{ $employee->department?->name ?? '-' }}</div>
                </td>
                <td class="px-4 py-2.5 text-right text-[12px] font-medium text-gray-900 numeric">{{ number_format($employee->base_salary,0,',','.') }}</td>
                <td class="px-4 py-2.5 text-right text-[12px] font-medium text-gray-900 numeric">{{ $employee->attendance_rate }}%</td>
                <td class="px-4 py-2.5">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium" style="background: {{ $employee->status === 'active' ? '#EAF6F0' : '#F1EFE8' }}; color: {{ $employee->status === 'active' ? '#1B7A4E' : '#5F5E5A' }};">
                        {{ ucfirst($employee->status) }}
                    </span>
                </td>
                <td class="px-4 py-2.5 text-right">
                    <a href="{{ route('employees.show', $employee) }}" class="btn-action-link-blue">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-[12px] text-[#aaa] py-8">No employees found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4 flex items-center justify-between">
    <p class="text-[11px] text-[#aaa]">Showing {{ $employees->firstItem() ?? 0 }}-{{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }} results</p>
    {{ $employees->links() }}
</div>
@endsection
