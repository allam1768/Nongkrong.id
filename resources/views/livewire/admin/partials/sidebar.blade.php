{{-- Admin Responsive Light Sidebar --}}
@php
    $isNavActive = function (string $routeName, string $componentClass) {
        if (isset($this) && $this instanceof \Livewire\Component) {
            return $this instanceof $componentClass;
        }
        return request()->routeIs($routeName);
    };
@endphp
<div
    x-data="{
        mobileOpen: false
    }"
    x-init="
        $watch('mobileOpen', val => document.body.classList.toggle('overflow-hidden', val));
    "
    @keydown.window.escape="mobileOpen = false"
    wire:ignore.self
    class="shrink-0"
>
    {{-- Top Bar (Visible on mobile ONLY, desktop uses permanent sidebar) --}}
    <header class="lg:hidden sticky top-0 z-30 bg-white border-b border-slate-200 px-4 sm:px-6 py-3 flex items-center justify-between shadow-2xs">
        <div class="flex items-center gap-3 min-w-0">
            <button
                type="button"
                @click="mobileOpen = true"
                class="p-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 transition flex items-center justify-center cursor-pointer shadow-2xs active:scale-95 shrink-0"
                aria-label="Buka Menu"
                title="Buka Menu Navigasi"
            >
                <x-icon name="menu" class="w-5 h-5 text-brand-blue" />
            </button>

            <div class="flex items-center gap-2.5 min-w-0">
                <img
                    src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                    alt="Nongkrong.id"
                    class="h-6 sm:h-7 w-auto object-contain shrink-0 select-none"
                />
                <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider truncate block self-center">Executive Suite</span>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-600">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Okupansi: <strong class="font-mono text-slate-900">{{ $occupiedCount }}/{{ $totalTables }}</strong> ({{ $occupancyPct }}%)</span>
            </div>
            <a
                href="{{ route('customer.order') }}"
                target="_blank"
                class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs"
            >
                <x-icon name="smartphone" class="w-3.5 h-3.5 text-slate-500" />
                <span class="hidden md:inline">Katalog Tamu</span>
            </a>
        </div>
    </header>

    {{-- Backdrop for Mobile (< lg only) --}}
    <div
        x-show="mobileOpen"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileOpen = false"
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-40 lg:hidden"
        x-cloak
    ></div>

    {{-- Main Sidebar Drawer (Mobile) / Permanent Panel (Desktop) --}}
    <aside
        class="bg-white text-slate-800 flex flex-col justify-between border-r border-slate-200 h-dvh overflow-y-auto overflow-x-hidden no-scrollbar transition-transform duration-300 ease-in-out fixed inset-y-0 left-0 z-50 w-72 sm:w-80 max-w-[calc(100vw-3rem)] lg:static lg:z-auto lg:h-screen lg:sticky lg:top-0 lg:w-72 lg:max-w-none lg:translate-x-0 lg:flex"
        :class="{
            'translate-x-0 shadow-2xl shadow-slate-900/20': mobileOpen,
            '-translate-x-full': !mobileOpen
        }"
    >
        <div class="p-4 sm:p-5 flex-1 flex flex-col w-full min-w-0">
            {{-- Brand Header (Mobile Close Button Only) --}}
            <div class="flex items-center justify-between pb-4 sm:pb-5 border-b border-slate-200 gap-2">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="min-w-0">
                        <img
                            src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                            alt="Nongkrong.id"
                            class="h-7 sm:h-8 w-auto object-contain select-none"
                        />
                        <div class="mt-1 flex items-center gap-1.5">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-brand-blue shrink-0"></span>
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider truncate">Executive Suite</span>
                        </div>
                    </div>
                </div>

                {{-- Close Button on Mobile Drawer (< lg) --}}
                <button
                    type="button"
                    @click="mobileOpen = false"
                    class="lg:hidden p-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 transition flex items-center justify-center cursor-pointer active:scale-95 shrink-0"
                    aria-label="Tutup Menu Mobile"
                    title="Tutup Menu"
                >
                    <x-icon name="x" class="w-4 h-4" />
                </button>
            </div>


            {{-- Nav Section: Eksekutif & Analisis --}}
            <div class="mt-5">
                <div class="px-3 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Manajemen Inti
                </div>
                <nav class="space-y-1 text-xs font-bold">
                    {{-- Executive Dashboard --}}
                    @php $dashActive = $isNavActive('admin.dashboard', \App\Livewire\Admin\AdminAnalytics::class); @endphp
                    <a
                        href="{{ route('admin.dashboard') }}"
                        wire:navigate
                        @click="mobileOpen = false"
                        class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl transition border {{ $dashActive ? 'bg-brand-blue text-white border-blue-600 shadow-xs' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <x-icon name="trending-up" class="w-4 h-4 shrink-0 {{ $dashActive ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" />
                            <span class="truncate">Executive Dashboard</span>
                        </div>
                        @if ($dashActive)
                            <span class="w-1.5 h-1.5 rounded-full bg-white shrink-0"></span>
                        @endif
                    </a>

                    {{-- Manajemen Menu --}}
                    @php $menusActive = $isNavActive('admin.menus', \App\Livewire\Admin\AdminMenu::class); @endphp
                    <a
                        href="{{ route('admin.menus') }}"
                        wire:navigate
                        @click="mobileOpen = false"
                        class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl transition border {{ $menusActive ? 'bg-brand-blue text-white border-blue-600 shadow-xs' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <x-icon name="coffee" class="w-4 h-4 shrink-0 {{ $menusActive ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" />
                            <span class="truncate">Manajemen Menu</span>
                        </div>
                    </a>

                    {{-- Manajemen Meja & QR --}}
                    @php $tablesActive = $isNavActive('admin.tables', \App\Livewire\Admin\AdminTable::class); @endphp
                    <a
                        href="{{ route('admin.tables') }}"
                        wire:navigate
                        @click="mobileOpen = false"
                        class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl transition border {{ $tablesActive ? 'bg-brand-blue text-white border-blue-600 shadow-xs' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <x-icon name="qris" class="w-4 h-4 shrink-0 {{ $tablesActive ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" />
                            <span class="truncate">Meja & QR Code</span>
                        </div>
                    </a>

                    {{-- AI Barista Tuner --}}
                    @php $aiActive = $isNavActive('admin.ai-config', \App\Livewire\Admin\AdminAiConfig::class); @endphp
                    <a
                        href="{{ route('admin.ai-config') }}"
                        wire:navigate
                        @click="mobileOpen = false"
                        class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl transition border {{ $aiActive ? 'bg-brand-blue text-white border-blue-600 shadow-xs' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <x-icon name="sparkles" class="w-4 h-4 shrink-0 {{ $aiActive ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" />
                            <span class="truncate">AI Barista Tuner</span>
                        </div>
                    </a>

                    {{-- Kelola Akun Karyawan --}}
                    @php $usersActive = $isNavActive('admin.users', \App\Livewire\Admin\AdminUser::class); @endphp
                    <a
                        href="{{ route('admin.users') }}"
                        wire:navigate
                        @click="mobileOpen = false"
                        class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl transition border {{ $usersActive ? 'bg-brand-blue text-white border-blue-600 shadow-xs' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <x-icon name="users" class="w-4 h-4 shrink-0 {{ $usersActive ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" />
                            <span class="truncate">Kelola Karyawan</span>
                        </div>
                    </a>
                </nav>
            </div>

            {{-- Nav Section: Operasional Staf & Dapur --}}
            <div class="mt-5">
                <div class="px-3 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Operasional Staf
                </div>
                <nav class="space-y-1 text-xs font-bold">
                    {{-- Kitchen Kanban KDS --}}
                    <a
                        href="{{ route('staff.kitchen-board') }}"
                        wire:navigate
                        @click="mobileOpen = false"
                        class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl transition border border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <x-icon name="kanban" class="w-4 h-4 shrink-0 text-slate-400 group-hover:text-slate-700" />
                            <span class="truncate">Kitchen Kanban KDS</span>
                        </div>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-700 shrink-0" />
                    </a>

                    {{-- Kasir Walk-in POS --}}
                    <a
                        href="{{ route('staff.cashier-tables') }}"
                        wire:navigate
                        @click="mobileOpen = false"
                        class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl transition border border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <x-icon name="monitor" class="w-4 h-4 shrink-0 text-slate-400 group-hover:text-slate-700" />
                            <span class="truncate">Kasir Walk-in POS</span>
                        </div>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-700 shrink-0" />
                    </a>

                    {{-- Tim Kebersihan --}}
                    <a
                        href="{{ route('staff.cleaning-board') }}"
                        wire:navigate
                        @click="mobileOpen = false"
                        class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl transition border border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <x-icon name="brush" class="w-4 h-4 shrink-0 text-slate-400 group-hover:text-slate-700" />
                            <span class="truncate">Tim Kebersihan</span>
                        </div>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-700 shrink-0" />
                    </a>
                </nav>
            </div>

            {{-- Nav Section: Pratinjau Pelanggan --}}
            <div class="mt-5">
                <div class="px-3 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Pratinjau Pelanggan
                </div>
                <nav class="space-y-1 text-xs font-bold">
                    <a
                        href="{{ route('customer.order') }}"
                        target="_blank"
                        @click="mobileOpen = false"
                        class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl transition border border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <x-icon name="smartphone" class="w-4 h-4 shrink-0 text-slate-400 group-hover:text-slate-700" />
                            <span class="truncate">Katalog Tamu (Meja)</span>
                        </div>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-700 shrink-0" />
                    </a>
                </nav>
            </div>
        </div>

        {{-- Bottom User Profile Card & Logout (Pinned to bottom) --}}
        <div class="p-4 sm:p-5 border-t border-slate-200 space-y-3 bg-white shrink-0 w-full min-w-0 pb-6 sm:pb-5">
            {{-- Profile --}}
            <div class="flex items-center gap-3 p-2.5 rounded-2xl bg-slate-50 border border-slate-200 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'AL', 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-xs font-black text-slate-900 truncate">{{ ucfirst(auth()->user()->role ?? 'Administrator') }}</div>
                    <div class="text-[11px] text-slate-500 font-medium truncate">{{ auth()->user()->name ?? 'Allam' }}</div>
                </div>
                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" title="Status Aktif"></span>
            </div>

            {{-- Logout Button with Confirmation --}}
            <button
                type="button"
                wire:click="confirmLogout"
                class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer shadow-2xs active:scale-98"
                title="Keluar dari akun admin"
            >
                <x-icon name="logout" class="w-4 h-4 shrink-0 text-slate-500" />
                <span>Logout Akun</span>
            </button>
        </div>
    </aside>

    {{-- Logout Confirmation Modal --}}
    @if ($showLogoutModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-150">
            <div class="bg-white rounded-3xl border border-slate-200 max-w-sm w-full p-6 shadow-2xl space-y-4 animate-in zoom-in-95 duration-150 text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center mx-auto">
                    <x-icon name="logout" class="w-6 h-6 text-slate-700" />
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-black text-slate-900">Konfirmasi Logout</h3>
                    <p class="text-xs text-slate-500 font-medium">Apakah Anda yakin ingin keluar dari sesi Administrator?</p>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <button
                        type="button"
                        wire:click="cancelLogout"
                        class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        wire:click="logout"
                        class="flex-1 py-2.5 rounded-xl bg-slate-900 hover:bg-black text-white font-black text-xs transition shadow-2xs cursor-pointer active:scale-98"
                    >
                        Ya, Logout
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
