<div
    x-data="{
        openMidtrans(token) {
            if (window.snap && token) {
                window.snap.pay(token, {
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
            } else if (!token) {
                $wire.processCheckout();
            }
        }
    }"
    class="min-h-screen bg-[#F1F5F9] font-sans pb-10"
>

    {{-- Toast Notification --}}
    @include('livewire.reuse-component.Toast-notification')

    {{-- Top Bar --}}
    <header class="sticky top-0 z-40 bg-white border-b-2 border-slate-200 px-4 sm:px-8 py-3 shadow-xs">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('staff.kitchen-board') }}"
                    wire:navigate
                    class="w-9 h-9 rounded-xl border-2 border-slate-200 bg-white flex items-center justify-center text-slate-700 hover:border-brand-blue hover:text-brand-blue transition font-black text-sm"
                    title="Ke Kitchen Kanban"
                >
                    ←
                </a>
                <div>
                    <span class="text-xs font-black text-brand-blue tracking-wide">POINT OF SALE (POS)</span>
                    <h1 class="text-base sm:text-lg font-black text-slate-900 leading-tight">Kasir Walk-in Register</h1>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a
                    href="{{ route('staff.kitchen-board') }}"
                    wire:navigate
                    class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold transition flex items-center gap-1.5"
                >
                    <span>📋 Kanban Dapur</span>
                </a>
                <a
                    href="{{ route('admin.dashboard') }}"
                    wire:navigate
                    class="px-3.5 py-1.5 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5"
                >
                    <span>📊 Admin</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button
                        type="submit"
                        class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-xs"
                        title="Logout Kasir"
                    >
                        <span>🚪 Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- Dual Pane Workspace --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 pt-5">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- Left Pane: Catalog & Menus (7 cols) --}}
            <div class="lg:col-span-7 space-y-4">
                {{-- Search & Category Filter --}}
                <div class="bg-white p-4 rounded-3xl border-2 border-slate-200 shadow-xs space-y-3">
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Cari menu kopi, matcha, makanan..."
                            class="w-full h-11 pl-10 pr-4 bg-slate-50 border-2 border-slate-200 rounded-2xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-brand-blue"
                        />
                        <span class="absolute left-3.5 top-3 text-slate-400 text-sm">🔍</span>
                    </div>

                    {{-- Category Chips --}}
                    @include('livewire.reuse-component.CategoryTabs', ['allLabel' => 'Semua Menu'])
                </div>

                {{-- Menu Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @forelse ($menus as $menu)
                        <div
                            wire:click="addToCart({{ $menu->id }})"
                            class="bg-white p-3.5 rounded-2xl border-2 border-slate-200 hover:border-brand-blue hover:shadow-md transition cursor-pointer flex flex-col justify-between group active:scale-98"
                        >
                            <div>
                                <div class="w-full h-24 bg-slate-100 rounded-xl mb-2.5 overflow-hidden flex items-center justify-center border border-slate-100">
                                    @if ($menu->image_url)
                                        <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" class="w-full h-full object-cover"/>
                                    @else
                                        <span class="text-3xl">☕</span>
                                    @endif
                                </div>
                                <h3 class="text-xs font-black text-slate-900 group-hover:text-brand-blue transition line-clamp-1">{{ $menu->name }}</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">{{ $menu->description }}</p>
                            </div>

                            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-xs font-black text-brand-blue font-mono">{{ $menu->formatted_price }}</span>
                                <span class="w-6 h-6 rounded-lg bg-blue-50 text-brand-blue text-xs font-black flex items-center justify-center group-hover:bg-brand-blue group-hover:text-white transition">+</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white p-8 rounded-2xl border-2 border-slate-200 text-center text-xs text-slate-400">
                            Menu tidak ditemukan.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Right Pane: Active Register Bill (5 cols) --}}
            <div class="lg:col-span-5 bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b-2 border-slate-100">
                    <div>
                        <h2 class="text-sm font-black text-slate-900">Pesanan Aktif</h2>
                        <span class="text-[11px] text-slate-400">Total item: {{ count($cart) }}</span>
                    </div>
                    @if (!empty($cart))
                        <button
                            type="button"
                            wire:click="clearCart"
                            class="text-xs font-bold text-red-500 hover:text-red-700 cursor-pointer"
                        >
                            Reset Keranjang
                        </button>
                    @endif
                </div>

                {{-- Customer Info & Table --}}
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Nama Pembeli</label>
                        <input
                            type="text"
                            wire:model.live="customerName"
                            class="w-full h-9 px-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-brand-blue"
                        />
                    </div>
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Pilih Meja</label>
                        <select
                            wire:model.live="selectedTableId"
                            class="w-full h-9 px-2 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-brand-blue"
                        >
                            @foreach ($tables as $tbl)
                                <option value="{{ $tbl->id }}">Meja {{ $tbl->table_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Cart Items List --}}
                <div class="space-y-2 max-h-60 overflow-y-auto pr-1 divide-y divide-slate-100">
                    @forelse ($cart as $id => $item)
                        <div class="pt-2 first:pt-0 flex items-center justify-between gap-3 text-xs">
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-slate-900 truncate">{{ $item['name'] }}</h4>
                                <span class="text-slate-400 font-mono text-[11px]">@ {{ $item['formatted_price'] ?? ('Rp '.number_format($item['price'], 0, ',', '.')) }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    wire:click="updateQuantity({{ $id }}, -1)"
                                    class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-black flex items-center justify-center cursor-pointer"
                                >
                                    -
                                </button>
                                <span class="w-5 text-center font-black text-slate-900">{{ $item['quantity'] }}</span>
                                <button
                                    type="button"
                                    wire:click="updateQuantity({{ $id }}, 1)"
                                    class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-black flex items-center justify-center cursor-pointer"
                                >
                                    +
                                </button>
                            </div>

                            <span class="font-black text-slate-900 font-mono shrink-0 w-20 text-right">
                                {{ $item['formatted_subtotal'] ?? ('Rp '.number_format($item['subtotal'], 0, ',', '.')) }}
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400 font-medium border-2 border-dashed border-slate-100 rounded-2xl">
                            Klik menu di sebelah kiri untuk menambahkan.
                        </div>
                    @endforelse
                </div>

                {{-- Bill Summary --}}
                <div class="pt-3 border-t-2 border-slate-100 space-y-1.5 text-xs">
                    <div class="flex justify-between text-slate-500">
                        <span>Subtotal</span>
                        <span class="font-mono font-bold">{{ $this->formatted_subtotal }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>PB1 (Pajak Resto 10%)</span>
                        <span class="font-mono font-bold">{{ $this->formatted_tax }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-black text-slate-900 pt-1.5 border-t border-dashed border-slate-200">
                        <span>Total Tagihan</span>
                        <span class="text-brand-blue font-mono">{{ $this->formatted_total }}</span>
                    </div>
                </div>

                {{-- Payment Method & Cash Quick Buttons --}}
                <div class="space-y-2 pt-2">
                    <div class="grid grid-cols-3 gap-1.5 text-xs font-bold">
                        <button
                            type="button"
                            wire:click="$set('paymentMethod', 'cash')"
                            class="h-9 px-1 rounded-xl border-2 flex items-center justify-center gap-1 cursor-pointer transition {{ $paymentMethod === 'cash' ? 'bg-brand-blue text-white border-brand-blue shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-slate-300' }}"
                        >
                            <span>💵 Tunai</span>
                        </button>
                        <button
                            type="button"
                            wire:click="$set('paymentMethod', 'qris')"
                            class="h-9 px-1 rounded-xl border-2 flex items-center justify-center gap-1 cursor-pointer transition {{ $paymentMethod === 'qris' ? 'bg-brand-blue text-white border-brand-blue shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-slate-300' }}"
                        >
                            <span>💳 QRIS Midtrans</span>
                        </button>
                        <button
                            type="button"
                            wire:click="$set('paymentMethod', 'qris_static')"
                            class="h-9 px-1 rounded-xl border-2 flex items-center justify-center gap-1 cursor-pointer transition {{ $paymentMethod === 'qris_static' ? 'bg-brand-blue text-white border-brand-blue shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-slate-300' }}"
                        >
                            <span>🏷️ QRIS Statis</span>
                        </button>
                    </div>

                    @if ($paymentMethod === 'cash')
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-500">Uang Diterima:</span>
                                <input
                                    type="number"
                                    wire:model.live="cashGiven"
                                    class="w-32 h-8 px-2.5 text-right bg-white border border-slate-300 rounded-lg font-mono font-black text-slate-900 focus:outline-none focus:border-brand-blue"
                                />
                            </div>

                            <div class="flex gap-1.5">
                                <button
                                    type="button"
                                    wire:click="setExactCash"
                                    class="flex-1 py-1 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-[11px] font-bold text-slate-700 cursor-pointer"
                                >
                                    Uang Pas
                                </button>
                                <button
                                    type="button"
                                    wire:click="setCashGiven(50000)"
                                    class="flex-1 py-1 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-[11px] font-bold text-slate-700 cursor-pointer"
                                >
                                    50.000
                                </button>
                                <button
                                    type="button"
                                    wire:click="setCashGiven(100000)"
                                    class="flex-1 py-1 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-[11px] font-bold text-slate-700 cursor-pointer"
                                >
                                    100.000
                                </button>
                            </div>

                            <div class="flex items-center justify-between pt-1 border-t border-slate-200 text-xs">
                                <span class="font-bold text-slate-500">Kembalian:</span>
                                <span class="font-mono font-black text-emerald-600 text-sm">
                                    {{ $this->formatted_change }}
                                </span>
                            </div>
                        </div>
                    @elseif ($paymentMethod === 'qris')
                        <div class="p-3 bg-blue-50/70 rounded-2xl border border-blue-200 space-y-1 text-xs text-blue-900">
                            <div class="flex items-center gap-1.5 font-bold">
                                <span>⚡ QRIS Dinamis Midtrans Snap</span>
                            </div>
                            <p class="text-[11px] text-blue-700 leading-relaxed">
                                Barcode QRIS resmi terbit otomatis sesuai nominal pesanan saat tombol bayar ditekan.
                            </p>
                        </div>
                    @elseif ($paymentMethod === 'qris_static')
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-1 text-xs text-slate-700">
                            <span class="font-bold">🏷️ QRIS Statis Meja Kasir</span>
                            <p class="text-[11px] text-slate-500">
                                Pelanggan scan stiker akrilik di kasir. Transaksi langsung dicatat lunas tanpa popup.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Pending Midtrans Helper if popup was closed --}}
                @if ($lastOrderCode && $snapToken && !$showSuccessModal)
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-2xl space-y-2 text-xs">
                        <div class="flex items-center justify-between text-amber-900 font-bold">
                            <span>Status: Menunggu Pembayaran</span>
                            <span class="font-mono">{{ $lastOrderCode }}</span>
                        </div>
                        <div class="flex gap-2">
                            <button
                                type="button"
                                wire:click="reOpenMidtrans"
                                class="flex-1 py-1.5 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl font-bold text-[11px] cursor-pointer"
                            >
                                🔄 Buka QRIS Lagi
                            </button>
                            <button
                                type="button"
                                wire:click="markOrderPaidManually"
                                class="flex-1 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-[11px] cursor-pointer"
                            >
                                ✓ Tandai Lunas
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Action Checkout Button --}}
                <button
                    type="button"
                    wire:click="processCheckout"
                    @disabled(empty($cart))
                    class="w-full h-11 bg-brand-blue hover:bg-[#003da1] disabled:opacity-40 text-white rounded-2xl font-extrabold text-xs flex items-center justify-center gap-2 cursor-pointer shadow-md transition disabled:cursor-not-allowed"
                >
                    @if ($paymentMethod === 'qris')
                        <span>💳 Tampilkan QRIS Midtrans & Bayar</span>
                    @else
                        <span>✓ Proses Transaksi & Kirim ke Dapur</span>
                    @endif
                </button>
            </div>
        </div>
    </main>

    {{-- Modal Transaksi Selesai / Struk Simulator --}}
    @if ($showSuccessModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div wire:click="closeSuccessModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
            <div class="relative w-full max-w-sm bg-white rounded-3xl p-6 shadow-2xl border-2 border-slate-200 z-10 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-2xl mb-3">
                    ✓
                </div>
                <h3 class="text-base font-black text-slate-900">Transaksi Berhasil Disimpan!</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Pesanan walk-in telah dicatat dan otomatis dikirim ke antrean Barista.
                </p>

                <div class="my-4 p-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-left space-y-1.5 text-xs font-mono">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Kode:</span>
                        <span class="font-bold text-brand-blue">{{ $lastOrderCode }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Status:</span>
                        <span class="font-bold text-emerald-600">LUNAS • DIRAMU</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <button
                        type="button"
                        wire:click="closeSuccessModal"
                        class="w-full h-10 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition cursor-pointer"
                    >
                        Transaksi Baru ➔
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
</div>
