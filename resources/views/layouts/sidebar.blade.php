<aside class="w-16 bg-white border-r border-[#E8E6E1] flex flex-col h-screen sticky top-0 self-start flex-shrink-0 items-center py-4">
    <div class="w-10 h-10 bg-[#1a1a1a] rounded-xl flex items-center justify-center shadow-sm mb-5" title="HRCore">
        <svg width="18" height="18" fill="none" viewBox="0 0 20 20"><rect width="18" height="18" rx="4" fill="#fff"/><rect x="3" y="3" width="12" height="12" rx="3" fill="#1a1a1a"/></svg>
    </div>

    <nav class="flex-1 flex flex-col items-center gap-2 w-full">
        <div class="relative group">
            <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-xl flex items-center justify-center transition-all {{ request()->routeIs('dashboard') ? 'bg-[#1a1a1a] text-white' : 'text-[#999] hover:bg-[#F1EFE8]' }}">
                <x-heroicon-o-home class="w-5 h-5" />
            </a>
            <span class="pointer-events-none absolute left-full ml-2 top-1/2 -translate-y-1/2 whitespace-nowrap bg-[#1a1a1a] text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity">Dashboard</span>
        </div>

        <div class="relative group">
            <a href="{{ route('employees.index') }}" class="w-10 h-10 rounded-xl flex items-center justify-center transition-all {{ request()->routeIs('employees.*') ? 'bg-[#1a1a1a] text-white' : 'text-[#999] hover:bg-[#F1EFE8]' }}">
                <x-heroicon-o-users class="w-5 h-5" />
            </a>
            <span class="pointer-events-none absolute left-full ml-2 top-1/2 -translate-y-1/2 whitespace-nowrap bg-[#1a1a1a] text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity">Employees</span>
        </div>

        <div class="relative group">
            <a href="{{ route('attendance.index') }}" class="w-10 h-10 rounded-xl flex items-center justify-center transition-all {{ request()->routeIs('attendance.*') ? 'bg-[#1a1a1a] text-white' : 'text-[#999] hover:bg-[#F1EFE8]' }}">
                <x-heroicon-o-calendar-days class="w-5 h-5" />
            </a>
            <span class="pointer-events-none absolute left-full ml-2 top-1/2 -translate-y-1/2 whitespace-nowrap bg-[#1a1a1a] text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity">Attendance</span>
        </div>

        <div class="relative group">
            <a href="{{ route('payroll.index') }}" class="w-10 h-10 rounded-xl flex items-center justify-center transition-all {{ request()->routeIs('payroll.*') ? 'bg-[#1a1a1a] text-white' : 'text-[#999] hover:bg-[#F1EFE8]' }}">
                <x-heroicon-o-banknotes class="w-5 h-5" />
            </a>
            <span class="pointer-events-none absolute left-full ml-2 top-1/2 -translate-y-1/2 whitespace-nowrap bg-[#1a1a1a] text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity">Payroll</span>
        </div>

        <div class="relative group">
            <a href="{{ route('leave-requests.index') }}" class="w-10 h-10 rounded-xl flex items-center justify-center transition-all {{ request()->routeIs('leave-requests.*') ? 'bg-[#1a1a1a] text-white' : 'text-[#999] hover:bg-[#F1EFE8]' }}">
                <x-heroicon-o-briefcase class="w-5 h-5" />
            </a>
            <span class="pointer-events-none absolute left-full ml-2 top-1/2 -translate-y-1/2 whitespace-nowrap bg-[#1a1a1a] text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity">Leave</span>
        </div>
    </nav>

    <div class="pt-3 border-t border-[#E8E6E1] w-full flex justify-center">
        <div class="w-10 h-10 rounded-xl bg-[#EAF1FB] flex items-center justify-center text-[11px] font-semibold text-[#185FA5]" title="{{ auth()->user()->name ?? 'Admin User' }}">
            {{ strtoupper(auth()->user()->name[0] ?? 'A') }}
        </div>
    </div>
</aside>
