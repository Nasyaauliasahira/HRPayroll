@extends('layouts.app')
@section('breadcrumb', 'Payroll')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-[18px] font-semibold text-gray-900">Payroll</h1>
    <a href="{{ route('payroll.create') }}" class="btn-action-primary">
        <x-heroicon-o-bolt class="w-4 h-4" />
        <span>Generate Payroll</span>
    </a>
</div>
<form method="GET" class="mb-4 flex gap-3">
    <select name="month" class="h-[34px] px-3 border border-[#E8E6E1] rounded-lg text-[12px] bg-white">
        <option value="">All Months</option>
        @foreach(range(1,12) as $m)
            <option value="{{ $m }}" @selected(request('month') == $m)>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
        @endforeach
    </select>
    <select name="year" class="h-[34px] px-3 border border-[#E8E6E1] rounded-lg text-[12px] bg-white">
        <option value="">All Years</option>
        @foreach($years as $y)
            <option value="{{ $y }}" @selected(request('year') == $y)>{{ $y }}</option>
        @endforeach
    </select>
    <select name="employee" class="h-[34px] px-3 border border-[#E8E6E1] rounded-lg text-[12px] bg-white">
        <option value="">All Employees</option>
        @foreach($employees as $emp)
            <option value="{{ $emp->id }}" @selected(request('employee') == $emp->id)>{{ $emp->name }}</option>
        @endforeach
    </select>
    <button class="btn-action-soft h-[34px]">Filter</button>
    <a href="{{ route('payroll.bulk') }}?month={{ request('month') }}&year={{ request('year') }}" class="btn-action-primary ml-auto">Bulk Generate</a>
</form>
<div class="bg-white border border-[#E8E6E1] rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-[#E8E6E1]">
                <th class="text-left px-4 py-2.5 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Employee</th>
                <th class="text-left px-4 py-2.5 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Period</th>
                <th class="text-right px-4 py-2.5 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Net Salary</th>
                <th class="text-left px-4 py-2.5 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Status</th>
                <th class="text-right px-4 py-2.5 text-[10px] font-medium text-[#999] uppercase tracking-[0.5px]">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payrolls as $payroll)
            <tr class="border-b border-[#F9F8F6] hover:bg-[#FAFAF8] transition-colors">
                <td class="px-4 py-2.5">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-[6px] flex items-center justify-center font-semibold text-[9px] flex-shrink-0" style="background: {{ $payroll->employee->department?->avatar_bg ?? '#EAF1FB' }}; color: {{ $payroll->employee->department?->avatar_text ?? '#185FA5' }};">
                            {{ $payroll->employee->initials }}
                        </div>
                        <div>
                            <div class="text-[12px] font-medium text-gray-900 leading-tight">{{ $payroll->employee->name }}</div>
                            <div class="text-[10px] text-[#bbb]">{{ $payroll->employee->department?->name }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-2.5">
                    <div class="text-[12px] font-medium text-gray-900 leading-tight">{{ DateTime::createFromFormat('!m', $payroll->period_month)->format('F') }} {{ $payroll->period_year }}</div>
                    <div class="text-[10px] text-[#bbb]">{{ $payroll->created_at?->format('d M Y') }}</div>
                </td>
                <td class="px-4 py-2.5 text-right text-[12px] font-medium text-gray-900 numeric">Rp {{ number_format($payroll->net_salary,0,',','.') }}</td>
                <td class="px-4 py-2.5">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium" style="background: {{ $payroll->status === 'draft' ? '#EAF1FB' : ($payroll->status === 'finalized' ? '#EAF6F0' : ($payroll->status === 'paid' ? '#FEF3E2' : '#F1EFE8')) }}; color: {{ $payroll->status === 'draft' ? '#185FA5' : ($payroll->status === 'finalized' ? '#1B7A4E' : ($payroll->status === 'paid' ? '#8A5A0A' : '#5F5E5A')) }};">
                        {{ ucfirst($payroll->status) }}
                    </span>
                </td>
                <td class="px-4 py-2.5 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <a href="{{ route('payroll.show', $payroll) }}"
                           class="px-2.5 py-1 rounded-md text-[11px] font-medium bg-[#F1EFE8] text-[#444] hover:bg-[#E8E6E1] transition-colors">
                            Detail
                        </a>

                        @if($payroll->status === 'draft')
                        <form method="POST" action="{{ route('payroll.finalize', $payroll) }}" class="inline">
                            @csrf
                            <button class="px-2.5 py-1 rounded-md text-[11px] font-medium bg-[#EAF6F0] text-[#1B7A4E] hover:bg-[#D4EFE3] transition-colors">
                                Finalize
                            </button>
                        </form>
                        @elseif($payroll->status === 'finalized')
                        <form method="POST" action="{{ route('payroll.pay', $payroll) }}" class="inline">
                            @csrf
                            <button class="px-2.5 py-1 rounded-md text-[11px] font-medium bg-[#FEF3E2] text-[#8A5A0A] hover:bg-[#FDECD0] transition-colors">
                                Mark as Paid
                            </button>
                        </form>
                        @else
                        @endif

                        <a href="{{ route('payroll.payslip', $payroll) }}" target="_blank"
                           class="px-2.5 py-1 rounded-md text-[11px] font-medium bg-[#EAF1FB] text-[#185FA5] hover:bg-[#D6E8F7] transition-colors">
                            Payslip
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-[#aaa] text-[12px] py-8">No payroll records found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4 flex items-center justify-between">
    <p class="text-[11px] text-[#aaa]">Showing {{ $payrolls->firstItem() ?? 0 }}-{{ $payrolls->lastItem() ?? 0 }} of {{ $payrolls->total() }} results</p>
    {{ $payrolls->links() }}
</div>
@endsection
