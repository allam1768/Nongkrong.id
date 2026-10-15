<div x-data="{
    showCart: @entangle('showCartDrawer'),
    toast: false,
    toastMsg: '',
    showToast(msg) {
        this.toastMsg = msg;
        this.toast = true;
        setTimeout(() => this.toast = false, 3500);
    },
    openMidtrans(token) {
        if (window.snap && token) {
            window.snap.pay(token, {
                onSuccess: (result) => {
                    $wire.handleMidtransSuccess();
                },
                onPending: (result) => {
                    this.showToast('Menunggu penyelesaian pembayaran...');
                },
                onError: (result) => {
                    this.showToast('Pembayaran Midtrans tidak berhasil diselesaikan.');
                },
                onClose: () => {
                    // User closed popup
                }
            });
        } else if (!token) {
            this.showToast('Membuat token Snap Midtrans...');
            $wire.payWithMidtrans();
        }
    }
}" @toast-notify.window="showToast(@this.toastMessage)"
    @open-midtrans-snap.window="openMidtrans($event.detail.token)"
    class="min-h-screen lg:h-screen lg:overflow-hidden bg-[#F8FAFC] text-slate-900 flex flex-col font-sans selection:bg-brand-blue selection:text-white">
    {{-- 1. TOP NAVBAR (Identik Mockup 03_web_customer_catalog.html) --}}
    <header
        class="h-16 shrink-0 bg-white border-b-[1.5px] border-[#E2E8F0] sticky top-0 z-30 px-4 sm:px-8 flex items-center justify-between gap-4">
        {{-- Brand Logo --}}
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('customer.order') }}" wire:navigate
                class="inline-flex items-center transition-transform duration-200 hover:scale-105 focus:outline-none"
                title="NONGKRONG.id">
                <x-icon name="logo"
                    class="h-6 sm:h-7 md:h-8 w-auto max-w-36.25 sm:max-w-none object-contain select-none text-brand-blue" />
            </a>
            <span class="sr-only">Digital Menu</span>
        </div>

        {{-- Center Pill Search Bar --}}
        <div class="flex-1 max-w-110 mx-4 hidden sm:flex items-center">
            <div
                class="w-full h-10 bg-[#F8FAFC] border-[1.5px] border-[#CBD5E1] rounded-full flex items-center px-4 gap-2.5 focus-within:border-brand-blue focus-within:bg-white transition-all">
                <span class="text-sm text-slate-400 select-none">🔍</span>
                <input type="text" wire:model.live.debounce.300ms="searchQuery" id="input-search-menu"
                    placeholder="Cari kopi, tea, makanan, atau snack..."
                    class="w-full bg-transparent border-none text-[13px] text-slate-800 placeholder-slate-400 focus:outline-none"
                    autocomplete="off" />
                @if ($searchQuery)
                    <button type="button" wire:click="$set('searchQuery', '')"
                        class="text-slate-400 hover:text-slate-600 text-xs cursor-pointer p-1" title="Hapus pencarian">
                        <x-icon name="close" class="w-3.5 h-3.5" />
                    </button>
                @endif
            </div>
        </div>

        {{-- Right Controls: Table Badge Pill & Mobile Cart Button --}}
        <div class="flex items-center gap-2.5 shrink-0">
            {{-- Table Tag Pill --}}
            <div
                class="inline-flex items-center gap-1.5 bg-slate-100 border border-slate-200 text-slate-700 text-xs sm:text-[13px] font-extrabold px-3 sm:px-4 py-1.5 sm:py-2 rounded-full shadow-2xs select-none">
                <span>Meja {{ $tableNumber ?: '01' }}</span>
            </div>

            {{-- Mobile Cart Trigger (Visible on < lg) --}}
            <button type="button" wire:click="openCart" title="Lihat Keranjang Pesanan" class="relative">
                <x-icon name="card" class="w-6 h-6 text-slate-700 select-none" />

                @if ($cartCount > 0)
                    <span
                        class="absolute -top-1.5 -right-1.5 bg-brand-blue text-white font-black text-[10px] w-5 h-5 rounded-full flex items-center justify-center shadow-xs">
                        {{ $cartCount }}
                    </span>
                @endif
            </button>
        </div>
    </header>

    {{-- Mobile Search Row (Screen < sm) --}}
    <div class="sm:hidden px-4 pt-2.5 pb-2.5">
        <div
            class="w-full h-10 border-[1.5px] border-[#CBD5E1] rounded-full flex items-center px-3.5 gap-2 focus-within:border-brand-blue bg-white transition-all">
            <x-icon name="search" class="w-4 h-4 text-slate-400 select-none" />
            <input type="text" wire:model.live.debounce.300ms="searchQuery"
                placeholder="Cari kopi, tea, makanan, atau snack..."
                class="w-full bg-transparent border-none text-xs text-slate-800 placeholder-slate-400 focus:outline-none" />
            @if ($searchQuery)
                <button type="button" wire:click="$set('searchQuery', '')"
                    class="text-slate-400 hover:text-slate-600 text-xs p-1">
                    <x-icon name="cancel" class="w-3.5 h-3.5 select-none" />
                </button>
            @endif
        </div>
    </div>

    {{-- 2. SPLIT CONTENT LAYOUT (Left: Catalog, Right: 360px Sticky Cart) --}}
    <div class="flex-1 flex overflow-hidden bg-[#F8FAFC]">
        {{-- LEFT MAIN SCROLLABLE CATALOG --}}
        <main class="flex-1 overflow-y-auto px-4 sm:px-8 py-6 flex flex-col gap-5">
            {{-- AI HERO BANNER (Compact & Modern) --}}
            <div
                class="shrink-0 bg-brand-blue rounded-2xl sm:rounded-3xl p-4 sm:p-6 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4 border-2 border-[#003087] relative overflow-hidden shadow-xs">
                <div class="relative z-10 max-w-xl">

                    <h2 class="text-base sm:text-xl font-black text-white leading-snug">
                        Bingung Mau Minum Apa Hari Ini?
                    </h2>
                    <p class="text-xs sm:text-[13px] text-blue-100 leading-relaxed mt-0.5">
                        Pilih Menu Favoritmu atau tanyakan rekomendasi rasa & mood terbaik langsung ke Barista AI kami!
                    </p>
                </div>

                <button type="button" wire:click="openAiModal"
                    class="relative z-10 shrink-0 w-full sm:w-auto bg-white hover:bg-blue-50 text-brand-blue font-black text-xs sm:text-[13px] px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl sm:rounded-full flex items-center justify-center gap-2 shadow-sm transition-all active:scale-95 cursor-pointer">
                    <span>Tanya Barista AI</span>
                    <x-icon name="toright" class="w-5 h-auto text-brand-blue select-none" />
                </button>

                {{-- Decorative Star Watermark in Background --}}
                <x-icon name="star" aria-hidden="true"
                    class="text-white absolute -right-6 -bottom-6 w-28 sm:w-32 h-28 sm:h-32 opacity-15 pointer-events-none select-none" />
            </div>

            {{-- CATEGORIES TABS BAR --}}
            <div class="shrink-0 flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar select-none">
                {{-- Tab: Semua Menu --}}
                <button type="button" wire:click="selectCategory('all')" @class([
                    'px-3.5 sm:px-4.5 py-2 rounded-xl sm:rounded-full text-xs sm:text-[13px] font-bold cursor-pointer whitespace-nowrap transition-all shadow-2xs',
                    'bg-brand-blue text-white border-2 border-brand-blue' =>
                        $selectedCategory === 'all',
                    'bg-white text-[#475569] border-2 border-slate-200 hover:border-slate-300' =>
                        $selectedCategory !== 'all',
                ])>
                    Semua Menu ({{ $menus->count() }})
                </button>

                @foreach ($categories as $cat)
                    <button type="button" wire:click="selectCategory('{{ $cat->slug }}')"
                        @class([
                            'px-3.5 sm:px-4.5 py-2 rounded-xl sm:rounded-full text-xs sm:text-[13px] font-bold cursor-pointer whitespace-nowrap transition-all shadow-2xs',
                            'bg-brand-blue text-white border-2 border-brand-blue' =>
                                $selectedCategory === $cat->slug,
                            'bg-white text-[#475569] border-2 border-slate-200 hover:border-slate-300' =>
                                $selectedCategory !== $cat->slug,
                        ])>
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            {{-- MENU GRID (2 Kolom di Mobile, 2 Kolom di Tablet, 3 Kolom di Desktop) --}}
            @if ($menus->count() > 0)
                <div class="shrink-0 grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-4.5">
                    @foreach ($menus as $menu)
                        <div class="bg-white border-2 border-slate-200 hover:border-brand-blue rounded-2xl p-3 sm:p-4 flex flex-col justify-between transition-all shadow-2xs hover:shadow-md group"
                            id="menu-card-{{ $menu->id }}">
                            {{-- Visual Image Container with Taste Badge --}}
                            <div wire:click="openDetail({{ $menu->id }})"
                                class="w-full aspect-square bg-white rounded-xl flex items-center justify-center relative border border-slate-100 cursor-pointer transition-colors select-none overflow-hidden p-2 group-hover:bg-slate-50/50"
                                title="Klik untuk kustomisasi opsi">
                                @if ($menu->flavor_tag)
                                    <span
                                        class="absolute top-2 left-2 bg-white/95 text-brand-blue text-[9px] sm:text-[10px] font-black px-1.5 sm:px-2 py-0.5 rounded-md border border-blue-200 shadow-2xs z-10 backdrop-blur-2xs">
                                        {{ $menu->flavor_tag }}
                                    </span>
                                @endif
                                @if ($menu->image_url)
                                    <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}"
                                        class="w-full h-full object-contain transition-transform duration-200 group-hover:scale-105"
                                        loading="lazy" />
                                @else
                                    <span class="text-3xl sm:text-4xl group-hover:scale-110 transition-transform">
                                        {{ $menu->visual }}
                                    </span>
                                @endif
                            </div>

                            {{-- Menu Information --}}
                            <div class="mt-2.5 flex-1 flex flex-col justify-between">
                                <div>
                                    <h4 wire:click="openDetail({{ $menu->id }})"
                                        class="text-xs sm:text-sm font-black text-slate-900 line-clamp-1 cursor-pointer group-hover:text-brand-blue transition-colors"
                                        title="{{ $menu->name }}">
                                        {{ $menu->name }}
                                    </h4>
                                    <p
                                        class="text-[10px] sm:text-xs text-slate-400 line-clamp-2 h-7 sm:h-8 leading-relaxed mt-0.5">
                                        {{ $menu->description }}
                                    </p>
                                </div>

                                {{-- Bottom Price & Quick Add Button --}}
                                <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-xs sm:text-sm font-mono font-black text-brand-blue">
                                        {{ $menu->formatted_price }}
                                    </span>
                                    <div class="flex items-center gap-1">
                                        <button type="button" wire:click="openDetail({{ $menu->id }})"
                                            class="hidden sm:inline-flex px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold rounded-lg transition cursor-pointer"
                                            title="Kustomisasi">
                                            Pilih
                                        </button>
                                        <button type="button" wire:click="quickAddToCart({{ $menu->id }})"
                                            class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-brand-blue hover:bg-[#003da1] text-white flex items-center justify-center font-black text-xs sm:text-sm shadow-xs active:scale-90 transition-all cursor-pointer"
                                            title="Tambah Cepat ke Keranjang">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty Search / Filter State --}}
                <div class="py-16 text-center max-w-md mx-auto text-slate-500">
                    <div
                        class="w-16 h-16 mx-auto rounded-3xl bg-slate-100 border border-slate-200 text-3xl flex items-center justify-center mb-3">
                        <x-icon name="search" class="w-8 h-8 text-slate-400 select-none" />
                    </div>
                    <h3 class="text-base font-bold text-slate-800">
                        Menu Tidak Ditemukan
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Tidak ada menu yang sesuai dengan kata kunci "<strong
                            class="text-brand-blue">{{ $searchQuery }}</strong>". Coba kata kunci lain.
                    </p>
                    <button type="button"
                        wire:click="$set('searchQuery', ''); selectCategory('all'); selectTaste('all')"
                        class="mt-4 px-4 py-2 rounded-full bg-brand-blue text-white text-xs font-bold hover:bg-[#003da1] transition-all cursor-pointer">
                        Tampilkan Semua Menu
                    </button>
                </div>
            @endif
        </main>

        {{-- RIGHT STICKY CART PANEL (360px Width - Desktop) --}}
        <aside class="hidden lg:flex w-90 bg-white border-l-[1.5px] border-[#E2E8F0] flex-col shrink-0 h-full z-20">
            {{-- Cart Header --}}
            <div class="p-5 px-6 border-b-[1.5px] border-slate-100 flex items-center justify-between">
                <div class="text-[16px] font-extrabold text-[#0F172A] flex items-center gap-2">
                    <span>🛒</span>
                    <span>Pesanan Meja {{ $tableNumber ?: '03' }}</span>
                </div>
                <span class="text-[12px] font-bold text-brand-blue bg-[#DBEAFE] px-2 py-0.5 rounded-md">
                    {{ $cartCount }} Item
                </span>
            </div>

            {{-- Scrollable Cart Items List --}}
            <div class="flex-1 overflow-y-auto p-4 px-6 flex flex-col gap-3.5">
                @forelse ($cart as $itemKey => $item)
                    <div class="flex gap-3 items-start pb-3 border-b border-slate-100">
                        <div class="flex-1 min-w-0">
                            <div class="text-[13px] font-extrabold text-[#0F172A] truncate">
                                {{ $item['name'] }}
                            </div>

                            @if (!empty($item['options_summary']))
                                <div class="text-[11px] text-[#64748B] mt-0.5 truncate">
                                    Catatan: {{ $item['options_summary'] }}
                                </div>
                            @endif

                            <div class="text-[13px] font-extrabold text-brand-blue mt-1">
                                {{ $item['formatted_subtotal'] ?? ('Rp '.number_format((float) ($item['subtotal'] ?? 0), 0, ',', '.')) }}
                            </div>
                        </div>

                        {{-- Quantity Stepper Control --}}
                        <div
                            class="flex items-center gap-2 bg-[#F8FAFC] border border-[#CBD5E1] rounded-lg px-1.5 py-0.5 shrink-0 select-none">
                            <button type="button" wire:click="decreaseItem('{{ $itemKey }}')"
                                class="text-brand-blue font-extrabold text-sm px-1 cursor-pointer hover:opacity-75"
                                title="Kurangi">
                                -
                            </button>
                            <span class="text-[12px] font-extrabold w-4 text-center text-slate-800">
                                {{ $item['quantity'] }}
                            </span>
                            <button type="button" wire:click="increaseItem('{{ $itemKey }}')"
                                class="text-brand-blue font-extrabold text-sm px-1 cursor-pointer hover:opacity-75"
                                title="Tambah">
                                +
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center text-slate-400">
                        <div class="text-4xl mb-2">🛒</div>
                        <p class="text-xs font-bold text-slate-600">Keranjang Masih Kosong</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Pilih menu favorit Anda dengan menekan tombol (+)
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- Cart Summary Footer --}}
            <div class="p-5 px-6 border-t-[1.5px] border-[#E2E8F0] bg-[#F8FAFC]">
                {{-- Payment Method Selection (1: Cash, 2: QRIS) --}}
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-bold text-slate-700">Pilihan Pembayaran</label>
                        <span class="text-[11px] text-slate-400 font-medium">Pilih salah satu</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        {{-- 1. Cash --}}
                        <button type="button" wire:click="setPaymentMethod('cash')"
                            class="flex items-center gap-2.5 p-2.5 rounded-xl border text-left transition-all cursor-pointer {{ $paymentMethod === 'cash' ? 'border-brand-blue bg-blue-50/80 text-brand-blue ring-1 ring-brand-blue' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50' }}">
                            <div
                                class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $paymentMethod === 'cash' ? 'bg-brand-blue/10 text-brand-blue' : 'bg-slate-100 text-slate-500' }}">
                                <x-icon name="cash" class="w-4 h-4" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-bold leading-tight">Cash</div>
                                <div
                                    class="text-[10px] {{ $paymentMethod === 'cash' ? 'text-brand-blue/80' : 'text-slate-400' }} leading-tight truncate">
                                    Bayar di Kasir</div>
                            </div>
                            <div
                                class="w-3.5 h-3.5 rounded-full border flex items-center justify-center shrink-0 {{ $paymentMethod === 'cash' ? 'border-brand-blue' : 'border-slate-300' }}">
                                @if ($paymentMethod === 'cash')
                                    <div class="w-1.5 h-1.5 rounded-full bg-brand-blue"></div>
                                @endif
                            </div>
                        </button>

                        {{-- 2. QRIS --}}
                        <button type="button" wire:click="setPaymentMethod('qris')"
                            class="flex items-center gap-2.5 p-2.5 rounded-xl border text-left transition-all cursor-pointer {{ $paymentMethod === 'qris' ? 'border-brand-blue bg-blue-50/80 text-brand-blue ring-1 ring-brand-blue' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50' }}">
                            <div
                                class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $paymentMethod === 'qris' ? 'bg-brand-blue/10 text-brand-blue' : 'bg-slate-100 text-slate-500' }}">
                                <x-icon name="qris" class="w-4 h-4" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-bold leading-tight">QRIS</div>
                                <div
                                    class="text-[10px] {{ $paymentMethod === 'qris' ? 'text-brand-blue/80' : 'text-slate-400' }} leading-tight truncate">
                                    Scan E-Wallet</div>
                            </div>
                            <div
                                class="w-3.5 h-3.5 rounded-full border flex items-center justify-center shrink-0 {{ $paymentMethod === 'qris' ? 'border-brand-blue' : 'border-slate-300' }}">
                                @if ($paymentMethod === 'qris')
                                    <div class="w-1.5 h-1.5 rounded-full bg-brand-blue"></div>
                                @endif
                            </div>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between text-[13px] text-[#64748B] mb-2 font-medium">
                    <span>Subtotal</span>
                    <span>{{ $formattedCartSubtotal }}</span>
                </div>
                <div class="flex justify-between text-[13px] text-[#64748B] mb-2 font-medium">
                    <span>PB1 (Pajak Resto 10%)</span>
                    <span>{{ $formattedCartTax }}</span>
                </div>
                <div
                    class="flex justify-between text-[16px] font-black text-[#0F172A] mt-2.5 pt-2.5 border-t border-dashed border-[#CBD5E1]">
                    <span>Total Tagihan</span>
                    <span class="text-brand-blue">{{ $formattedCartTotal }}</span>
                </div>

                <button type="button" wire:click="checkoutOrder" wire:loading.attr="disabled" @disabled(empty($cart)) id="btn-checkout-cart"
                    class="w-full h-12 bg-brand-blue hover:bg-[#003da1] disabled:opacity-40 text-white rounded-full text-[14px] font-extrabold flex items-center justify-center gap-2 mt-4 transition-all shadow-md active:scale-98 cursor-pointer disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="checkoutOrder">Lanjut ke Checkout ➔</span>
                    <span wire:loading.inline-flex wire:target="checkoutOrder" class="inline-flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4 animate-spin text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span>Memproses Pesanan...</span>
                    </span>
                </button>
            </div>
        </aside>
    </div>

    {{-- 3. MOBILE FLOATING CART BAR (Screen < lg) --}}
    @if ($cartCount > 0)
        <aside class="lg:hidden fixed bottom-4 inset-x-4 z-40">
            <button type="button" wire:click="openCart"
                class="w-full py-3.5 px-5 rounded-full bg-brand-blue text-white font-extrabold shadow-xl shadow-brand-blue/30 flex items-center justify-between active:scale-98 transition-all cursor-pointer">
                <div class="flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">
                        {{ $cartCount }}
                    </span>
                    <span class="text-xs">Lihat Keranjang Pesanan</span>
                </div>
                <div class="flex items-center gap-1.5 text-sm">
                    <span>{{ $formattedCartTotal }}</span>
                    <span>➔</span>
                </div>
            </button>
        </aside>
    @endif

    {{-- 4. MOBILE SLIDE-OVER CART DRAWER --}}
    @include('livewire.reuse-component.CartDrawer-modal')

    {{-- 5. BARISTA AI VIRTUAL CONSULTATION MODAL (04_web_ai_barista.html) --}}
    @include('livewire.reuse-component.AiBarista-modal')

    {{-- 6. MENU CUSTOMIZATION DETAIL MODAL --}}
    @include('livewire.user.MenuDetail-modal')

    {{-- 7. ORDER SUCCESS MODAL --}}
    @include('livewire.reuse-component.OrderSuccess-modal')

    {{-- 8. TOAST NOTIFICATION --}}
    <div x-show="toast" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" x-cloak
        class="fixed top-5 right-5 z-60 max-w-sm bg-slate-900 text-white text-xs px-4 py-3 rounded-2xl shadow-xl flex items-center gap-2.5 pointer-events-none">
        <span class="text-emerald-400 text-base">✓</span>
        <span x-text="toastMsg" class="font-medium"></span>
    </div>
</div>
