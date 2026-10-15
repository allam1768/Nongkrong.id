{{-- Admin Solid Navy Sidebar --}}
<aside class="w-full lg:w-64 bg-[#0F172A] text-slate-300 flex-shrink-0 flex flex-col justify-between border-r-2 border-slate-800">
    <div class="p-5">
        {{-- Brand Header --}}
        <div class="flex items-center gap-3 pb-6 border-b border-slate-800">
            <div class="w-10 h-10 rounded-2xl bg-brand-blue flex items-center justify-center text-white font-black text-lg">
                N
            </div>
            <div>
                <h1 class="text-white font-black text-base leading-tight">NONGKRONG.ID</h1>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Admin Operations</span>
            </div>
        </div>

        {{-- Nav Links --}}
        <nav class="mt-6 space-y-1.5 text-xs font-bold">
            <a
                href="{{ route('admin.dashboard') }}"
                wire:navigate
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-brand-blue text-white shadow-sm' : 'hover:bg-slate-800/80 text-slate-300' }}"
            >
                <span class="text-base">📊</span>
                <span>Executive Dashboard</span>
            </a>

            <a
                href="{{ route('admin.menus') }}"
                wire:navigate
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.menus') ? 'bg-brand-blue text-white shadow-sm' : 'hover:bg-slate-800/80 text-slate-300' }}"
            >
                <span class="text-base">☕</span>
                <span>Manajemen Menu</span>
            </a>

            <a
                href="{{ route('admin.ai-config') }}"
                wire:navigate
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.ai-config') ? 'bg-brand-blue text-white shadow-sm' : 'hover:bg-slate-800/80 text-slate-300' }}"
            >
                <span class="text-base">🤖</span>
                <span>AI Barista Tuner</span>
            </a>

            <div class="pt-4 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3">
                Operasional Staf
            </div>

            <a
                href="{{ route('staff.kitchen-board') }}"
                wire:navigate
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800/80 text-slate-300 transition"
            >
                <span class="text-base">📋</span>
                <span>Kitchen Kanban KDS</span>
            </a>

            <a
                href="{{ route('staff.cashier-register') }}"
                wire:navigate
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800/80 text-slate-300 transition"
            >
                <span class="text-base">🖥️</span>
                <span>Kasir Walk-in POS</span>
            </a>
        </nav>
    </div>

    {{-- Bottom User Card & Portal Back --}}
    <div class="p-5 border-t border-slate-800 space-y-3">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-slate-800 text-white flex items-center justify-center font-bold text-xs">
                AD
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-xs font-bold text-white truncate">Administrator</div>
                <div class="text-[10px] text-emerald-400 font-semibold">● Aktif</div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button
                type="submit"
                class="w-full py-2.5 bg-rose-950/40 hover:bg-rose-900/60 border border-rose-800/40 text-rose-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer shadow-xs"
                title="Logout Resmi Admin"
            >
                <span>🚪 Logout Resmi</span>
            </button>
        </form>
    </div>
</aside>
