<div
    x-data="{
        openMidtrans(token) {
            const snapToken = (typeof token === 'object' && token !== null) ? (token.token || token[0]) : token;
            if (!snapToken) return;

            const executePay = () => {
                if (window.snap) {
                    window.snap.pay(snapToken, {
                        onSuccess: (result) => {
                            $wire.handleMidtransSuccess();
                        },
                        onPending: (result) => {
                            $wire.triggerToast('Menunggu pembayaran QRIS Midtrans diselesaikan...');
                        },
                        onError: (result) => {
                            $wire.triggerToast('Pembayaran QRIS Midtrans tidak berhasil.');
                        },
                        onClose: () => {
                            $wire.triggerToast('Jendela QRIS Midtrans ditutup. Anda bisa membuka ulang atau menandai lunas manual.');
                        }
                    });
                } else {
                    setTimeout(executePay, 300);
                }
            };

            if (!window.snap && !document.getElementById('midtrans-snap-script')) {
                const script = document.createElement('script');
                script.id = 'midtrans-snap-script';
                script.src = '{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}';
                script.setAttribute('data-client-key', '{{ config('services.midtrans.client_key') }}');
                script.onload = () => executePay();
                document.head.appendChild(script);
            } else {
                executePay();
            }
        }
    }"
    @open-midtrans-snap.window="openMidtrans($event.detail.token || $event.detail)"
    class="h-screen flex flex-col bg-[#F8FAFC] font-sans overflow-hidden text-slate-800"
