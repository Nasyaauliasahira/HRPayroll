@extends('layouts.app')
@section('breadcrumb', 'Payroll Detail')
@section('content')
<div class="flex gap-8">
    <div class="flex-1">
        <div class="bg-white border border-[#E8E6E1] rounded-xl p-6 mb-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-[10px] flex items-center justify-center font-semibold text-[16px]" style="background: {{ $payroll->employee->department?->avatar_bg ?? '#EAF1FB' }}; color: {{ $payroll->employee->department?->avatar_text ?? '#185FA5' }};">
                    {{ $payroll->employee->initials }}
                </div>
                <div>
                    <div class="text-[16px] font-semibold text-gray-900">{{ $payroll->employee->name }}</div>
                    <div class="text-[12px] text-[#999] mt-0.5">{{ $payroll->employee->position?->title }} · {{ $payroll->employee->department?->name }}</div>
                </div>
                <div class="ml-auto flex flex-col gap-2">
                    @if($payroll->status === 'draft')
                        <form method="POST" action="{{ route('payroll.finalize', $payroll) }}">
                            @csrf
                            <button class="bg-[#2D8F5E] text-white px-4 py-2 rounded-lg text-sm font-medium">Finalize</button>
                        </form>
                    @elseif($payroll->status === 'finalized')
                        <form method="POST" action="{{ route('payroll.pay', $payroll) }}">
                            @csrf
                            <button class="bg-[#D97706] text-white px-4 py-2 rounded-lg text-sm font-medium">Mark as Paid</button>
                        </form>
                    @endif
                    <a href="{{ route('payroll.payslip', $payroll) }}" class="bg-[#185FA5] text-white px-4 py-2 rounded-lg text-sm font-medium" target="_blank">Print Payslip</a>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <div class="text-[10px] text-[#aaa] uppercase tracking-[0.5px] mb-1">Period</div>
                    <div class="text-[13px] font-medium text-gray-900">{{ DateTime::createFromFormat('!m', $payroll->period_month)->format('F') }} {{ $payroll->period_year }}</div>
                </div>
                <div>
                    <div class="text-[10px] text-[#aaa] uppercase tracking-[0.5px] mb-1">Status</div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium {{
                        $payroll->status === 'draft' ? 'bg-[#EAF1FB] text-[#185FA5]' :
                        ($payroll->status === 'finalized' ? 'bg-[#EAF6F0] text-[#1B7A4E]' :
                        ($payroll->status === 'paid' ? 'bg-[#FEF3E2] text-[#8A5A0A]' :
                        'bg-[#F1EFE8] text-[#5F5E5A]'))
                    }}">
                        {{ ucfirst($payroll->status) }}
                    </span>
                </div>
            </div>
            <div class="mb-6">
                <div class="text-[13px] font-medium text-gray-900 mb-2">Salary Breakdown</div>
                <div class="mb-2">
                    <div class="flex justify-between text-[11px] text-[#666] mb-1">
                        <span>Base Salary</span>
                        <span class="numeric">Rp {{ number_format($payroll->base_salary,0,',','.') }}</span>
                    </div>
                    <div class="h-1.5 bg-[#F1EFE8] rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-[#1a1a1a]" style="width: 100%"></div>
                    </div>
                </div>
                @if($payroll->other_allowance > 0)
                <div class="mb-2">
                    <div class="flex justify-between text-[11px] text-[#666] mb-1">
                        <span>Allowances</span>
                        <span class="numeric">Rp {{ number_format($payroll->other_allowance,0,',','.') }}</span>
                    </div>
                    <div class="h-1.5 bg-[#F1EFE8] rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-[#378ADD]" style="width: 80%"></div>
                    </div>
                </div>
                @endif
                @if($payroll->overtime_pay > 0)
                <div class="mb-2">
                    <div class="flex justify-between text-[11px] text-[#666] mb-1">
                        <span>Overtime</span>
                        <span class="numeric">Rp {{ number_format($payroll->overtime_pay,0,',','.') }}</span>
                    </div>
                    <div class="h-1.5 bg-[#F1EFE8] rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-[#2D8F5E]" style="width: 60%"></div>
                    </div>
                </div>
                @endif
                @if($payroll->total_deductions > 0)
                <div class="mb-2">
                    <div class="flex justify-between text-[11px] text-[#666] mb-1">
                        <span>Deductions</span>
                        <span class="numeric">Rp {{ number_format($payroll->total_deductions,0,',','.') }}</span>
                    </div>
                    <div class="h-1.5 bg-[#F1EFE8] rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-[#E8433A]" style="width: 40%"></div>
                    </div>
                </div>
                @endif
                @if($payroll->bonus > 0)
                <div class="mb-2">
                    <div class="flex justify-between text-[11px] text-[#666] mb-1">
                        <span>Bonus</span>
                        <span class="numeric">Rp {{ number_format($payroll->bonus,0,',','.') }}</span>
                    </div>
                    <div class="h-1.5 bg-[#F1EFE8] rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-[#D97706]" style="width: 30%"></div>
                    </div>
                </div>
                @endif
                <div class="flex justify-between text-[13px] font-semibold text-gray-900 mt-4">
                    <span>Net Salary</span>
                    <span class="numeric">Rp {{ number_format($payroll->net_salary,0,',','.') }}</span>
                </div>
            </div>
        </div>
    </div>
    @section('right')
    <aside class="w-64 flex-shrink-0">
        <div class="bg-white border border-[#E8E6E1] rounded-xl p-4 mb-6">
            <div class="text-[13px] font-medium text-gray-900 mb-2">Payroll Info</div>
            <div class="text-[12px] text-gray-900 mb-1"><span class="text-[#999]">Period:</span> {{ DateTime::createFromFormat('!m', $payroll->period_month)->format('F') }} {{ $payroll->period_year }}</div>
            <div class="text-[12px] text-gray-900 mb-1"><span class="text-[#999]">Status:</span> {{ ucfirst($payroll->status) }}</div>
            <div class="text-[12px] text-gray-900 mb-1"><span class="text-[#999]">Generated:</span> {{ $payroll->generated_at?->format('d M Y') }}</div>
            <div class="text-[12px] text-gray-900 mb-1"><span class="text-[#999]">Paid:</span> {{ $payroll->paid_at?->format('d M Y') ?? '-' }}</div>
        </div>
        <div class="bg-white border border-[#E8E6E1] rounded-xl p-4 mb-6">
            <div class="text-[13px] font-medium text-gray-900 mb-2">Payslip</div>
            <a href="{{ route('payroll.payslip', $payroll) }}" class="bg-[#185FA5] text-white px-4 py-2 rounded-lg text-sm font-medium w-full block text-center" target="_blank">Download PDF</a>
        </div>
        <div class="bg-white border border-[#E8E6E1] rounded-xl p-4">
            <div class="text-[13px] font-medium text-gray-900 mb-2">Notes</div>
            <div class="text-[12px] text-[#aaa]">{{ $payroll->notes ?: '-' }}</div>
        </div>
    </aside>
    @show
</div>
@endsection
