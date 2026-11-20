<div class="min-h-screen lg:h-screen lg:overflow-hidden bg-slate-50 flex flex-col lg:flex-row font-sans" wire:poll.10s>
    {{-- Sidebar --}}
    @include('livewire.admin.partials.sidebar')

    {{-- Main Analytics Content --}}
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto lg:h-screen">
        {{-- Top Header --}}
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-brand-blue text-white">
                        Executive Suite
                    </span>
                    <span class="text-xs text-slate-300 font-medium hidden sm:inline">|</span>
                    <span class="text-xs text-slate-500 font-medium hidden sm:inline">Pembaruan otomatis tiap 10 detik</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Executive Dashboard & Analytics</h1>
            </div>

            {{-- Period Filter & Quick Actions --}}
            <div class="flex flex-wrap items-center gap-2.5 w-full xl:w-auto">
                {{-- Period Toggle Pills --}}
                <div class="inline-flex p-1 bg-white border border-slate-200 rounded-2xl shadow-2xs overflow-x-auto max-w-full">
                    <button
                        type="button"
                        wire:click="setPeriod('today')"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition shrink-0 {{ $period === 'today' ? 'bg-brand-blue text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                    >
                        Hari Ini
                    </button>
                    <button
                        type="button"
                        wire:click="setPeriod('week')"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition shrink-0 {{ $period === 'week' ? 'bg-brand-blue text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                    >
                        7 Hari
                    </button>
                    <button
                        type="button"
                        wire:click="setPeriod('month')"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition shrink-0 {{ $period === 'month' ? 'bg-brand-blue text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                    >
                        30 Hari
                    </button>
                    <button
                        type="button"
                        wire:click="setPeriod('all')"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition shrink-0 {{ $period === 'all' ? 'bg-brand-blue text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                    >
                        Semua
                    </button>
                </div>

                {{-- Date Indicator --}}
                <span class="px-3.5 py-2 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 flex items-center gap-2 shadow-2xs">
                    <x-icon name="calendar" class="w-3.5 h-3.5 text-slate-500" />
                    <span>{{ now()->translatedFormat('d F Y') }}</span>
                </span>
            </div>
        </div>

        {{-- Operational Pulse Summary (Cafe Floor Status) --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
            <div class="bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-900 border border-slate-200 flex items-center justify-center font-mono font-black text-sm shrink-0">
                    {{ $pulse['pending'] }}
                </span>
                <div class="min-w-0">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Antrean Masuk</div>
                    <div class="text-xs font-black text-slate-800">Menunggu Diproses</div>
                </div>
            </div>

            <div class="bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-900 border border-slate-200 flex items-center justify-center font-mono font-black text-sm shrink-0">
                    {{ $pulse['cooking'] }}
                </span>
                <div class="min-w-0">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sedang Diracik</div>
                    <div class="text-xs font-black text-slate-800">Barista & Kitchen</div>
                </div>
            </div>

            <div class="bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-900 border border-slate-200 flex items-center justify-center font-mono font-black text-sm shrink-0">
                    {{ $pulse['ready'] }}
                </span>
                <div class="min-w-0">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Siap Disajikan</div>
                    <div class="text-xs font-black text-slate-800">Menuju Meja Tamu</div>
                </div>
            </div>

            <div class="bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-900 border border-slate-200 flex items-center justify-center font-mono font-black text-sm shrink-0">
                    {{ $pulse['unpaid'] }}
                </span>
                <div class="min-w-0">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Belum Dibayar</div>
                    <div class="text-xs font-black text-slate-800">Menunggu Pembayaran</div>
                </div>
            </div>
        </div>

        {{-- 4 Main KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            {{-- KPI 1: Omzet --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs hover:border-slate-300 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Omzet Lunas</span>
                    <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center">
                        <x-icon name="banknote" class="w-4 h-4 text-slate-700" />
                    </span>
                </div>
                <div class="text-2xl font-black text-slate-900 font-mono tracking-tight">
                    {{ $formattedTotalRevenue }}
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="font-bold text-slate-600 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-900"></span>
                        <span>Transaksi Lunas Terverifikasi</span>
                    </span>
                </div>
            </div>

            {{-- KPI 2: Total Orders --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs hover:border-slate-300 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Transaksi</span>
                    <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center">
                        <x-icon name="receipt" class="w-4 h-4 text-slate-700" />
                    </span>
                </div>
                <div class="text-2xl font-black text-slate-900 font-mono tracking-tight">
                    {{ $formattedTotalOrders }} <span class="text-sm font-bold text-slate-400">Pesanan</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="font-bold text-slate-600 flex items-center gap-1">
                        <span>{{ $pulse['completed'] }} Pesanan Selesai</span>
                    </span>
                    <span class="text-slate-400">Total Periode</span>
                </div>
            </div>

            {{-- KPI 3: Items Sold --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs hover:border-slate-300 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Produk Terjual</span>
                    <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center">
                        <x-icon name="coffee" class="w-4 h-4 text-slate-700" />
                    </span>
                </div>
                <div class="text-2xl font-black text-slate-900 font-mono tracking-tight">
                    {{ $formattedTotalItemsSold }} <span class="text-sm font-bold text-slate-400">Item</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="font-bold text-slate-600">
                        {{ $totalOrders > 0 ? number_format($totalItemsSold / $totalOrders, 1) : '0' }} item / transaksi
                    </span>
                    <span class="text-slate-400">Basket Size</span>
                </div>
            </div>

            {{-- KPI 4: AI Barista Conversion --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs hover:border-slate-300 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Konversi AI Barista</span>
                    <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center">
                        <x-icon name="sparkles" class="w-4 h-4 text-slate-700" />
                    </span>
                </div>
                <div class="text-2xl font-black text-slate-900 font-mono tracking-tight">
                    {{ $aiConversionRate }}%
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="font-bold text-slate-600 flex items-center gap-1">
                        <span>Rekomendasi Sukses di Cart</span>
                    </span>
                    <span class="text-slate-400">Smart Sales</span>
                </div>
            </div>
        </div>

        {{-- Mid Section: Peak Hours & Top Selling Menus --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">
            {{-- Peak Hours Bar Graph (7 cols) --}}
            <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-2xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-black text-slate-900">Distribusi Jam Sibuk (Peak Hours)</h2>
                        <span class="text-xs text-slate-500 font-medium">Kepadatan pesanan kafe sepanjang jam operasional</span>
                    </div>
                    <span class="text-xs font-bold text-slate-700 bg-slate-100 px-3 py-1 rounded-xl border border-slate-200 self-start sm:self-auto">
                        {{ $peakHoursText }}
                    </span>
                </div>

                {{-- Chart Container with True Proportional Heights --}}
                <div class="pt-6">
                    <div class="flex items-end justify-between gap-3 h-48 px-2 border-b-2 border-slate-100 pb-2">
                        @foreach ($hourlyDistribution as $bar)
                            <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group">
                                <span class="text-[11px] font-mono font-bold {{ $bar['is_peak'] ? 'text-slate-900 font-black' : 'text-slate-500' }}">
                                    {{ $bar['orders'] }}
                                </span>
                                <div class="w-full max-w-[40px] bg-slate-100 rounded-t-xl overflow-hidden flex flex-col justify-end h-[130px] border border-slate-200">
                                    <div
                                        style="height: {{ $bar['pct'] }}%;"
                                        class="transition-all rounded-t-lg {{ $bar['is_peak'] ? 'bg-brand-blue' : 'bg-slate-300 group-hover:bg-slate-400' }}"
                                    ></div>
                                </div>
                                <span class="text-[11px] font-bold text-slate-500 mt-1">{{ $bar['hour'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 flex items-center justify-between text-xs text-slate-400">
                        <span>Buka 10:00 WIB</span>
                        <span class="flex items-center gap-1.5 font-medium text-slate-600">
                            <span class="w-2.5 h-2.5 rounded-sm bg-brand-blue inline-block"></span>
                            Jam Sibuk Utama (Peak)
                        </span>
                        <span>Tutup 23:00 WIB</span>
                    </div>
                </div>
            </div>

            {{-- 5 Menu Terlaris Leaderboard (5 cols) --}}
            <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <div>
                            <h2 class="text-base font-black text-slate-900">5 Menu Terlaris</h2>
                            <span class="text-xs text-slate-500 font-medium">Leaderboard menu dengan penjualan tertinggi</span>
                        </div>
                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                            Terjual
                        </span>
                    </div>

                    <div class="space-y-3.5">
                        @forelse ($topSelling as $index => $item)
                            <div class="p-3 rounded-2xl border border-slate-200/80 hover:border-slate-300 transition bg-slate-50/50">
                                <div class="flex items-center justify-between gap-3 text-xs mb-1.5">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <span class="w-6 h-6 rounded-lg {{ $index === 0 ? 'bg-brand-blue text-white' : 'bg-slate-100 text-slate-700 border border-slate-200' }} font-black text-[11px] flex items-center justify-center shrink-0">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="min-w-0 truncate">
                                            <div class="font-black text-slate-900 truncate text-xs">{{ $item->menu?->name ?? 'Menu' }}</div>
                                            <span class="text-[10px] text-slate-400 font-medium">
                                                {{ $item->menu?->category?->name ?? 'Kategori' }} &bull; <span class="font-mono text-slate-600 font-bold">{{ $item->formatted_total_rev }}</span>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="font-bold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-xl border border-slate-200 shrink-0 font-mono text-xs">
                                        {{ $item->total_qty }}x
                                    </span>
                                </div>
                                {{-- Mini Progress Share Bar --}}
                                <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-brand-blue h-1.5 rounded-full" style="width: {{ $item->bar_pct }}%;"></div>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-xs text-slate-400 font-medium">
                                Belum ada data penjualan tercatat pada periode ini.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-4">
                    <a
                        href="{{ route('admin.menus') }}"
                        wire:navigate
                        class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5"
                    >
                        <span>Kelola Seluruh Menu & Stok</span>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5 text-slate-600" />
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent Orders Table with Live Search & Status Tabs --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-2xs mt-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-4">
                <div>
                    <h2 class="text-base font-black text-slate-900">Transaksi Live Terkini</h2>
                    <span class="text-xs text-slate-500 font-medium">Pantau pesanan real-time dari seluruh meja dan kasir</span>
                </div>

                {{-- Status Filter Pills & Search --}}
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full lg:w-auto">
                    <div class="relative w-full sm:w-64">
                        <x-icon name="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Cari order, pemesan, meja..."
                            class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 focus:border-brand-blue focus:bg-white rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-hidden transition"
                        />
                    </div>

                    <div class="inline-flex p-0.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold overflow-x-auto max-w-full">
                        <button
                            type="button"
                            wire:click="setStatusFilter('all')"
                            class="px-2.5 py-1 rounded-lg transition shrink-0 {{ $statusFilter === 'all' ? 'bg-brand-blue text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            Semua
                        </button>
                        <button
                            type="button"
                            wire:click="setStatusFilter('pending')"
                            class="px-2.5 py-1 rounded-lg transition shrink-0 {{ $statusFilter === 'pending' ? 'bg-brand-blue text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            Menunggu
                        </button>
                        <button
                            type="button"
                            wire:click="setStatusFilter('in_progress')"
                            class="px-2.5 py-1 rounded-lg transition shrink-0 {{ $statusFilter === 'in_progress' ? 'bg-brand-blue text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            Diracik
                        </button>
                        <button
                            type="button"
                            wire:click="setStatusFilter('ready')"
                            class="px-2.5 py-1 rounded-lg transition shrink-0 {{ $statusFilter === 'ready' ? 'bg-brand-blue text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            Siap
                        </button>
                        <button
                            type="button"
                            wire:click="setStatusFilter('completed')"
                            class="px-2.5 py-1 rounded-lg transition shrink-0 {{ $statusFilter === 'completed' ? 'bg-brand-blue text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            Selesai
                        </button>
                        <button
                            type="button"
                            wire:click="setStatusFilter('unpaid')"
                            class="px-2.5 py-1 rounded-lg transition shrink-0 {{ $statusFilter === 'unpaid' ? 'bg-brand-blue text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            Belum Bayar
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto -mx-6 sm:mx-0 px-6 sm:px-0">
                <table class="w-full text-left text-xs min-w-[760px]">
                    <thead>
                        <tr class="border-b-2 border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-3">Kode Order</th>
                            <th class="py-3 px-3">Waktu</th>
                            <th class="py-3 px-3">Pembeli & Meja</th>
                            <th class="py-3 px-3">Rincian Menu</th>
                            <th class="py-3 px-3">Total Tagihan</th>
                            <th class="py-3 px-3">Pembayaran</th>
                            <th class="py-3 px-3">Status Dapur</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @forelse ($recentOrders as $order)
                            <tr class="hover:bg-slate-50/80 transition">
                                {{-- Kode Order --}}
                                <td class="py-3.5 px-3 font-mono font-black text-brand-blue">
                                    <a
                                        href="{{ route('order.tracking', ['code' => $order->order_code]) }}"
                                        target="_blank"
                                        class="hover:underline flex items-center gap-1"
                                        title="Buka Live Tracking Tamu"
                                    >
                                        <span>{{ $order->order_code }}</span>
                                        <x-icon name="arrow-right" class="w-3 h-3 opacity-60" />
                                    </a>
                                </td>

                                {{-- Waktu --}}
                                <td class="py-3.5 px-3 text-slate-500 font-mono text-[11px]">
                                    {{ $order->created_at ? $order->created_at->format('H:i') : '-' }} WIB
                                </td>

                                {{-- Pembeli & Meja --}}
                                <td class="py-3.5 px-3">
                                    <div class="font-bold text-slate-900">{{ $order->customer_name }}</div>
                                    <span class="inline-block mt-0.5 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-bold text-[10px]">
                                        Meja {{ $order->table?->table_number ?? '01' }}
                                    </span>
                                </td>

                                {{-- Rincian Item --}}
                                <td class="py-3.5 px-3 text-slate-600">
                                    <span class="font-bold text-slate-800">{{ $order->items->sum('quantity') }} item</span>
                                    <div class="text-[10px] text-slate-400 truncate max-w-[180px]">
                                        {{ $order->items->map(fn($it) => ($it->quantity > 1 ? $it->quantity.'x ' : '').($it->menu?->name ?? 'Menu'))->join(', ') }}
                                    </div>
                                </td>

                                {{-- Total --}}
                                <td class="py-3.5 px-3 font-mono font-bold text-slate-900">
                                    {{ $order->formatted_total_amount }}
                                </td>

                                {{-- Pembayaran --}}
                                <td class="py-3.5 px-3">
                                    <div class="flex flex-col gap-1">
                                        <span class="font-mono text-[10px] font-bold uppercase text-slate-700">
                                            {{ $order->payment_method }}
                                        </span>
                                        @if ($order->payment_status === 'paid')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md font-black text-[10px] w-fit">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Lunas
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-md font-black text-[10px] w-fit">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Status Dapur --}}
                                <td class="py-3.5 px-3">
                                    @if ($order->order_status === 'completed')
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg font-bold text-[10px]">
                                            Selesai
                                        </span>
                                    @elseif ($order->order_status === 'ready')
                                        <span class="px-2.5 py-1 bg-blue-50 text-brand-blue border border-blue-200 rounded-lg font-bold text-[10px]">
                                            Siap Saji
                                        </span>
                                    @elseif ($order->order_status === 'in_progress')
                                        <span class="px-2.5 py-1 bg-blue-50 text-brand-blue border border-blue-200 rounded-lg font-bold text-[10px]">
                                            Diracik
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg font-bold text-[10px]">
                                            Menunggu
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi Cepat --}}
                                <td class="py-3.5 px-3 text-right">
                                    <a
                                        href="{{ route('staff.kitchen-board') }}"
                                        wire:navigate
                                        class="px-2.5 py-1.5 bg-slate-100 hover:bg-brand-blue hover:text-white text-slate-700 rounded-lg text-[11px] font-bold transition inline-flex items-center gap-1 cursor-pointer"
                                    >
                                        <span>Proses</span>
                                        <x-icon name="arrow-right" class="w-3 h-3 text-current" />
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-10 text-center text-slate-400 font-medium">
                                    Tidak ada transaksi yang cocok dengan kriteria pencarian atau filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
