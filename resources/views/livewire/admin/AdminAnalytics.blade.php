<div class="min-h-screen bg-[#F8FAFC] flex flex-col lg:flex-row font-sans">
    {{-- Sidebar --}}
    @include('livewire.admin.partials.sidebar')

    {{-- Main Analytics Content --}}
    <main class="flex-1 p-5 sm:p-8 overflow-y-auto">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b-2 border-slate-200">
            <div>
                <span class="text-xs font-black text-brand-blue tracking-wide uppercase">Performance Overview</span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900">Executive Dashboard & Analytics</h1>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3.5 py-1.5 bg-white border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-600">
                    📅 {{ now()->translatedFormat('d F Y') }}
                </span>
                <a
                    href="{{ route('staff.kitchen-board') }}"
                    wire:navigate
                    class="px-4 py-2 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition shadow-xs"
                >
                    Lihat KDS Dapur ➔
                </a>
            </div>
        </div>

        {{-- 4 KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            {{-- KPI 1 --}}
            <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400">Total Omzet Lunas</span>
                    <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">💰</span>
                </div>
                <div class="text-xl sm:text-2xl font-black text-slate-900 font-mono">
                    {{ $formattedTotalRevenue }}
                </div>
                <div class="mt-2 text-[11px] text-emerald-600 font-bold flex items-center gap-1">
                    <span>↑ +18.4%</span>
                    <span class="text-slate-400 font-normal">vs minggu lalu</span>
                </div>
            </div>

            {{-- KPI 2 --}}
            <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400">Total Transaksi</span>
                    <span class="w-8 h-8 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center font-bold text-sm">🧾</span>
                </div>
                <div class="text-xl sm:text-2xl font-black text-slate-900 font-mono">
                    {{ $formattedTotalOrders }}
                </div>
                <div class="mt-2 text-[11px] text-blue-600 font-bold flex items-center gap-1">
                    <span>↑ +12 Pesanan</span>
                    <span class="text-slate-400 font-normal">hari ini</span>
                </div>
            </div>

            {{-- KPI 3 --}}
            <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400">Item Terjual</span>
                    <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">☕</span>
                </div>
                <div class="text-xl sm:text-2xl font-black text-slate-900 font-mono">
                    {{ $formattedTotalItemsSold }}
                </div>
                <div class="mt-2 text-[11px] text-purple-600 font-bold flex items-center gap-1">
                    <span>Rata-rata 2.4</span>
                    <span class="text-slate-400 font-normal">item / meja</span>
                </div>
            </div>

            {{-- KPI 4 --}}
            <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400">Konversi AI Barista</span>
                    <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">🤖</span>
                </div>
                <div class="text-xl sm:text-2xl font-black text-brand-blue font-mono">
                    {{ $aiConversionRate }}%
                </div>
                <div class="mt-2 text-[11px] text-emerald-600 font-bold flex items-center gap-1">
                    <span>Rekomendasi Sukses Masuk Cart</span>
                </div>
            </div>
        </div>

        {{-- Mid Section: Peak Hours & Top Selling --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">
            {{-- Peak Hours Bar Graph (7 cols) --}}
            <div class="lg:col-span-7 bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-black text-slate-900">Distribusi Jam Sibuk (Peak Hours)</h2>
                        <span class="text-xs text-slate-400 font-medium">Aktivitas pesanan kafe per 2 jam</span>
                    </div>
                    <span class="text-xs font-bold text-brand-blue bg-blue-50 px-3 py-1 rounded-full border border-blue-200">
                        Puncak: 16:00 - 20:00
                    </span>
                </div>

                <div class="pt-6 flex items-end justify-between gap-3 h-48 px-2">
                    @foreach ($hourlyDistribution as $bar)
                        <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end">
                            <span class="text-[10px] font-bold text-slate-500 font-mono">{{ $bar['orders'] }}</span>
                            <div class="w-full max-w-[36px] bg-slate-100 rounded-t-xl overflow-hidden flex flex-col justify-end h-[120px]">
                                <div
                                    class="bg-brand-blue hover:bg-[#003da1] transition-all rounded-t-xl @if ($bar['pct'] >= 80) h-5/6 @elseif ($bar['pct'] >= 50) h-3/5 @elseif ($bar['pct'] >= 30) h-2/5 @else h-1/5 @endif"
                                ></div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400">{{ $bar['hour'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Top 5 Selling Menus (5 cols) --}}
            <div class="lg:col-span-5 bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <h2 class="text-sm font-black text-slate-900">5 Menu Terlaris</h2>
                    <span class="text-xs text-slate-400 font-bold">Qty Terjual</span>
                </div>

                <div class="space-y-3">
                    @forelse ($topSelling as $index => $item)
                        <div class="flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-700 font-black flex items-center justify-center shrink-0">
                                    {{ $index + 1 }}
                                </span>
                                <div class="truncate">
                                    <div class="font-bold text-slate-900 truncate">{{ $item->menu?->name ?? 'Menu' }}</div>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $item->formatted_total_rev }}</span>
                                </div>
                            </div>
                            <span class="font-black text-brand-blue bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200 shrink-0 font-mono">
                                {{ $item->total_qty }}x
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400 font-medium">
                            Belum ada data penjualan tercatat.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Orders Table --}}
        <div class="bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-xs mt-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <div>
                    <h2 class="text-sm font-black text-slate-900">Transaksi Terbaru</h2>
                    <span class="text-xs text-slate-400 font-medium">Daftar pesanan live dari meja maupun walk-in</span>
                </div>
                <a
                    href="{{ route('staff.kitchen-board') }}"
                    wire:navigate
                    class="text-xs font-bold text-brand-blue hover:underline"
                >
                    Lihat Semua di KDS ➔
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold">
                            <th class="pb-3">Kode Order</th>
                            <th class="pb-3">Pembeli</th>
                            <th class="pb-3">Meja</th>
                            <th class="pb-3">Item</th>
                            <th class="pb-3">Total</th>
                            <th class="pb-3">Metode</th>
                            <th class="pb-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @forelse ($recentOrders as $order)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 font-mono font-black text-brand-blue">{{ $order->order_code }}</td>
                                <td class="py-3 font-bold text-slate-900">{{ $order->customer_name }}</td>
                                <td class="py-3">Meja {{ $order->table?->table_number ?? '01' }}</td>
                                <td class="py-3 text-slate-500">{{ $order->items->count() }} item</td>
                                <td class="py-3 font-mono font-bold">{{ $order->formatted_total_amount }}</td>
                                <td class="py-3 uppercase font-mono text-[11px]">{{ $order->payment_method }}</td>
                                <td class="py-3">
                                    @if ($order->order_status === 'completed')
                                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px]">Selesai</span>
                                    @elseif ($order->order_status === 'ready')
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full font-bold text-[10px]">Siap Saji</span>
                                    @elseif ($order->order_status === 'in_progress')
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full font-bold text-[10px]">Diramu</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-full font-bold text-[10px]">Menunggu</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-slate-400">Belum ada transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
