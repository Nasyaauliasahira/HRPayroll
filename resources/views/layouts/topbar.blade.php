<header class="sticky top-0 z-30 px-5 pt-5 pb-3 bg-gradient-to-b from-[#F7F5F2] to-[#F7F5F2]/75 backdrop-blur-sm">
    <div class="bg-white/90 border border-[#E8E6E1] rounded-2xl shadow-sm px-4 py-3 flex items-center gap-3">
        <div class="flex items-center gap-2 min-w-0">
            <div class="w-8 h-8 rounded-xl bg-[#F5F4F1] border border-[#E8E6E1] text-[#666] flex items-center justify-center flex-shrink-0">
                <x-heroicon-o-home class="w-4 h-4" />
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-1.5 text-[11px] text-[#aaa] leading-none">
                    <span>HRCore</span>
                    <span class="text-[#ddd]">/</span>
                    <span class="text-gray-900 font-medium truncate">@yield('breadcrumb', 'Dashboard')</span>
                </div>
                <div class="text-[10px] text-[#b8b5ae] mt-1">{{ now()->format('l, d M Y') }}</div>
            </div>
        </div>

        <div class="ml-auto flex items-center gap-2">
            <label class="hidden md:flex items-center gap-2 bg-[#F5F4F1] border border-[#E8E6E1] rounded-xl px-3 h-9 w-[260px]">
                <x-heroicon-o-magnifying-glass class="w-4 h-4 text-[#aaa]" />
                <input placeholder="Search employee, payroll..." class="border-none bg-transparent text-[12px] outline-none w-full placeholder:text-[#999]" />
            </label>

            <button class="btn-action-soft hidden md:inline-flex">Filter</button>

            <a href="{{ route('payroll.create') }}" class="btn-action-primary whitespace-nowrap">
                <x-heroicon-o-bolt class="w-4 h-4" />
                <span class="hidden sm:inline">Generate Payroll</span>
                <span class="sm:hidden">Generate</span>
            </a>
        </div>
    </div>
</header>
