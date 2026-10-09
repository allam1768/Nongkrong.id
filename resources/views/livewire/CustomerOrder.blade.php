<div
    x-data="{
        showCart: @entangle('showCartDrawer'),
        toast: false,
        toastMsg: '',
        showToast(msg) {
            this.toastMsg = msg;
            this.toast = true;
            setTimeout(() => this.toast = false, 3500);
        }
    }"
    @toast-notify.window="showToast(@this.toastMessage)"
    class="min-h-screen lg:h-screen lg:overflow-hidden bg-[#F8FAFC] text-slate-900 flex flex-col font-sans selection:bg-[#004AAD] selection:text-white"
>
    {{-- 1. TOP NAVBAR (Identik Mockup 03_web_customer_catalog.html) --}}
    <header class="h-16 shrink-0 bg-white border-b-[1.5px] border-[#E2E8F0] sticky top-0 z-30 px-4 sm:px-8 flex items-center justify-between gap-4">
        {{-- Brand Logo --}}
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('customer.order') }}" wire:navigate class="inline-flex items-center transition-transform duration-200 hover:scale-105 focus:outline-none" title="NONGKRONG.id">
                <img
                    src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                    alt="NONGKRONG.id"
                    class="h-6 sm:h-7 md:h-8 w-auto max-w-[145px] sm:max-w-none object-contain select-none"
                />
            </a>
            <span class="sr-only">Digital Menu</span>
        </div>

        {{-- Center Pill Search Bar --}}
        <div class="flex-1 max-w-[440px] mx-4 hidden sm:flex items-center">
            <div class="w-full h-10 bg-[#F8FAFC] border-[1.5px] border-[#CBD5E1] rounded-full flex items-center px-4 gap-2.5 focus-within:border-[#004AAD] focus-within:bg-white transition-all">
                <span class="text-sm text-slate-400 select-none">🔍</span>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="searchQuery"
                    id="input-search-menu"
                    placeholder="Cari kopi, tea, makanan, atau snack..."
                    class="w-full bg-transparent border-none text-[13px] text-slate-800 placeholder-slate-400 focus:outline-none"
                    autocomplete="off"
                />
                @if ($searchQuery)
                    <button
                        type="button"
                        wire:click="$set('searchQuery', '')"
                        class="text-slate-400 hover:text-slate-600 text-xs cursor-pointer p-1"
                        title="Hapus pencarian"
                    >
                        ✕
                    </button>
                @endif
            </div>
        </div>

        {{-- Right Controls: Table Badge Pill & Mobile Cart Button --}}
        <div class="flex items-center gap-3 shrink-0">
            {{-- Table Tag Pill (#004AAD bg, white text, green dot) --}}
            <div class="inline-flex items-center gap-2 bg-[#004AAD] text-white text-[13px] font-extrabold px-4 sm:px-4.5 py-2 rounded-full shadow-sm select-none">
                <span class="w-2 h-2 rounded-full bg-[#22C55E]"></span>
                <span>Meja {{ $tableNumber ?: '03' }} (Indoor)</span>
                <span class="sr-only">{{ $customerName ?: 'Tamu' }}</span>
                <button
                    type="button"
                    wire:click="changeTable"
                    class="text-blue-200 hover:text-white ml-0.5 transition-colors cursor-pointer"
                    title="Ganti Meja (Pelanggan: {{ $customerName ?: 'Tamu' }})"
                >
                </button>
            </div>

            {{-- Mobile Cart Trigger (Visible on < lg) --}}
            <button
                type="button"
                wire:click="openCart"
                class="lg:hidden relative p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer"
                title="Lihat Keranjang Pesanan"
            >
                <span class="text-lg">🛒</span>
                @if ($cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-[#004AAD] text-white font-bold text-[10px] w-5 h-5 rounded-full flex items-center justify-center shadow-xs">
                        {{ $cartCount }}
                    </span>
                @endif
            </button>
        </div>
    </header>

    {{-- Mobile Search Row (Screen < sm) --}}
    <div class="sm:hidden px-4 pt-3 pb-2 bg-white border-b border-[#E2E8F0]">
        <div class="w-full h-10 bg-[#F8FAFC] border-[1.5px] border-[#CBD5E1] rounded-full flex items-center px-4 gap-2">
            <span class="text-xs text-slate-400">🔍</span>
            <input
                type="text"
                wire:model.live.debounce.300ms="searchQuery"
                placeholder="Cari kopi, tea, makanan, atau snack..."
                class="w-full bg-transparent border-none text-xs text-slate-800 placeholder-slate-400 focus:outline-none"
            />
            @if ($searchQuery)
                <button
                    type="button"
                    wire:click="$set('searchQuery', '')"
                    class="text-slate-400 hover:text-slate-600 text-xs p-1"
                >
                    ✕
                </button>
            @endif
        </div>
    </div>

    {{-- 2. SPLIT CONTENT LAYOUT (Left: Catalog, Right: 360px Sticky Cart) --}}
    <div class="flex-1 flex overflow-hidden bg-[#F8FAFC]">
        {{-- LEFT MAIN SCROLLABLE CATALOG --}}
        <main class="flex-1 overflow-y-auto px-4 sm:px-8 py-6 flex flex-col gap-5">
            {{-- AI HERO BANNER (#004AAD Deep Blue) --}}
            <div class="shrink-0 bg-[#004AAD] rounded-[20px] p-5 sm:p-7 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-2 border-[#003087] relative overflow-hidden shadow-sm">
                <div class="relative z-10 max-w-xl">
                    <div class="inline-flex items-center gap-1.5 bg-white text-[#004AAD] text-[11px] font-extrabold px-2.5 py-1 rounded-full mb-2 shadow-xs select-none">
                        COFFEE ASSISTANT
                    </div>
                    <h2 class="text-xl sm:text-[22px] font-black text-white leading-snug mb-1">
                        Bingung Mau Minum Apa Hari Ini?
                    </h2>
                    <p class="text-[13px] text-[#DBEAFE] leading-relaxed">
                        Pilih Menu Favoritmu atau ceritakan rasa & mood favoritmu, Barista AI kami akan meracikkan rekomendasi menu yang paling pas dengan seleramu!
                    </p>
                </div>

                <button
                    type="button"
                    wire:click="openAiModal"
                    class="relative z-10 shrink-0 bg-white hover:bg-blue-50 text-[#004AAD] font-extrabold text-[13px] px-6 py-3 rounded-full flex items-center gap-2 shadow-md hover:shadow-lg transition-all active:scale-95 cursor-pointer"
                >
                    <span>Tanya Barista AI</span>
                    <span>➔</span>
                </button>

                {{-- Decorative Star Watermark in Background --}}
                <img
                    src="{{ asset('assets/img/Star-ic.svg') }}"
                    alt=""
                    aria-hidden="true"
                    class="absolute -right-6 -bottom-6 w-32 h-32 opacity-15 pointer-events-none select-none"
                />
            </div>

            {{-- CATEGORIES TABS BAR --}}
            <div class="shrink-0 flex items-center gap-2.5 overflow-x-auto pb-1 scrollbar-none select-none">
                {{-- Tab: Semua Menu --}}
                <button
                    type="button"
                    wire:click="selectCategory('all')"
                    @class([
                        'px-4.5 py-2 rounded-full text-[13px] font-bold cursor-pointer whitespace-nowrap transition-all shadow-2xs',
                        'bg-[#004AAD] text-white border-[1.5px] border-[#004AAD]' => $selectedCategory === 'all',
                        'bg-white text-[#475569] border-[1.5px] border-[#CBD5E1] hover:bg-slate-50' => $selectedCategory !== 'all',
                    ])
                >
                    Semua Menu ({{ $menus->count() }})
                </button>

                @foreach ($categories as $cat)
                    <button
                        type="button"
                        wire:click="selectCategory('{{ $cat->slug }}')"
                        @class([
                            'px-4.5 py-2 rounded-full text-[13px] font-bold cursor-pointer whitespace-nowrap transition-all shadow-2xs',
                            'bg-[#004AAD] text-white border-[1.5px] border-[#004AAD]' => $selectedCategory === $cat->slug,
                            'bg-white text-[#475569] border-[1.5px] border-[#CBD5E1] hover:bg-slate-50' => $selectedCategory !== $cat->slug,
                        ])
                    >
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            {{-- MENU 3-COLUMN GRID --}}
            @if ($menus->count() > 0)
                <div class="shrink-0 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach ($menus as $menu)
                        <div
                            class="bg-white border-[1.5px] border-[#E2E8F0] hover:border-[#004AAD] rounded-[16px] p-3.5 flex flex-col justify-between gap-2.5 transition-all shadow-2xs hover:shadow-md group"
                            id="menu-card-{{ $menu->id }}"
                        >
                            {{-- Visual Image Placeholder with Taste Badge --}}
                            <div
                                wire:click="openDetail({{ $menu->id }})"
                                class="h-[120px] bg-[#EFF6FF] rounded-[12px] flex items-center justify-center relative border border-[#DBEAFE] cursor-pointer group-hover:bg-blue-50/80 transition-colors select-none"
                                title="Klik untuk sesuaikan opsi"
                            >
                                <span class="absolute top-2 left-2 bg-white text-[#004AAD] text-[10px] font-extrabold px-2 py-0.5 rounded-[6px] border border-[#BFDBFE] shadow-2xs">
                                    {{ $menu->flavor_tag }}
                                </span>
                                <span class="text-4xl group-hover:scale-110 transition-transform">
                                    {{ $menu->visual }}
                                </span>
                            </div>

                            {{-- Menu Information --}}
                            <div>
                                <h4
                                    wire:click="openDetail({{ $menu->id }})"
                                    class="text-[15px] font-extrabold text-[#0F172A] mb-1 line-clamp-1 cursor-pointer hover:text-[#004AAD] transition-colors"
                                >
                                    {{ $menu->name }}
                                </h4>
                                <p class="text-[12px] text-[#64748B] line-clamp-2 h-[34px] leading-relaxed">
                                    {{ $menu->description }}
                                </p>
                            </div>

                            {{-- Bottom Price & Quick Add Button --}}
                            <div class="flex items-center justify-between pt-1">
                                <span class="text-[15px] font-black text-[#004AAD]">
                                    {{ $menu->formatted_price }}
                                </span>
                                <button
                                    type="button"
                                    wire:click="quickAddToCart({{ $menu->id }})"
                                    class="w-[34px] h-[34px] bg-[#004AAD] hover:bg-[#003da1] text-white rounded-full font-bold flex items-center justify-center text-lg shadow-sm transition-transform active:scale-90 cursor-pointer"
                                    title="Tambah ke pesanan"
                                >
                                    +
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty Search / Filter State --}}
                <div class="py-16 text-center max-w-md mx-auto text-slate-500">
                    <div class="w-16 h-16 mx-auto rounded-3xl bg-slate-100 border border-slate-200 text-3xl flex items-center justify-center mb-3">
                        🔍
                    </div>
                    <h3 class="text-base font-bold text-slate-800">
                        Menu Tidak Ditemukan
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Tidak ada menu yang sesuai dengan kata kunci "<strong class="text-[#004AAD]">{{ $searchQuery }}</strong>". Coba kata kunci lain.
                    </p>
                    <button
                        type="button"
                        wire:click="$set('searchQuery', ''); selectCategory('all'); selectTaste('all')"
                        class="mt-4 px-4 py-2 rounded-full bg-[#004AAD] text-white text-xs font-bold hover:bg-[#003da1] transition-all cursor-pointer"
                    >
                        Tampilkan Semua Menu
                    </button>
                </div>
            @endif
        </main>

        {{-- RIGHT STICKY CART PANEL (360px Width - Desktop) --}}
        <aside class="hidden lg:flex w-[360px] bg-white border-l-[1.5px] border-[#E2E8F0] flex-col shrink-0 h-full z-20">
            {{-- Cart Header --}}
            <div class="p-5 px-6 border-b-[1.5px] border-slate-100 flex items-center justify-between">
                <div class="text-[16px] font-extrabold text-[#0F172A] flex items-center gap-2">
                    <span>🛒</span>
                    <span>Pesanan Meja {{ $tableNumber ?: '03' }}</span>
                </div>
                <span class="text-[12px] font-bold text-[#004AAD] bg-[#DBEAFE] px-2 py-0.5 rounded-[6px]">
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

                            <div class="text-[13px] font-extrabold text-[#004AAD] mt-1">
                                {{ $item['formatted_subtotal'] }}
                            </div>
                        </div>

                        {{-- Quantity Stepper Control --}}
                        <div class="flex items-center gap-2 bg-[#F8FAFC] border border-[#CBD5E1] rounded-[8px] px-1.5 py-0.5 shrink-0 select-none">
                            <button
                                type="button"
                                wire:click="decreaseItem('{{ $itemKey }}')"
                                class="text-[#004AAD] font-extrabold text-sm px-1 cursor-pointer hover:opacity-75"
                                title="Kurangi"
                            >
                                -
                            </button>
                            <span class="text-[12px] font-extrabold w-4 text-center text-slate-800">
                                {{ $item['quantity'] }}
                            </span>
                            <button
                                type="button"
                                wire:click="increaseItem('{{ $itemKey }}')"
                                class="text-[#004AAD] font-extrabold text-sm px-1 cursor-pointer hover:opacity-75"
                                title="Tambah"
                            >
                                +
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center text-slate-400">
                        <div class="text-4xl mb-2">🛒</div>
                        <p class="text-xs font-bold text-slate-600">Keranjang Masih Kosong</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Pilih menu favorit Anda dengan menekan tombol (+)</p>
                    </div>
                @endforelse
            </div>

            {{-- Cart Summary Footer --}}
            <div class="p-5 px-6 border-t-[1.5px] border-[#E2E8F0] bg-[#F8FAFC]">
                <div class="flex justify-between text-[13px] text-[#64748B] mb-2 font-medium">
                    <span>Subtotal</span>
                    <span>{{ $formattedCartSubtotal }}</span>
                </div>
                <div class="flex justify-between text-[13px] text-[#64748B] mb-2 font-medium">
                    <span>PB1 (Pajak Resto 10%)</span>
                    <span>{{ $formattedCartTax }}</span>
                </div>
                <div class="flex justify-between text-[16px] font-black text-[#0F172A] mt-2.5 pt-2.5 border-t border-dashed border-[#CBD5E1]">
                    <span>Total Tagihan</span>
                    <span class="text-[#004AAD]">{{ $formattedCartTotal }}</span>
                </div>

                <button
                    type="button"
                    wire:click="checkoutOrder"
                    @disabled(empty($cart))
                    id="btn-checkout-cart"
                    class="w-full h-12 bg-[#004AAD] hover:bg-[#003da1] disabled:opacity-40 text-white rounded-full text-[14px] font-extrabold flex items-center justify-center gap-2 mt-4 transition-all shadow-md active:scale-98 cursor-pointer disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="checkoutOrder">Lanjut ke Checkout ➔</span>
                    <span wire:loading wire:target="checkoutOrder" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin text-white" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
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
            <button
                type="button"
                wire:click="openCart"
                class="w-full py-3.5 px-5 rounded-full bg-[#004AAD] text-white font-extrabold shadow-xl shadow-[#004AAD]/30 flex items-center justify-between active:scale-98 transition-all cursor-pointer"
            >
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
    @include('livewire.partials.CartDrawer-modal')

    {{-- 5. BARISTA AI VIRTUAL CONSULTATION MODAL (04_web_ai_barista.html) --}}
    @include('livewire.partials.AiBarista-modal')

    {{-- 6. MENU CUSTOMIZATION DETAIL MODAL --}}
    @include('livewire.partials.MenuDetail-modal')

    {{-- 7. ORDER SUCCESS MODAL --}}
    @include('livewire.partials.OrderSuccess-modal')

    {{-- 8. TOAST NOTIFICATION --}}
    <div
        x-show="toast"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        x-cloak
        class="fixed top-5 right-5 z-60 max-w-sm bg-slate-900 text-white text-xs px-4 py-3 rounded-2xl shadow-xl flex items-center gap-2.5 pointer-events-none"
    >
        <span class="text-emerald-400 text-base">✓</span>
        <span x-text="toastMsg" class="font-medium"></span>
    </div>
</div>
