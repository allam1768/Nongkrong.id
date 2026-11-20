<div wire:poll.10s class="h-screen flex flex-col bg-[#F8FAFC] font-sans overflow-hidden text-slate-800">
    {{-- Notification Toast (Top Right - Unified Single Toast) --}}
    @if ($toastMessage)
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => { show = false; $wire.dismissToast(); }, 3500)"
            x-show="show"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="fixed top-4 right-4 z-50 flex items-center gap-2.5 px-4 py-3 bg-slate-900 text-white text-xs font-bold rounded-2xl shadow-xl border border-slate-800"
        >
            <span class="w-2 h-2 rounded-full bg-brand-blue shrink-0"></span>
            <span>{{ $toastMessage }}</span>
            <button type="button" wire:click="dismissToast" class="ml-2 text-slate-400 hover:text-white cursor-pointer font-black" aria-label="Tutup notifikasi">✕</button>
        </div>
    @endif

    {{-- Top KDS Header --}}
    <header class="shrink-0 h-16 bg-white border-b border-slate-200 px-4 sm:px-6 shadow-2xs z-30">
        <div class="max-w-[1720px] h-full mx-auto flex items-center justify-between gap-4">
            {{-- Left: Logo, Module Title & Live Indicator --}}
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('portal') }}" wire:navigate class="shrink-0 focus:outline-none">
                    <img
                        src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                        alt="Nongkrong.id"
                        class="h-7 w-auto object-contain shrink-0 select-none"
                    />
                </a>
                <span class="w-px h-5 bg-slate-200 shrink-0"></span>
                <div class="flex items-center gap-2 min-w-0">
                    <h1 class="text-xs sm:text-sm font-black text-slate-900 tracking-tight whitespace-nowrap">
                        Kanban Pesanan
                    </h1>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-slate-600 text-[10px] font-bold shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Live 10s</span>
                    </span>
                </div>
            </div>

            {{-- Center: Segmented Filter Tabs --}}
            <div class="hidden sm:inline-flex p-1 rounded-xl bg-slate-100 border border-slate-200 text-xs font-bold shrink-0">
                <button
                    type="button"
                    wire:click="setFilter('all')"
                    class="px-3.5 py-1.5 rounded-lg transition cursor-pointer {{ $activeFilter === 'all' ? 'bg-brand-blue text-white shadow-xs font-black' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    Semua ({{ $allCount }})
                </button>
                <button
                    type="button"
                    wire:click="setFilter('dine-in')"
                    class="px-3.5 py-1.5 rounded-lg transition cursor-pointer {{ $activeFilter === 'dine-in' ? 'bg-brand-blue text-white shadow-xs font-black' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    Dine-In ({{ $dineInCount }})
                </button>
                <button
                    type="button"
                    wire:click="setFilter('takeaway')"
                    class="px-3.5 py-1.5 rounded-lg transition cursor-pointer {{ $activeFilter === 'takeaway' ? 'bg-brand-blue text-white shadow-xs font-black' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    Takeaway ({{ $takeawayCount }})
                </button>
            </div>

            {{-- Right: Queue Summary, Fullscreen Toggle & User Info --}}
            <div class="flex items-center gap-2.5 shrink-0">
                {{-- Queue Summary Pill --}}
                <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-xs font-bold text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-brand-blue"></span>
                    <span>Total Diracik: <strong class="text-slate-900 font-black">{{ $totalItemsInQueue }}</strong> porsi</span>
                </div>

                {{-- Fullscreen Toggle --}}
                <button
                    type="button"
                    x-data="{ isFull: false }"
                    @click="if (!document.fullscreenElement) { document.documentElement.requestFullscreen(); isFull = true; } else { document.exitFullscreen(); isFull = false; }"
                    class="h-9 px-3 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5 cursor-pointer shadow-2xs active:scale-95"
                    title="Toggle Layar Penuh"
                >
                    <x-icon name="monitor" class="w-3.5 h-3.5 text-slate-500" />
                    <span class="hidden xl:inline text-[11px]" x-text="isFull ? 'Kecilkan' : 'Layar Penuh'">Layar Penuh</span>
                </button>

                {{-- User Info --}}
                <div class="hidden md:flex flex-col text-right pl-1">
                    <span class="text-xs font-bold text-slate-900 leading-tight">{{ auth()->user()->name ?? 'Staf Dapur' }}</span>
                    <span class="text-[10px] text-slate-500 font-medium">Tim Dapur & Barista</span>
                </div>

                {{-- Logout Button with Confirmation --}}
                <button
                    type="button"
                    wire:click="confirmLogout"
                    class="h-9 px-3.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5 cursor-pointer shadow-2xs active:scale-95"
                    title="Logout Staf Dapur"
                >
                    <x-icon name="logout" class="w-3.5 h-3.5 text-slate-500" />
                    <span>Keluar</span>
                </button>
            </div>
        </div>
    </header>

    {{-- Kanban Board Grid --}}
    <main class="flex-1 max-w-[1720px] w-full mx-auto px-4 sm:px-6 py-4 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 h-full items-stretch min-h-0">
            {{-- Column 1: Pesanan Masuk (Pending) --}}
            <div class="flex flex-col h-full min-h-0 rounded-2xl bg-white border border-slate-200 p-3.5 sm:p-4 shadow-2xs overflow-hidden">
                {{-- Column Header --}}
                <div class="shrink-0 flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                        <div>
                            <h2 class="text-xs font-black uppercase text-slate-900 tracking-wider">
                                1. Antrean Masuk
                            </h2>
                            <p class="text-[10px] text-slate-400 font-medium">Menunggu mulai diracik</p>
                        </div>
                    </div>
                    <span class="text-xs font-black px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-700 border border-slate-200 font-mono">
                        {{ $pendingOrders->count() }}
                    </span>
                </div>

                {{-- Column Cards --}}
                <div class="flex-1 min-h-0 space-y-3 overflow-y-auto pr-1">
                    @forelse ($pendingOrders as $order)
                        <div class="bg-white rounded-xl p-3.5 border border-slate-200 hover:border-slate-300 shadow-2xs space-y-3 transition">
                            {{-- Header Meta: Table / Takeaway & Time --}}
                            <div class="pb-2.5 border-b border-slate-100">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if ($order->table)
                                            <span class="px-2.5 py-0.5 rounded-md bg-slate-900 text-white font-mono font-black text-xs tracking-wide">
                                                MEJA {{ $order->table->table_number }}
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-800 border border-slate-300 font-mono font-bold text-xs tracking-wide">
                                                TAKEAWAY
                                            </span>
                                        @endif
                                        <span class="font-mono text-xs font-bold text-slate-500">
                                            {{ $order->order_code }}
                                        </span>
                                    </div>
                                    <span class="text-[11px] font-semibold text-slate-500 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-md flex items-center gap-1 font-mono shrink-0">
                                        <x-icon name="clock" class="w-3 h-3 text-slate-400" />
                                        <span>{{ $order->created_at?->diffForHumans(null, true) ?? 'baru' }}</span>
                                    </span>
                                </div>
                                <div class="text-xs text-slate-500 font-medium mt-1.5 flex items-center justify-between">
                                    <span>Pemesan: <strong class="text-slate-900 capitalize font-bold">{{ $order->customer_name }}</strong></span>
                                    <span class="text-[11px] font-bold text-slate-400 font-mono">{{ $order->items->sum('quantity') }} porsi</span>
                                </div>
                            </div>

                            {{-- Item List (Clean, Ticket Style) --}}
                            <div class="space-y-2">
                                @foreach ($order->items as $item)
                                    <div class="flex items-start gap-2.5">
                                        <span class="inline-flex items-center justify-center min-w-[26px] h-6 px-1.5 rounded-md bg-slate-900 text-white font-mono font-black text-xs shrink-0">
                                            {{ $item->quantity }}x
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <div class="font-bold text-slate-900 text-xs sm:text-sm leading-tight">
                                                {{ $item->menu?->name ?? 'Menu' }}
                                            </div>
                                            @if ($item->item_notes)
                                                <div class="mt-1 flex items-center gap-1.5 text-[11px]">
                                                    <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Catatan:</span>
                                                    <span class="font-bold text-slate-800 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded text-[11px]">
                                                        {{ $item->item_notes }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Payment Meta (if unpaid) --}}
                            @if ($order->payment_status !== 'paid')
                                <div class="pt-1 border-t border-slate-100 flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-medium">Pembayaran:</span>
                                    <span class="px-2 py-0.5 bg-amber-50 text-amber-800 border border-amber-200 rounded-md font-bold text-[10px] flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>BELUM BAYAR ({{ strtoupper($order->payment_method) }})</span>
                                    </span>
                                </div>
                            @endif

                            {{-- Action Button --}}
                            <div class="pt-1">
                                @if ($order->payment_status !== 'paid')
                                    <button
                                        type="button"
                                        wire:click="confirmPayment({{ $order->id }})"
                                        class="w-full h-10 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 shadow-2xs cursor-pointer active:scale-98"
                                    >
                                        <x-icon name="banknote" class="w-4 h-4 text-white" />
                                        <span>Terima Kasir & Kirim Barista</span>
                                    </button>
                                @else
                                    <button
                                        type="button"
                                        wire:click="sendToKitchen({{ $order->id }})"
                                        class="w-full h-10 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 shadow-2xs cursor-pointer active:scale-98"
                                    >
                                        <span>Mulai Racik Pesanan</span>
                                        <x-icon name="arrow-right" class="w-4 h-4 text-white" />
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 px-4 rounded-xl border border-dashed border-slate-200 bg-slate-50/50 space-y-1.5">
                            <div class="w-9 h-9 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <x-icon name="coffee" class="w-4 h-4" />
                            </div>
                            <div class="text-xs font-bold text-slate-700">Tidak ada antrean baru</div>
                            <div class="text-[11px] text-slate-400">Pesanan dari meja atau kasir akan muncul di sini.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Column 2: Sedang Diracik (In Progress) --}}
            <div class="flex flex-col h-full min-h-0 rounded-2xl bg-white border border-slate-200 p-3.5 sm:p-4 shadow-2xs overflow-hidden">
                {{-- Column Header --}}
                <div class="shrink-0 flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-blue animate-pulse"></span>
                        <div>
                            <h2 class="text-xs font-black uppercase text-slate-900 tracking-wider">
                                2. Sedang Diracik
                            </h2>
                            <p class="text-[10px] text-slate-400 font-medium">Proses pembuatan oleh Barista</p>
                        </div>
                    </div>
                    <span class="text-xs font-black px-2.5 py-0.5 rounded-lg bg-blue-50 text-brand-blue border border-blue-200 font-mono">
                        {{ $inProgressOrders->count() }}
                    </span>
                </div>

                {{-- Column Cards --}}
                <div class="flex-1 min-h-0 space-y-3 overflow-y-auto pr-1">
                    @forelse ($inProgressOrders as $order)
                        <div class="bg-white rounded-xl p-3.5 border border-slate-200 hover:border-slate-300 shadow-2xs space-y-3 transition">
                            {{-- Header Meta: Table / Takeaway & Status --}}
                            <div class="pb-2.5 border-b border-slate-100">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if ($order->table)
                                            <span class="px-2.5 py-0.5 rounded-md bg-slate-900 text-white font-mono font-black text-xs tracking-wide">
                                                MEJA {{ $order->table->table_number }}
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-800 border border-slate-300 font-mono font-bold text-xs tracking-wide">
                                                TAKEAWAY
                                            </span>
                                        @endif
                                        <span class="font-mono text-xs font-bold text-slate-500">
                                            {{ $order->order_code }}
                                        </span>
                                    </div>
                                    <span class="text-[11px] font-semibold text-brand-blue bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md flex items-center gap-1 shrink-0">
                                        <x-icon name="coffee" class="w-3 h-3 text-brand-blue" />
                                        <span>Meracik</span>
                                    </span>
                                </div>
                                <div class="text-xs text-slate-500 font-medium mt-1.5 flex items-center justify-between">
                                    <span>Pemesan: <strong class="text-slate-900 capitalize font-bold">{{ $order->customer_name }}</strong></span>
                                    <span class="text-[11px] font-bold text-slate-400 font-mono">{{ $order->items->sum('quantity') }} porsi</span>
                                </div>
                            </div>

                            {{-- Item List (Clean, Ticket Style) --}}
                            <div class="space-y-2">
                                @foreach ($order->items as $item)
                                    <div class="flex items-start gap-2.5">
                                        <span class="inline-flex items-center justify-center min-w-[26px] h-6 px-1.5 rounded-md bg-slate-900 text-white font-mono font-black text-xs shrink-0">
                                            {{ $item->quantity }}x
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <div class="font-bold text-slate-900 text-xs sm:text-sm leading-tight">
                                                {{ $item->menu?->name ?? 'Menu' }}
                                            </div>
                                            @if ($item->item_notes)
                                                <div class="mt-1 flex items-center gap-1.5 text-[11px]">
                                                    <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Catatan:</span>
                                                    <span class="font-bold text-slate-800 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded text-[11px]">
                                                        {{ $item->item_notes }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Action Button --}}
                            <div class="pt-1">
                                <button
                                    type="button"
                                    wire:click="markReady({{ $order->id }})"
                                    class="w-full h-10 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 shadow-2xs cursor-pointer active:scale-98"
                                >
                                    <x-icon name="check" class="w-4 h-4 text-white" />
                                    <span>Selesai Racik (Siap Saji)</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 px-4 rounded-xl border border-dashed border-slate-200 bg-slate-50/50 space-y-1.5">
                            <div class="w-9 h-9 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <x-icon name="coffee" class="w-4 h-4" />
                            </div>
                            <div class="text-xs font-bold text-slate-700">Barista sedang stand by</div>
                            <div class="text-[11px] text-slate-400">Tidak ada racikan pesanan yang sedang diproses.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Column 3: Siap Disajikan (Ready) --}}
            <div class="flex flex-col h-full min-h-0 rounded-2xl bg-white border border-slate-200 p-3.5 sm:p-4 shadow-2xs overflow-hidden">
                {{-- Column Header --}}
                <div class="shrink-0 flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <div>
                            <h2 class="text-xs font-black uppercase text-slate-900 tracking-wider">
                                3. Siap Disajikan
                            </h2>
                            <p class="text-[10px] text-slate-400 font-medium">Menunggu diantar pramusaji</p>
                        </div>
                    </div>
                    <span class="text-xs font-black px-2.5 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono">
                        {{ $readyOrders->count() }}
                    </span>
                </div>

                {{-- Column Cards --}}
                <div class="flex-1 min-h-0 space-y-3 overflow-y-auto pr-1">
                    @forelse ($readyOrders as $order)
                        <div class="bg-white rounded-xl p-3.5 border border-slate-200 hover:border-slate-300 shadow-2xs space-y-3 transition">
                            {{-- Header Meta: Table / Takeaway & Status --}}
                            <div class="pb-2.5 border-b border-slate-100">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if ($order->table)
                                            <span class="px-2.5 py-0.5 rounded-md bg-slate-900 text-white font-mono font-black text-xs tracking-wide">
                                                MEJA {{ $order->table->table_number }}
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-800 border border-slate-300 font-mono font-bold text-xs tracking-wide">
                                                TAKEAWAY
                                            </span>
                                        @endif
                                        <span class="font-mono text-xs font-bold text-slate-500">
                                            {{ $order->order_code }}
                                        </span>
                                    </div>
                                    <span class="text-[11px] font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md flex items-center gap-1 shrink-0">
                                        <x-icon name="utensils" class="w-3 h-3 text-emerald-700" />
                                        <span>Siap Antar</span>
                                    </span>
                                </div>
                                <div class="text-xs text-slate-500 font-medium mt-1.5 flex items-center justify-between">
                                    <span>Pemesan: <strong class="text-slate-900 capitalize font-bold">{{ $order->customer_name }}</strong></span>
                                    <span class="text-[11px] font-bold text-slate-400 font-mono">{{ $order->items->sum('quantity') }} porsi</span>
                                </div>
                            </div>

                            {{-- Item List Summary --}}
                            <div class="space-y-1.5">
                                @foreach ($order->items as $item)
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold text-slate-900 flex items-center gap-2">
                                            <span class="inline-flex items-center justify-center min-w-[22px] h-5 px-1 rounded bg-slate-900 text-white font-mono font-black text-[11px]">
                                                {{ $item->quantity }}x
                                            </span>
                                            <span>{{ $item->menu?->name ?? 'Menu' }}</span>
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Action Button --}}
                            <div class="pt-1">
                                <button
                                    type="button"
                                    wire:click="markCompleted({{ $order->id }})"
                                    class="w-full h-10 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 shadow-2xs cursor-pointer active:scale-98"
                                >
                                    <x-icon name="check" class="w-4 h-4 text-white" />
                                    <span>Antarkan ke Meja (Selesai)</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 px-4 rounded-xl border border-dashed border-slate-200 bg-slate-50/50 space-y-1.5">
                            <div class="w-9 h-9 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <x-icon name="utensils" class="w-4 h-4" />
                            </div>
                            <div class="text-xs font-bold text-slate-700">Semua pesanan telah diantar</div>
                            <div class="text-[11px] text-slate-400">Meja saji bersih dan siap menerima racikan berikutnya.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Column 4: Selesai (Completed History) --}}
            <div class="flex flex-col h-full min-h-0 rounded-2xl bg-white border border-slate-200 p-3.5 sm:p-4 shadow-2xs overflow-hidden">
                {{-- Column Header --}}
                <div class="shrink-0 flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                        <div>
                            <h2 class="text-xs font-black uppercase text-slate-900 tracking-wider">
                                4. Riwayat Selesai
                            </h2>
                            <p class="text-[10px] text-slate-400 font-medium">Pesanan selesai hari ini</p>
                        </div>
                    </div>
                    <span class="text-xs font-black px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-700 border border-slate-200 font-mono">
                        {{ $completedOrders->count() }}
                    </span>
                </div>

                {{-- Column Cards --}}
                <div class="flex-1 min-h-0 space-y-2.5 overflow-y-auto pr-1">
                    @forelse ($completedOrders as $order)
                        <div class="bg-white rounded-xl p-3 border border-slate-200 hover:border-slate-300 shadow-2xs space-y-2 transition">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-xs font-bold text-slate-600">
                                    {{ $order->order_code }}
                                </span>
                                @if ($order->table)
                                    <span class="text-[11px] font-black px-2 py-0.5 rounded-md bg-slate-100 text-slate-800 border border-slate-200">
                                        Meja {{ $order->table->table_number }}
                                    </span>
                                @else
                                    <span class="text-[11px] font-black px-2 py-0.5 rounded-md bg-slate-100 text-slate-800 border border-slate-200">
                                        Takeaway
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-800 capitalize">{{ $order->customer_name }}</span>
                                <span class="text-slate-500 font-mono text-[11px] font-bold">{{ $order->items->count() }} menu ({{ $order->items->sum('quantity') }} porsi)</span>
                            </div>

                            <div class="flex items-center justify-between text-[11px] pt-1.5 border-t border-slate-100 text-slate-500">
                                <span class="font-mono">{{ $order->updated_at?->format('H:i') ?? '-' }} WIB</span>
                                <span class="font-bold text-slate-700 flex items-center gap-1">
                                    <x-icon name="check" class="w-3 h-3 text-emerald-600" />
                                    <span>Tersaji</span>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 px-4 rounded-xl border border-dashed border-slate-200 bg-slate-50/50 space-y-1.5">
                            <div class="w-9 h-9 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <x-icon name="clock" class="w-4 h-4" />
                            </div>
                            <div class="text-xs font-bold text-slate-700">Belum ada riwayat selesai</div>
                            <div class="text-[11px] text-slate-400">Pesanan yang selesai diantar akan diarsipkan di sini.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    {{-- Logout Confirmation Modal --}}
    @if ($showLogoutModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-150">
            <div class="bg-white rounded-3xl border border-slate-200 max-w-sm w-full p-6 shadow-2xl space-y-4 animate-in zoom-in-95 duration-150 text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center mx-auto">
                    <x-icon name="logout" class="w-6 h-6 text-slate-700" />
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-black text-slate-900">Konfirmasi Keluar</h3>
                    <p class="text-xs text-slate-500 font-medium">Apakah Anda yakin ingin keluar dari sesi Dapur?</p>
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
                        Ya, Keluar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
