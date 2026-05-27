<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payslip - {{ $payroll->employee->name }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600&family=DM+Mono:wght@400;500&display=swap">
    <style>
        body { font-family: 'DM Sans', sans-serif; background: #fff; color: #1a1a1a; margin: 0; padding: 0; }
        .container { max-width: 520px; margin: 32px auto; background: #fff; border: 1px solid #E8E6E1; border-radius: 12px; padding: 32px; }
        .header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
        .avatar { width: 48px; height: 48px; border-radius: 10px; background: #EAF1FB; color: #185FA5; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 600; }
        .title { font-size: 20px; font-weight: 600; letter-spacing: -0.5px; }
        .meta { font-size: 12px; color: #999; margin-top: 2px; }
        .section { margin-bottom: 24px; }
        .label { font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .value { font-size: 15px; font-family: 'DM Mono', monospace; font-weight: 500; color: #1a1a1a; }
        .breakdown { margin-bottom: 12px; }
        .row { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px; }
        .total { font-size: 16px; font-weight: 600; font-family: 'DM Mono', monospace; color: #1a1a1a; margin-top: 12px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 500; background: #EAF1FB; color: #185FA5; margin-left: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="avatar">{{ $payroll->employee->initials }}</div>
            <div>
                <div class="title">{{ $payroll->employee->name }}</div>
                <div class="meta">{{ $payroll->employee->position?->title }} · {{ $payroll->employee->department?->name }}</div>
            </div>
            <span class="badge">{{ ucfirst($payroll->status) }}</span>
        </div>
        <div class="section">
            <div class="label">Period</div>
            <div class="value">{{ DateTime::createFromFormat('!m', $payroll->period_month)->format('F') }} {{ $payroll->period_year }}</div>
        </div>
        <div class="section">
            <div class="label">Salary Breakdown</div>
            <div class="breakdown">
                <div class="row"><span>Base Salary</span><span>Rp {{ number_format($payroll->base_salary,0,',','.') }}</span></div>
                @if($payroll->other_allowance > 0)
                <div class="row"><span>Allowances</span><span>Rp {{ number_format($payroll->other_allowance,0,',','.') }}</span></div>
                @endif
                @if($payroll->overtime_pay > 0)
                <div class="row"><span>Overtime</span><span>Rp {{ number_format($payroll->overtime_pay,0,',','.') }}</span></div>
                @endif
                @if($payroll->bonus > 0)
                <div class="row"><span>Bonus</span><span>Rp {{ number_format($payroll->bonus,0,',','.') }}</span></div>
                @endif
                @if($payroll->total_deductions > 0)
                <div class="row"><span>Deductions</span><span>- Rp {{ number_format($payroll->total_deductions,0,',','.') }}</span></div>
                @endif
            </div>
            <div class="total">Net Salary: Rp {{ number_format($payroll->net_salary,0,',','.') }}</div>
        </div>
        <div class="section">
            <div class="label">Notes</div>
            <div class="value">{{ $payroll->notes ?: '-' }}</div>
        </div>
        <div class="section">
            <div class="label">Generated</div>
            <div class="value">{{ $payroll->generated_at?->format('d M Y') }}</div>
        </div>
        <div class="section">
            <div class="label">Paid</div>
            <div class="value">{{ $payroll->paid_at?->format('d M Y') ?? '-' }}</div>
        </div>
    </div>
</body>
</html>
