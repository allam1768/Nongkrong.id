<div wire:poll.3s class="min-h-screen bg-[#F1F5F9] pb-12 font-sans">
    {{-- Toast Notification --}}
    @include('livewire.reuse-component.Toast-notification')

    {{-- Top Bar --}}
    <header class="sticky top-0 z-40 bg-white border-b-2 border-slate-200 px-4 sm:px-8 py-3.5 shadow-xs">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('portal') }}"
                    wire:navigate
                    class="w-9 h-9 rounded-xl border-2 border-slate-200 bg-white flex items-center justify-center text-slate-700 hover:border-brand-blue hover:text-brand-blue transition font-black text-sm"
                    title="Ke Portal Karyawan"
                >
                    ←
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-black text-brand-blue tracking-wide">KITCHEN DISPLAY SYSTEM (KDS)</span>
                        @include('livewire.reuse-component.StatusBadge', [
                            'variant' => 'live',
                            'label' => 'Live Sync 3s',
                            'pulse' => true,
                        ])
                    </div>
                    <h1 class="text-base sm:text-lg font-black text-slate-900">Kanban Pesanan & Barista Bar</h1>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a
                    href="{{ route('staff.cashier-register') }}"
                    wire:navigate
                    class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs"
                >
                    <span>🖥️ Kasir POS Walk-in</span>
                </a>
                <a
                    href="{{ route('admin.dashboard') }}"
                    wire:navigate
                    class="px-4 py-2 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs"
                >
                    <span>📊 Admin Analytics</span>
                </a>
            </div>
        </div>
    </header>

    {{-- Kanban Board Grid --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 pt-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            {{-- Column 1: Pesanan Masuk (Pending) --}}
            <div class="flex flex-col rounded-3xl bg-slate-200/70 p-4 border-2 border-slate-300">
                <div class="flex items-center justify-between pb-3 border-b-2 border-slate-300 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <h2 class="text-xs font-black uppercase text-slate-800 tracking-wider">1. Masuk (Baru)</h2>
                    </div>
                    <span class="text-xs font-black px-2.5 py-0.5 bg-amber-100 text-amber-900 rounded-full border border-amber-300">
                        {{ $pendingOrders->count() }}
                    </span>
                </div>

                <div class="space-y-3 overflow-y-auto max-h-[calc(100vh-210px)] pr-1">
                    @forelse ($pendingOrders as $order)
                        <div class="bg-white rounded-2xl p-4 border-2 border-amber-200 shadow-xs space-y-3">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-mono text-xs font-black text-brand-blue">{{ $order->order_code }}</span>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 bg-slate-100 text-slate-700 rounded-md">
                                            Meja {{ $order->table?->table_number ?? '01' }}
                                        </span>
                                    </div>
                                    <h3 class="text-sm font-black text-slate-900 mt-0.5">{{ $order->customer_name }}</h3>
                                </div>
                                <span class="text-[11px] font-bold text-slate-400 font-mono">
                                    {{ $order->created_at?->diffForHumans(null, true) ?? 'baru' }}
                                </span>
                            </div>

                            {{-- Item List --}}
                            <div class="text-xs bg-slate-50 p-2.5 rounded-xl border border-slate-100 space-y-1.5">
                                @foreach ($order->items as $item)
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium text-slate-800">
                                            <strong class="text-brand-blue font-black">{{ $item->quantity }}x</strong> {{ $item->menu?->name }}
                                        </span>
                                    </div>
                                    @if ($item->item_notes)
                                        <div class="text-[10px] text-amber-700 italic pl-4">📝 {{ $item->item_notes }}</div>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Payment Badge --}}
                            <div class="flex items-center justify-between pt-1 text-xs">
                                <span class="text-slate-400 font-bold">Pembayaran:</span>
                                @if ($order->payment_status === 'paid')
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px]">
                                        ✓ LUNAS ({{ strtoupper($order->payment_method) }})
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full font-bold text-[10px]">
                                        ⏳ BELUM BAYAR ({{ strtoupper($order->payment_method) }})
                                    </span>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="space-y-1.5 pt-1">
                                @if ($order->payment_status !== 'paid')
                                    <button
                                        type="button"
                                        wire:click="confirmPayment({{ $order->id }})"
                                        class="w-full h-8 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer"
                                    >
                                        💵 Terima Tunai & Kirim Barista
                                    </button>
                                @else
                                    <button
                                        type="button"
                                        wire:click="sendToKitchen({{ $order->id }})"
                                        class="w-full h-8 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer"
                                    >
                                        Kirim ke Barista ➔
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400 font-medium">
                            Tidak ada pesanan masuk baru.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Column 2: Sedang Diramu (In Progress) --}}
            <div class="flex flex-col rounded-3xl bg-slate-200/70 p-4 border-2 border-slate-300">
                <div class="flex items-center justify-between pb-3 border-b-2 border-slate-300 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-blue-500 animate-pulse"></span>
                        <h2 class="text-xs font-black uppercase text-slate-800 tracking-wider">2. Sedang Diramu</h2>
                    </div>
                    <span class="text-xs font-black px-2.5 py-0.5 bg-blue-100 text-blue-900 rounded-full border border-blue-300">
                        {{ $inProgressOrders->count() }}
                    </span>
                </div>

                <div class="space-y-3 overflow-y-auto max-h-[calc(100vh-210px)] pr-1">
                    @forelse ($inProgressOrders as $order)
                        <div class="bg-white rounded-2xl p-4 border-2 border-blue-200 shadow-xs space-y-3">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-mono text-xs font-black text-brand-blue">{{ $order->order_code }}</span>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 bg-blue-50 text-brand-blue rounded-md border border-blue-200">
                                            Meja {{ $order->table?->table_number ?? '01' }}
                                        </span>
                                    </div>
                                    <h3 class="text-sm font-black text-slate-900 mt-0.5">{{ $order->customer_name }}</h3>
                                </div>
                                <span class="text-[11px] font-bold text-blue-600 font-mono">
                                    ☕ Diramu
                                </span>
                            </div>

                            {{-- Item List --}}
                            <div class="text-xs bg-slate-50 p-2.5 rounded-xl border border-slate-100 space-y-1.5">
                                @foreach ($order->items as $item)
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-slate-900">
                                            <strong class="text-brand-blue font-black">{{ $item->quantity }}x</strong> {{ $item->menu?->name }}
                                        </span>
                                    </div>
                                    @if ($item->item_notes)
                                        <div class="text-[10px] text-amber-700 italic pl-4">📝 {{ $item->item_notes }}</div>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Action --}}
                            <button
                                type="button"
                                wire:click="markReady({{ $order->id }})"
                                class="w-full h-9 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer"
                            >
                                ✓ Selesai Racik ➔ Meja Saji
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400 font-medium">
                            Barista sedang stand by.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Column 3: Siap Disajikan (Ready) --}}
            <div class="flex flex-col rounded-3xl bg-slate-200/70 p-4 border-2 border-slate-300">
                <div class="flex items-center justify-between pb-3 border-b-2 border-slate-300 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <h2 class="text-xs font-black uppercase text-slate-800 tracking-wider">3. Siap Saji</h2>
                    </div>
                    <span class="text-xs font-black px-2.5 py-0.5 bg-emerald-100 text-emerald-900 rounded-full border border-emerald-300">
                        {{ $readyOrders->count() }}
                    </span>
                </div>

                <div class="space-y-3 overflow-y-auto max-h-[calc(100vh-210px)] pr-1">
                    @forelse ($readyOrders as $order)
                        <div class="bg-white rounded-2xl p-4 border-2 border-emerald-200 shadow-xs space-y-3">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-mono text-xs font-black text-brand-blue">{{ $order->order_code }}</span>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 bg-emerald-50 text-emerald-700 rounded-md border border-emerald-200">
                                            Meja {{ $order->table?->table_number ?? '01' }}
                                        </span>
                                    </div>
                                    <h3 class="text-sm font-black text-slate-900 mt-0.5">{{ $order->customer_name }}</h3>
                                </div>
                                <span class="text-[11px] font-bold text-emerald-600 font-mono">
                                    🍽️ Siap Antar
                                </span>
                            </div>

                            <div class="text-xs bg-emerald-50/50 p-2.5 rounded-xl border border-emerald-100 space-y-1">
                                @foreach ($order->items as $item)
                                    <div class="text-slate-800 font-medium">
                                        {{ $item->quantity }}x {{ $item->menu?->name }}
                                    </div>
                                @endforeach
                            </div>

                            {{-- Action --}}
                            <button
                                type="button"
                                wire:click="markCompleted({{ $order->id }})"
                                class="w-full h-9 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer"
                            >
                                Antarkan ke Meja ➔ Selesai ✓
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400 font-medium">
                            Semua pesanan siap saji telah diantar.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Column 4: Selesai (Completed) --}}
            <div class="flex flex-col rounded-3xl bg-slate-200/70 p-4 border-2 border-slate-300">
                <div class="flex items-center justify-between pb-3 border-b-2 border-slate-300 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-slate-400"></span>
                        <h2 class="text-xs font-black uppercase text-slate-800 tracking-wider">4. Selesai (Arsip)</h2>
                    </div>
                    <span class="text-xs font-black px-2.5 py-0.5 bg-slate-100 text-slate-700 rounded-full border border-slate-300">
                        {{ $completedOrders->count() }}
                    </span>
                </div>

                <div class="space-y-3 overflow-y-auto max-h-[calc(100vh-210px)] pr-1">
                    @forelse ($completedOrders as $order)
                        <div class="bg-white/80 rounded-2xl p-3 border border-slate-200 shadow-xs space-y-1.5 opacity-80">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-xs font-bold text-slate-600">{{ $order->order_code }}</span>
                                <span class="text-[10px] font-bold text-slate-500">Meja {{ $order->table?->table_number ?? '01' }}</span>
                            </div>
                            <div class="text-xs font-bold text-slate-800">{{ $order->customer_name }}</div>
                            <div class="text-[11px] text-slate-400 flex items-center justify-between pt-1 border-t border-slate-100">
                                <span>{{ $order->items->count() }} item</span>
                                <span class="font-bold text-emerald-600">✓ Selesai</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400 font-medium">
                            Belum ada riwayat pesanan selesai.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</div>