>

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

    {{-- Top Bar (Single Row, Precision Aligned) --}}
    <header class="shrink-0 h-16 bg-white border-b border-slate-200 px-4 sm:px-6 shadow-2xs z-30">
        <div class="max-w-[1720px] h-full mx-auto flex items-center justify-between gap-4">
            {{-- Left: Logo & Navigation --}}
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('portal') }}" wire:navigate class="shrink-0 focus:outline-none">
                    <img
                        src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                        alt="Nongkrong.id"
                        class="h-7 w-auto object-contain shrink-0 select-none"
                    />
                </a>
                <span class="hidden sm:inline-block w-px h-5 bg-slate-200 shrink-0"></span>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                        Kasir POS
                    </span>
                    <a
                        href="{{ route('staff.cashier-tables') }}"
                        wire:navigate
                        class="text-xs font-bold text-slate-600 hover:text-brand-blue flex items-center gap-1.5 transition ml-1"
                        title="Kembali ke Denah Meja"
                    >
                        <x-icon name="arrow-left" class="w-3.5 h-3.5 text-slate-400" />
                        <span class="hidden md:inline">Daftar Meja</span>
                    </a>
                </div>
            </div>

            {{-- Right: Table Indicator, Identity & Logout --}}
            <div class="flex items-center gap-2.5 shrink-0">
                <button
                    type="button"
                    wire:click="openTableModal"
                    class="h-9 px-3 rounded-xl border text-xs font-bold transition flex items-center gap-2 cursor-pointer bg-slate-50 border-slate-200 text-slate-800 hover:bg-slate-100 shadow-2xs active:scale-95"
                    title="Ubah Meja Terpilih"
                >
                    <span class="w-2 h-2 rounded-full {{ $this->available_tables_count > 0 ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                    <span>Meja: <strong class="font-black text-slate-900">{{ \App\Models\Table::find($selectedTableId)?->table_number ?? $selectedTableId }}</strong> <span class="hidden sm:inline text-slate-500 font-medium">({{ $this->available_tables_count }} Kosong)</span></span>
                </button>

                <span class="hidden md:inline-block w-px h-5 bg-slate-200 shrink-0"></span>

                <div class="hidden md:flex flex-col text-right pl-1">
                    <span class="text-xs font-bold text-slate-900 leading-tight">{{ auth()->user()->name ?? 'Kasir' }}</span>
                    <span class="text-[10px] text-slate-500 font-medium">Staf Kasir Bertugas</span>
                </div>

                <button
                    type="button"
                    wire:click="confirmLogout"
                    class="h-9 px-3.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5 cursor-pointer shadow-2xs active:scale-95"
                    title="Logout Kasir"
                >
                    <x-icon name="logout" class="w-3.5 h-3.5 text-slate-500" />
                    <span>Keluar</span>
                </button>
            </div>
        </div>
    </header>

    {{-- Dual Pane Workspace (Fixed Viewport, Scroll Independent) --}}
    <main class="flex-1 min-h-0 max-w-[1720px] w-full mx-auto px-4 sm:px-6 py-4 overflow-hidden">
        <div class="flex flex-col lg:flex-row gap-4 h-full items-stretch min-h-0">
            {{-- Left Pane: Catalog & Menus (Scrolls Independently) --}}
            <div class="flex-1 min-h-0 flex flex-col overflow-hidden">
                {{-- Search & Category Filter --}}
                <div class="shrink-0 mb-3 bg-white p-3.5 rounded-2xl border border-slate-200 shadow-2xs space-y-3">
                    <div class="relative flex items-center">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Cari menu kopi, matcha, makanan..."
                            class="w-full h-10 pl-9 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:bg-white transition"
                        />
                        <x-icon name="search" class="w-4 h-4 text-slate-400 absolute left-3 pointer-events-none" />
                    </div>

                    {{-- Category Chips --}}
                    @include('livewire.reuse-component.CategoryTabs', [
                        'allLabel' => 'Semua Menu',
                        'activeClass' => 'bg-brand-blue text-white border-brand-blue shadow-xs'
                    ])
                </div>

                {{-- Menu Grid (Scrolls Independently) --}}
                <div class="flex-1 min-h-0 overflow-y-auto pr-1 pb-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3">
                        @forelse ($menus as $menu)
                            @php
                                $inCartCount = 0;
                                foreach ($cart as $cartItem) {
                                    if (($cartItem['menu_id'] ?? null) == $menu->id) {
                                        $inCartCount += ($cartItem['quantity'] ?? $cartItem['qty'] ?? 1);
                                    }
                                }
                            @endphp
                            <div
                                wire:click="openCustomModal({{ $menu->id }})"
                                class="bg-white p-3 rounded-2xl border border-slate-200 hover:border-slate-300 shadow-2xs hover:shadow-xs transition duration-150 cursor-pointer flex flex-col justify-between group active:scale-[0.99] relative overflow-hidden"
                            >
                                {{-- In Cart Badge Indicator --}}
                                @if ($inCartCount > 0)
                                    <div class="absolute top-2 right-2 z-10 px-2 py-0.5 rounded-full bg-brand-blue text-white text-[10px] font-mono font-black shadow-xs">
                                        {{ $inCartCount }}x
                                    </div>
                                @endif

                                <div>
                                    <div class="w-full h-24 bg-slate-50 rounded-xl mb-2 overflow-hidden flex items-center justify-center relative border border-slate-100">
                                        @if ($menu->image_url)
                                            <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                                        @else
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                                <x-icon name="coffee" class="w-5 h-5 text-slate-400" />
                                            </div>
                                        @endif
                                    </div>
                                    <h3 class="text-xs font-bold text-slate-900 group-hover:text-brand-blue transition line-clamp-1">{{ $menu->name }}</h3>
                                    <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">{{ $menu->description ?: 'Pilihan menu kopi & kuliner segar' }}</p>
                                </div>

                                <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900 font-mono">{{ $menu->formatted_price }}</span>
                                    <button
                                        type="button"
                                        wire:click.stop="addToCart({{ $menu->id }})"
                                        title="Tambah Cepat Standar"
                                        class="h-7 px-2.5 rounded-lg bg-slate-100 text-slate-800 hover:bg-brand-blue hover:text-white text-[11px] font-bold flex items-center gap-1 transition cursor-pointer shadow-2xs active:scale-95"
                                    >
                                        <x-icon name="plus" class="w-3 h-3 text-inherit" />
                                        <span>Pilih</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full bg-white p-8 rounded-2xl border border-slate-200 text-center text-xs text-slate-400">
                                Menu tidak ditemukan.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right Pane: Active Register Bill (Fixed, Fits Screen) --}}
            <div id="cashier-active-bill" class="w-full lg:w-[420px] xl:w-[450px] shrink-0 h-full flex flex-col justify-between bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs overflow-hidden">
                <div class="space-y-2.5 shrink-0">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <div>
                            <h2 class="text-xs font-black text-slate-900 uppercase tracking-wide">Pesanan Aktif</h2>
                            <span class="text-[10px] text-slate-400 font-medium">{{ count($cart) }} item dalam struk</span>
                        </div>
                        @if (!empty($cart))
                            <button
                                type="button"
                                wire:click="clearCart"
                                class="text-[11px] font-bold text-rose-600 hover:text-rose-700 cursor-pointer transition active:scale-95"
                            >
                                Reset Keranjang
                            </button>
                        @endif
                    </div>

                    {{-- Customer Info & Table Selection --}}
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Nama Pembeli</label>
                            <input
                                type="text"
                                wire:model.live="customerName"
                                placeholder="Contoh: Budi"
                                class="w-full h-8.5 px-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:bg-white transition text-xs"
                            />
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-[11px] font-bold text-slate-600">Pilih Meja</label>
                                <button
                                    type="button"
                                    wire:click="openTableModal"
                                    class="text-[10px] font-bold text-brand-blue hover:underline cursor-pointer"
                                >
                                    Denah ({{ $this->available_tables_count }} Kosong)
                                </button>
                            </div>
                            <select
                                wire:model.live="selectedTableId"
                                class="w-full h-8.5 px-2 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-slate-800 focus:bg-white transition text-xs"
                            >
                                @foreach ($tables as $tbl)
                                    <option value="{{ $tbl->id }}" @disabled(!$tbl->isAvailable())>
                                        Meja {{ $tbl->table_number }} ({{ $tbl->isAvailable() ? 'Kosong' : 'Terisi' }} • {{ $tbl->capacity }}p)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Table Quick Pills Strip --}}
                    <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-0.5">
                        @foreach ($tables as $tbl)
                            @php
                                $isAvail = $tbl->isAvailable();
                                $isSelected = ((int)$selectedTableId === (int)$tbl->id);
                            @endphp
                            <button
                                type="button"
                                @if ($isAvail)
                                    wire:click="selectTable({{ $tbl->id }})"
                                @else
                                    disabled
                                @endif
                                class="shrink-0 px-2.5 py-1 rounded-lg border text-center transition flex items-center gap-1 text-[11px] font-bold {{ $isSelected ? 'bg-brand-blue text-white border-brand-blue shadow-xs font-black' : ($isAvail ? 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100 cursor-pointer' : 'bg-slate-100 border-slate-200 text-slate-400 opacity-50 cursor-not-allowed') }}"
                                title="Meja {{ $tbl->table_number }} - {{ $isAvail ? 'Kosong' : 'Terisi' }} ({{ $tbl->capacity }} kursi)"
                            >
                                <span class="w-1.5 h-1.5 rounded-full {{ $isAvail ? 'bg-emerald-500' : 'bg-rose-400' }}"></span>
                                <span>{{ $tbl->table_number }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Cart Items List (Tengah Fleksibel, Scrolls Internally) --}}
                <div class="space-y-1.5 flex-1 min-h-[70px] overflow-y-auto my-2 pr-1">
                    @forelse ($cart as $index => $item)
                        <div class="p-2.5 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-between gap-2 text-xs">
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-slate-900 truncate">{{ $item['name'] }}</div>
                                @if (!empty($item['options_summary']))
                                    <div class="mt-0.5 inline-block text-[10px] text-slate-600 bg-white border border-slate-200 rounded px-1.5 py-0.5 font-medium truncate max-w-full">
                                        {{ $item['options_summary'] }}
                                    </div>
                                @endif
                                <div class="text-[10px] text-slate-500 font-mono mt-0.5">{{ $item['formatted_subtotal'] }}</div>
                            </div>

                            <div class="flex items-center gap-1 shrink-0">
                                <button
                                    type="button"
                                    wire:click="updateQuantity('{{ $index }}', -1)"
                                    class="w-6 h-6 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold flex items-center justify-center hover:bg-slate-100 cursor-pointer shadow-2xs active:scale-95"
                                >
                                    -
                                </button>
                                <span class="w-5 text-center font-bold font-mono text-xs">{{ $item['quantity'] ?? $item['qty'] ?? 1 }}</span>
                                <button
                                    type="button"
                                    wire:click="updateQuantity('{{ $index }}', 1)"
                                    class="w-6 h-6 rounded-lg bg-slate-900 text-white font-bold flex items-center justify-center hover:bg-black cursor-pointer shadow-2xs active:scale-95"
                                >
                                    +
                                </button>
                                <button
                                    type="button"
                                    wire:click="removeFromCart('{{ $index }}')"
                                    class="w-6 h-6 rounded-lg text-rose-500 hover:text-rose-700 font-bold flex items-center justify-center cursor-pointer ml-1 active:scale-95"
                                    title="Hapus"
                                >
                                    <x-icon name="trash" class="w-3 h-3 text-inherit" />
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="h-full min-h-[90px] flex flex-col items-center justify-center text-center text-xs text-slate-400">
                            <x-icon name="shopping-bag" class="w-6 h-6 text-slate-300 mb-1" />
                            <span>Keranjang walk-in kosong</span>
                        </div>
                    @endforelse
                </div>

                {{-- Bagian Bawah: Perhitungan, Metode Bayar & Tombol Checkout (Docked di Bawah) --}}
                <div class="shrink-0 space-y-2 pt-2 border-t border-slate-100">
                    {{-- Subtotal & Tax Calculation --}}
                    <div class="p-2.5 bg-slate-50 border border-slate-100 rounded-xl space-y-1 text-xs">
                        <div class="flex justify-between text-slate-500 font-medium">
                            <span>Subtotal Item</span>
                            <span class="font-mono font-bold text-slate-800">{{ $this->formatted_subtotal }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500 font-medium">
                            <span>Pajak Restoran PB1 (10%)</span>
                            <span class="font-mono font-bold text-slate-800">{{ $this->formatted_tax }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-black text-slate-900 pt-1 border-t border-slate-200">
                            <span>Total Tagihan</span>
                            <span class="text-slate-900 font-mono text-sm font-black">{{ $this->formatted_total }}</span>
                        </div>
                    </div>

                    {{-- Payment Method Buttons --}}
                    <div class="grid grid-cols-3 gap-1.5 text-xs font-bold">
                        <button
                            type="button"
                            wire:click="$set('paymentMethod', 'cash')"
                            class="h-8.5 px-1 rounded-xl border flex items-center justify-center gap-1 cursor-pointer transition text-xs {{ $paymentMethod === 'cash' ? 'bg-brand-blue text-white border-brand-blue shadow-xs font-black' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}"
                        >
                            <x-icon name="banknote" class="w-3.5 h-3.5" />
                            <span>Tunai</span>
                        </button>
                        <button
                            type="button"
                            wire:click="$set('paymentMethod', 'qris')"
                            class="h-8.5 px-1 rounded-xl border flex items-center justify-center gap-1 cursor-pointer transition text-xs {{ $paymentMethod === 'qris' ? 'bg-brand-blue text-white border-brand-blue shadow-xs font-black' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}"
                        >
                            <x-icon name="qr-code" class="w-3.5 h-3.5" />
                            <span>QRIS Snap</span>
                        </button>
                        <button
                            type="button"
                            wire:click="$set('paymentMethod', 'qris_static')"
                            class="h-8.5 px-1 rounded-xl border flex items-center justify-center gap-1 cursor-pointer transition text-xs {{ $paymentMethod === 'qris_static' ? 'bg-brand-blue text-white border-brand-blue shadow-xs font-black' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}"
                        >
                            <x-icon name="credit-card" class="w-3.5 h-3.5" />
                            <span>QRIS Meja</span>
                        </button>
                    </div>

                    @if ($paymentMethod === 'cash')
                        <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100 space-y-1.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-600">Uang Diterima:</span>
                                <input
                                    type="number"
                                    wire:model.live="cashGiven"
                                    class="w-32 h-8 px-2.5 text-right bg-white border border-slate-200 rounded-lg font-mono font-black text-slate-900 focus:outline-none focus:border-slate-800 shadow-2xs text-xs"
                                />
                            </div>

                            <div class="flex gap-1.5">
                                <button
                                    type="button"
                                    wire:click="setExactCash"
                                    class="flex-1 py-1 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-[11px] font-bold text-slate-700 cursor-pointer shadow-2xs transition active:scale-95"
                                >
                                    Uang Pas
                                </button>
                                <button
                                    type="button"
                                    wire:click="setCashGiven(50000)"
                                    class="flex-1 py-1 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-[11px] font-bold text-slate-700 cursor-pointer shadow-2xs transition active:scale-95"
                                >
                                    50k
                                </button>
                                <button
                                    type="button"
                                    wire:click="setCashGiven(100000)"
                                    class="flex-1 py-1 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-[11px] font-bold text-slate-700 cursor-pointer shadow-2xs transition active:scale-95"
                                >
                                    100k
                                </button>
                            </div>

                            <div class="flex items-center justify-between pt-1 border-t border-slate-200 text-xs">
                                <span class="font-bold text-slate-600">Kembalian:</span>
                                <span class="font-mono font-black text-emerald-600 text-sm">
                                    {{ $this->formatted_change }}
                                </span>
                            </div>
                        </div>
                    @elseif ($paymentMethod === 'qris')
                        <div class="p-2 bg-slate-100 rounded-xl border border-slate-200 space-y-0.5 text-xs text-slate-800">
                            <div class="flex items-center gap-1.5 font-bold text-[11px]">
                                <x-icon name="qr-code" class="w-3.5 h-3.5 text-slate-900" />
                                <span>QRIS Dinamis Midtrans Snap</span>
                            </div>
                            <p class="text-[10px] text-slate-600 leading-tight">
                                Barcode QRIS terbit otomatis sesuai nominal pesanan.
                            </p>
                        </div>
                    @elseif ($paymentMethod === 'qris_static')
                        <div class="p-2 bg-slate-50 rounded-xl border border-slate-200 space-y-0.5 text-xs text-slate-700">
                            <div class="flex items-center gap-1.5 font-bold text-[11px]">
                                <x-icon name="credit-card" class="w-3.5 h-3.5 text-slate-600" />
                                <span>QRIS Statis Meja Kasir</span>
                            </div>
                            <p class="text-[10px] text-slate-500 leading-tight">
                                Pelanggan scan stiker akrilik di kasir. Transaksi langsung dicatat lunas.
                            </p>
                        </div>
                    @endif

                    {{-- Pending Midtrans Helper if popup was closed --}}
                    @if ($lastOrderCode && $snapToken && !$showSuccessModal)
                        <div class="p-2 bg-amber-50 border border-amber-200 rounded-xl space-y-1 text-xs">
                            <div class="flex items-center justify-between text-amber-900 font-bold text-[11px]">
                                <span>Menunggu Pembayaran:</span>
                                <span class="font-mono">{{ $lastOrderCode }}</span>
                            </div>
                            <div class="flex gap-1.5">
                                <button
                                    type="button"
                                    @click="openMidtrans('{{ $snapToken }}')"
                                    wire:click="reOpenMidtrans"
                                    class="flex-1 py-1.5 bg-slate-900 hover:bg-black text-white rounded-lg font-bold text-xs cursor-pointer flex items-center justify-center gap-1 shadow-2xs active:scale-95"
                                >
                                    <x-icon name="refresh" class="w-3 h-3 text-white" />
                                    <span>Buka QRIS</span>
                                </button>
                                <button
                                    type="button"
                                    wire:click="markOrderPaidManually"
                                    class="flex-1 py-1.5 bg-brand-blue hover:bg-[#003da1] text-white rounded-lg font-bold text-xs cursor-pointer flex items-center justify-center gap-1 shadow-xs active:scale-95"
                                >
                                    <x-icon name="check" class="w-3 h-3 text-white" />
                                    <span>Tandai Lunas</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Action Checkout Button --}}
                    <button
                        type="button"
                        wire:click="processCheckout"
                        @disabled(empty($cart))
                        class="w-full h-11 bg-brand-blue hover:bg-[#003da1] disabled:opacity-40 text-white rounded-xl font-black text-xs sm:text-sm flex items-center justify-center gap-2 cursor-pointer shadow-xs transition active:scale-98 disabled:cursor-not-allowed"
                    >
                        @if ($paymentMethod === 'qris')
                            <x-icon name="qr-code" class="w-4 h-4 text-white" />
                            <span>Tampilkan QRIS Midtrans & Bayar</span>
                        @else
                            <x-icon name="check" class="w-4 h-4 text-white" />
                            <span>Proses Transaksi & Kirim ke Dapur</span>
                        @endif
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Floating Bill Shortcut (Hanya di layar < md) --}}
        @if (!empty($cart))
            <div class="md:hidden fixed bottom-4 inset-x-4 z-30">
                <a
                    href="#cashier-active-bill"
                    class="w-full py-3.5 px-5 bg-slate-900/95 backdrop-blur-md text-white rounded-2xl shadow-xl flex items-center justify-between font-bold text-xs active:scale-98 transition"
                >
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-slate-800 flex items-center justify-center text-[11px] font-black">{{ count($cart) }}</span>
                        <span>Pesanan Aktif</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-mono text-emerald-400 font-black">
                        <span>{{ $this->formatted_total }}</span>
                        <span>&darr;</span>
                    </div>
                </a>
            </div>
        @endif
    </main>

    {{-- Modal Transaksi Selesai / Struk Simulator --}}
    @if ($showSuccessModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div wire:click="closeSuccessModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
            <div class="relative w-full max-w-sm bg-white rounded-3xl p-6 shadow-2xl border-2 border-slate-200 z-10 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-50 text-brand-blue border border-blue-200 flex items-center justify-center mb-3">
                    <x-icon name="check" class="w-8 h-8 text-brand-blue" />
                </div>
                <h3 class="text-base font-black text-slate-900">Transaksi Berhasil Disimpan!</h3>
                <p class="text-xs text-slate-500 mt-1 font-medium">
                    Pesanan walk-in telah dicatat dan otomatis dikirim ke antrean Barista.
                </p>

                <div class="my-4 p-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-left space-y-1.5 text-xs font-mono">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Kode:</span>
                        <span class="font-bold text-slate-900">{{ $lastOrderCode }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Status:</span>
                        <span class="font-bold text-brand-blue">LUNAS • DIRAMU</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <button
                        type="button"
                        wire:click="closeSuccessModal"
                        class="w-full h-10 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition cursor-pointer flex items-center justify-center gap-1.5 shadow-xs"
                    >
                        <span>Transaksi Baru (Pilih Meja)</span>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5 text-white" />
                    </button>
                    <a
                        href="{{ route('staff.kitchen-board') }}"
                        wire:navigate
                        class="w-full h-10 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold transition flex items-center justify-center"
                    >
                        Buka Kitchen Kanban
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Kustomisasi Menu Kasir (Minuman vs Makanan) --}}
    @if ($showCustomModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div wire:click="closeCustomModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
            <div class="relative w-full max-w-md bg-white rounded-3xl p-6 shadow-2xl border-2 border-slate-200 z-10 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-800">
                            {{ $customMenuCategory ?? ($customIsBeverage ? 'Minuman' : 'Makanan') }}
                        </span>
                        <h3 class="text-base font-black text-slate-900 mt-1">{{ $customMenuName }}</h3>
                        <p class="text-xs font-black text-slate-900 font-mono mt-0.5">{{ $formattedCustomMenuPrice }}</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeCustomModal"
                        class="p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 cursor-pointer"
                    >
                        <x-icon name="close" class="w-4 h-4 text-inherit" />
                    </button>
                </div>

                <div class="space-y-3.5 text-xs">
                    @if ($customIsBeverage)
                        {{-- Minuman: Pilihan Suhu --}}
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Penyajian Suhu:</label>
                            <div class="grid grid-cols-2 gap-2">
                                <button
                                    type="button"
                                    wire:click="$set('customTemperature', 'cold')"
                                    @class([
                                        'py-2 px-3 rounded-xl font-bold border transition flex items-center justify-center gap-1.5 cursor-pointer text-xs',
                                        'bg-brand-blue border-brand-blue text-white shadow-xs font-black' => $customTemperature === 'cold',
                                        'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $customTemperature !== 'cold',
                                    ])
                                >
                                    <x-icon name="snowflake" class="w-3.5 h-3.5 text-inherit" />
                                    <span>Dingin (Iced)</span>
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('customTemperature', 'hot')"
                                    @class([
                                        'py-2 px-3 rounded-xl font-bold border transition flex items-center justify-center gap-1.5 cursor-pointer text-xs',
                                        'bg-brand-blue border-brand-blue text-white shadow-xs font-black' => $customTemperature === 'hot',
                                        'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $customTemperature !== 'hot',
                                    ])
                                >
                                    <x-icon name="flame" class="w-3.5 h-3.5 text-inherit" />
                                    <span>Panas (Hot)</span>
                                </button>
                            </div>
                        </div>

                        {{-- Minuman: Tingkat Gula --}}
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Tingkat Manis / Gula:</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button
                                    type="button"
                                    wire:click="$set('customSugar', 'normal')"
                                    @class([
                                        'py-1.5 px-2 rounded-xl font-bold border transition text-center cursor-pointer text-xs',
                                        'bg-brand-blue border-brand-blue text-white shadow-xs font-black' => $customSugar === 'normal',
                                        'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $customSugar !== 'normal',
                                    ])
                                >
                                    Normal
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('customSugar', 'less')"
                                    @class([
                                        'py-1.5 px-2 rounded-xl font-bold border transition text-center cursor-pointer text-xs',
                                        'bg-brand-blue border-brand-blue text-white shadow-xs font-black' => $customSugar === 'less',
                                        'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $customSugar !== 'less',
                                    ])
                                >
                                    Less Sugar
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('customSugar', 'zero')"
                                    @class([
                                        'py-1.5 px-2 rounded-xl font-bold border transition text-center cursor-pointer text-xs',
                                        'bg-brand-blue border-brand-blue text-white shadow-xs font-black' => $customSugar === 'zero',
                                        'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $customSugar !== 'zero',
                                    ])
                                >
                                    No Sugar
                                </button>
                            </div>
                        </div>
                    @else
                        {{-- Makanan: Penyajian --}}
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Penyajian Makanan:</label>
                            <div class="grid grid-cols-2 gap-2">
                                <button
                                    type="button"
                                    wire:click="$set('customServing', 'warm')"
                                    @class([
                                        'py-2 px-3 rounded-xl font-bold border transition flex items-center justify-center gap-1.5 cursor-pointer text-xs',
                                        'bg-brand-blue border-brand-blue text-white shadow-xs font-black' => $customServing === 'warm',
                                        'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $customServing !== 'warm',
                                    ])
                                >
                                    <x-icon name="flame" class="w-3.5 h-3.5 text-inherit" />
                                    <span>Dihangatkan</span>
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('customServing', 'normal')"
                                    @class([
                                        'py-2 px-3 rounded-xl font-bold border transition flex items-center justify-center gap-1.5 cursor-pointer text-xs',
                                        'bg-brand-blue border-brand-blue text-white shadow-xs font-black' => $customServing === 'normal',
                                        'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $customServing !== 'normal',
                                    ])
                                >
                                    <span>Suhu Ruang</span>
                                </button>
                            </div>
                        </div>

                        {{-- Makanan: Saus --}}
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Pilihan Saus / Rasa:</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button
                                    type="button"
                                    wire:click="$set('customSauce', 'standard')"
                                    @class([
                                        'py-1.5 px-2 rounded-xl font-bold border transition text-center cursor-pointer text-xs',
                                        'bg-brand-blue border-brand-blue text-white shadow-xs font-black' => $customSauce === 'standard',
                                        'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $customSauce !== 'standard',
                                    ])
                                >
                                    Standar
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('customSauce', 'extra_sauce')"
                                    @class([
                                        'py-1.5 px-2 rounded-xl font-bold border transition text-center cursor-pointer text-xs',
                                        'bg-brand-blue border-brand-blue text-white shadow-xs font-black' => $customSauce === 'extra_sauce',
                                        'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $customSauce !== 'extra_sauce',
                                    ])
                                >
                                    Extra Saus
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('customSauce', 'no_sauce')"
                                    @class([
                                        'py-1.5 px-2 rounded-xl font-bold border transition text-center cursor-pointer text-xs',
                                        'bg-brand-blue border-brand-blue text-white shadow-xs font-black' => $customSauce === 'no_sauce',
                                        'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $customSauce !== 'no_sauce',
                                    ])
                                >
                                    Tanpa Saus
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Catatan Barista / Dapur --}}
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            {{ $customIsBeverage ? 'Catatan Barista:' : 'Catatan Dapur:' }}
                        </label>
                        <input
                            type="text"
                            wire:model="customNotes"
                            placeholder="{{ $customIsBeverage ? 'Contoh: Less ice, oat milk...' : 'Contoh: Saus dipisah, jangan gosong...' }}"
                            class="w-full h-9 px-3 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-800 text-xs"
                        />
                    </div>

                    {{-- Jumlah & Subtotal --}}
                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <span class="font-bold text-slate-700">Jumlah:</span>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                wire:click="decrementCustomQty"
                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold flex items-center justify-center cursor-pointer"
                            >
                                -
                            </button>
                            <span class="font-black text-sm w-6 text-center font-mono">{{ $customQuantity }}</span>
                            <button
                                type="button"
                                wire:click="incrementCustomQty"
                                class="w-8 h-8 rounded-lg bg-slate-900 text-white font-bold flex items-center justify-center hover:bg-black cursor-pointer"
                            >
                                +
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button
                        type="button"
                        wire:click="addCustomizedToCart"
                        class="w-full h-11 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-black transition cursor-pointer flex items-center justify-center gap-2 shadow-xs active:scale-98"
                    >
                        <span>Tambahkan ke Keranjang</span>
                        <span>• {{ $formattedCustomSubtotal }}</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Status & Denah Meja Kasir --}}
    @if ($showTableModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-150">
            <div class="bg-white rounded-3xl border-2 border-slate-200 max-w-3xl w-full p-6 shadow-2xl space-y-5 animate-in zoom-in-95 duration-150 max-h-[90vh] overflow-y-auto">
                {{-- Header Modal --}}
                <div class="flex items-center justify-between pb-3 border-b-2 border-slate-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-black text-slate-900">Denah & Status Meja Cafe</h2>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black {{ $this->available_tables_count > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $this->available_tables_count }} Meja Kosong / {{ $this->total_tables_count }} Total
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">Pantau meja yang sedang terisi atau kosong, dan pilih langsung untuk pesanan walk-in kasir.</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeTableModal"
                        class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-black cursor-pointer text-sm"
                    >
                        ✕
                    </button>
                </div>

                {{-- Grid Cards Meja --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3.5">
                    @foreach ($tables as $tbl)
                        @php
                            $isAvail = $tbl->isAvailable();
                            $isOccupied = ($tbl->status === 'occupied');
                            $isDirty = ($tbl->status === 'dirty');
                            $isSelected = ((int)$selectedTableId === (int)$tbl->id);
                        @endphp
                        <div
                            @if ($isAvail)
                                wire:click="selectTableAndClose({{ $tbl->id }})"
                            @endif
                            class="p-3.5 rounded-2xl border-2 transition flex flex-col justify-between select-none relative {{ $isSelected ? 'border-brand-blue bg-blue-50/50 shadow-xs' : ($isAvail ? 'border-slate-200 hover:border-slate-400 bg-white cursor-pointer hover:shadow-xs' : 'border-slate-200 bg-slate-50 cursor-not-allowed') }}"
                        >
                            {{-- Top Indicator & Selected Badge --}}
                            <div class="flex items-center justify-between">
                                <span class="w-2.5 h-2.5 rounded-full {{ $isAvail ? 'bg-emerald-500' : ($isOccupied ? 'bg-brand-blue' : 'bg-amber-500') }}"></span>
                                @if ($isSelected)
                                    <span class="px-2 py-0.5 rounded-md bg-brand-blue text-white text-[10px] font-black">
                                        TERPILIH
                                    </span>
                                @endif
                            </div>

                            {{-- Center Table Number --}}
                            <div class="py-3 text-center">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider">MEJA</div>
                                <div class="text-2xl font-black text-slate-900 font-mono">{{ $tbl->table_number }}</div>
                                <div class="text-[11px] text-slate-500 font-medium mt-0.5">{{ $tbl->capacity }} Kursi</div>
                            </div>

                            {{-- Bottom Status Label --}}
                            <div class="pt-2 border-t border-slate-100 text-center">
                                @if ($isAvail)
                                    <span class="text-[11px] font-bold text-emerald-700">Kosong (Pilih)</span>
                                @elseif ($isOccupied)
                                    <span class="text-[11px] font-bold text-slate-600">Terisi Tamu</span>
                                @elseif ($isDirty)
                                    <span class="text-[11px] font-bold text-amber-700">Perlu Bersih</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Action / Close --}}
                <div class="pt-3 border-t border-slate-100 flex justify-end">
                    <button
                        type="button"
                        wire:click="closeTableModal"
                        class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-black text-white text-xs font-bold transition cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Logout Confirmation Modal --}}
    @if ($showLogoutModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-150">
            <div class="bg-white rounded-3xl border-2 border-slate-200 max-w-sm w-full p-6 shadow-2xl space-y-4 animate-in zoom-in-95 duration-150 text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center mx-auto">
                    <x-icon name="logout" class="w-6 h-6 text-slate-700" />
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-black text-slate-900">Konfirmasi Keluar</h3>
                    <p class="text-xs text-slate-500 font-medium">Apakah Anda yakin ingin keluar dari sesi Kasir?</p>
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
