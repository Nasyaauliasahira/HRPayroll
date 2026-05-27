@extends('layouts.app')
@section('breadcrumb', 'Dashboard')

@section('content')
<h1 class="text-[22px] font-semibold text-gray-900 mb-5">
    Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }},
    {{ auth()->user()->name ?? 'Admin' }} 👋
</h1>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm p-4">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#E8F4FD; color:#2D7DD2;">
            <x-heroicon-o-users class="w-5 h-5" />
        </div>
        <p class="text-[28px] font-bold numeric text-gray-900 leading-none">{{ $employeeCount }}</p>
        <p class="text-[11px] text-[#aaa] mt-2">Total Employees · <span class="text-[#2D7DD2]">+{{ $newEmployees }}</span> this month</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-4">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#E6F7EE; color:#2D8F5E;">
            <x-heroicon-o-banknotes class="w-5 h-5" />
        </div>
        <p class="text-[28px] font-bold numeric text-gray-900 leading-none">{{ $payrollCount }}</p>
        <p class="text-[11px] text-[#aaa] mt-2">Active Payrolls · <span class="text-[#2D8F5E]">+{{ $newPayrolls }}</span> this month</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-4">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#FEF9E7; color:#D97706;">
            <x-heroicon-o-calendar-days class="w-5 h-5" />
        </div>
        <p class="text-[28px] font-bold numeric text-gray-900 leading-none">{{ $attendanceRate }}%</p>
        <p class="text-[11px] text-[#aaa] mt-2">Attendance Rate · <span class="text-[#D97706]">{{ $lateCount }} late</span></p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-4">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#FDECEA; color:#E8433A;">
            <x-heroicon-o-briefcase class="w-5 h-5" />
        </div>
        <p class="text-[28px] font-bold numeric text-gray-900 leading-none">{{ $leaveCount }}</p>
        <p class="text-[11px] text-[#aaa] mt-2">Leave Requests · <span class="text-[#E8433A]">{{ $pendingLeaves }} pending</span></p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm p-5">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-[14px] font-semibold text-gray-900">Recent Activities</h2>
        <a href="{{ route('dashboard') }}" class="text-[11px] text-[#999] hover:text-gray-900 transition-colors">View all →</a>
    </div>
    <div>
        @forelse($activities->take(6) as $activity)
        <div class="flex items-start justify-between gap-3 py-2 border-b border-[#F1EFE8] last:border-0">
            <div class="flex items-start gap-2.5 min-w-0">
                <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0" style="background: {{ $activity['dot'] }}"></div>
                <div class="text-[12px] text-[#555] leading-snug min-w-0">
                    <strong class="text-gray-900 font-medium">{{ $activity['user'] }}</strong>
                    {{ $activity['desc'] }}
                </div>
            </div>
            <div class="text-[10px] text-[#bbb] whitespace-nowrap">{{ $activity['time'] }}</div>
        </div>
        @empty
        <div class="text-[12px] text-[#aaa] py-4 text-center">No recent activities.</div>
        @endforelse
    </div>
</div>
@endsection

@section('right')
<div class="bg-white rounded-2xl shadow-sm p-4 mb-4">
    <div class="text-[13px] font-semibold text-gray-900 mb-3">Payroll Summary</div>
    @foreach($payrollBreakdown as $item)
    <div class="mb-3 last:mb-0">
        <div class="flex justify-between text-[11px] text-[#666] mb-1">
            <span>{{ $item['label'] }}</span>
            <span class="numeric font-medium">{{ $item['value'] }}</span>
        </div>
        <div class="h-2 bg-[#F1EFE8] rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all" style="width: {{ $item['percent'] }}%; background: {{ $item['color'] }}"></div>
        </div>
    </div>
    @endforeach
</div>

<div class="bg-white rounded-2xl shadow-sm p-4 mb-4">
    <div class="text-[13px] font-semibold text-gray-900 mb-3">Quick Stats</div>
    <div class="space-y-2">
        <div class="flex justify-between text-[12px]">
            <span class="text-[#888]">Pending Leave</span>
            <span class="font-medium text-[#E8433A]">{{ $pendingLeaves }}</span>
        </div>
        <div class="flex justify-between text-[12px]">
            <span class="text-[#888]">Late This Month</span>
            <span class="font-medium text-[#D97706]">{{ $lateCount }}</span>
        </div>
        <div class="flex justify-between text-[12px]">
            <span class="text-[#888]">Attendance Rate</span>
            <span class="font-medium text-[#2D8F5E]">{{ $attendanceRate }}%</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm p-4">
    <div class="text-[13px] font-semibold text-gray-900 mb-3">Quick Actions</div>
    <div class="flex flex-col gap-2">
        <a href="{{ route('employees.create') }}" class="btn-action-soft w-full justify-start py-2">
            <x-heroicon-o-plus class="w-4 h-4" />
            <span>Add Employee</span>
        </a>
        <a href="{{ route('attendance.create') }}" class="btn-action-soft w-full justify-start py-2">
            <x-heroicon-o-plus class="w-4 h-4" />
            <span>Record Attendance</span>
        </a>
        <a href="{{ route('leave-requests.create') }}" class="btn-action-soft w-full justify-start py-2">
            <x-heroicon-o-plus class="w-4 h-4" />
            <span>Leave Request</span>
        </a>
        <a href="{{ route('payroll.bulk') }}" class="btn-action-primary w-full justify-start py-2">
            <x-heroicon-o-bolt class="w-4 h-4" />
            <span>Generate Payroll</span>
        </a>
    </div>
</div>
@endsection
