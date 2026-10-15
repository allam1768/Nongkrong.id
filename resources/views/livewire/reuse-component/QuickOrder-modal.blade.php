{{-- Quick Order Full Screen Modal (Pesan Cepat) - Lively & Smart Edition --}}
<div
    x-data="{
        open: @entangle('showQuickOrderModal'),
        quickTags: ['☕ Kak ', '⚡ Bos Kopi', '🥐 Si Santai', '🎧 Chill Mode'],
        selectTag(tag) {
            let current = $wire.get('customerName') || '';
            if (tag.startsWith('☕ Kak ')) {
                if (!current.startsWith('Kak ')) {
                    $wire.set('customerName', ('Kak ' + current).trim());
                }
            } else {
                $wire.set('customerName', tag.replace(/^[^\s]+\s/, ''));
            }
        }
    }"
    x-show="open"
    x-cloak
    @keydown.escape.window="open = false; $wire.closeQuickOrder()"
    class="fixed inset-0 z-50 flex items-center justify-center p-0 sm:p-4 md:p-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="quick-order-title"
>
    {{-- Backdrop Overlay (Only visible on tablet/desktop centered dialog) --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false; $wire.closeQuickOrder()"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity hidden sm:block"
    ></div>

    {{-- Dialog Box: Full Screen on Mobile, Centered Modal on Tablet/Desktop --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
        class="relative w-full h-full sm:h-auto min-h-dvh sm:min-h-0 sm:max-w-md bg-white rounded-none sm:rounded-3xl shadow-none sm:shadow-2xl sm:border-2 border-slate-200 z-10 transition-all select-auto flex flex-col justify-between p-5 sm:p-7 overflow-y-auto"
        @click.stop
    >
        {{-- Subtle Flat Decorative Star Accents (Pure Flat Solid #004AAD, Zero Gradient) --}}
        <div class="absolute -top-3 -right-3 w-16 h-16 pointer-events-none opacity-20 select-none hidden sm:block rotate-12">
            <img src="{{ asset('assets/img/Star-ic.svg') }}" alt="" class="w-full h-full" />
        </div>
        <div class="absolute -bottom-4 -left-4 w-20 h-20 pointer-events-none opacity-15 select-none hidden sm:block -rotate-12">
            <img src="{{ asset('assets/img/Star-ic.svg') }}" alt="" class="w-full h-full" />
        </div>

        {{-- Top Navigation Bar --}}
        <div class="flex items-center justify-between w-full shrink-0 pt-1 sm:pt-0 relative z-20">
            {{-- Left: Mobile Back to Table Selection Button --}}
            <div class="flex items-center justify-start min-w-[95px]">
                <button
                    type="button"
                    wire:click="backToTableModal"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 active:scale-95 transition-all cursor-pointer font-poppins text-xs font-semibold whitespace-nowrap border border-slate-200"
                    aria-label="Ganti Meja"
                >
                    <svg class="w-3.5 h-3.5 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Ganti Meja</span>
                </button>
            </div>

            {{-- Center: Logo NONGKRONG.id --}}
            <div class="flex-1 flex items-center justify-center select-none px-2">
                <img
                    src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                    alt="NONGKRONG.id"
                    class="h-6 sm:h-7 w-auto object-contain select-none"
                />
            </div>

            {{-- Right: Close Button --}}
            <div class="flex items-center justify-end min-w-[95px]">
                <button
                    type="button"
                    wire:click="closeQuickOrder"
                    @click="open = false"
                    class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center active:scale-90 transition-all cursor-pointer focus:outline-none border border-slate-200"
                    title="Tutup"
                    aria-label="Tutup"
                >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Center Content: Table Status, Mas Nongki Speech Card, Form & Steps --}}
        <div class="flex-1 flex flex-col justify-center max-w-sm mx-auto w-full py-4 sm:py-3 relative z-10">

            {{-- 1. Smart Table & Facility Status Strip --}}
            <div class="w-full bg-blue-50/80 border-2 border-blue-200 rounded-2xl px-3.5 py-2 flex items-center justify-between mb-3 select-none">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-brand-blue font-poppins">
                        Meja {{ $selectedTableNumber ?? '01' }} &bull; Area Indoor
                    </span>
                </div>
                <div class="flex items-center gap-1.5 text-[10px] font-semibold text-slate-600 font-poppins">
                    <span class="bg-white px-2 py-0.5 rounded-lg border border-slate-200">📶 Free WiFi</span>
                    <span class="bg-white px-2 py-0.5 rounded-lg border border-slate-200">⚡ Stopkontak</span>
                </div>
            </div>

            {{-- 2. Mas Nongki (Barista AI) Friendly Speech Greeting Card --}}
            <div class="w-full bg-white border-2 border-slate-200 rounded-2xl p-3 flex items-center gap-3 mb-3 select-none shadow-xs">
                <div class="relative w-14 h-14 rounded-2xl bg-blue-50 border-2 border-brand-blue flex items-center justify-center shrink-0">
                    <img
                        src="{{ asset('assets/img/icont_noks-blue.svg') }}"
                        alt="Mas Nongki"
                        class="w-8 h-8 object-contain"
                    />
                    {{-- Mini Star on Mascot --}}
                    <div class="absolute -top-1.5 -right-1.5 w-4 h-4">
                        <img src="{{ asset('assets/img/Star-ic.svg') }}" alt="" class="w-full h-full" />
                    </div>
                </div>
                <div class="flex-1 text-left">
                    <div class="flex items-center gap-1 text-[10px] font-extrabold text-brand-blue font-poppins uppercase tracking-wider">
                        <span>✨ Mas Nongki (Barista AI)</span>
                    </div>
                    <p class="text-xs font-semibold text-slate-800 font-poppins leading-snug mt-0.5">
                        "Kenalan dulu yuk! Nama kamu siapa? Biar pesanan kopimu gak ketukar."
                    </p>
                </div>
            </div>

            {{-- 3. Title & Subtitle --}}
            <div class="text-center mb-3">
                <h3 id="quick-order-title" class="text-2xl sm:text-[26px] font-extrabold font-poppins text-slate-900 tracking-tight leading-tight">
                    Siapa Nama Kamu?
                </h3>
                <p class="text-xs text-slate-500 font-poppins mt-1 max-w-xs mx-auto leading-relaxed">
                    Dipakai barista untuk panggil nama saat pesanan siap diantar.
                </p>
            </div>

            {{-- 4. Interactive Form & Quick Chips --}}
            <form wire:submit="submitQuickOrder" class="w-full flex flex-col gap-2.5">
                {{-- Input Field Container with User Icon & Inline Quick Submit --}}
                <div class="relative w-full flex items-center">
                    {{-- User Avatar Icon --}}
                    <div class="absolute left-3.5 pointer-events-none text-brand-blue flex items-center justify-center">
                        <svg class="w-5 h-5 text-brand-blue" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>

                    {{-- Name Input --}}
                    <input
                        type="text"
                        wire:model="customerName"
                        id="input-customer-name"
                        placeholder="Ketik nama panggilanmu (misal: Allam)"
                        autocomplete="name"
                        autofocus
                        class="w-full h-12.5 pl-11 pr-13 rounded-2xl bg-slate-50 hover:bg-slate-100/70 focus:bg-white border-2 border-slate-200 focus:border-brand-blue focus:outline-none text-slate-900 placeholder:text-slate-400 font-poppins text-sm font-semibold transition-all"
                    />

                    {{-- Quick Send Paper Airplane Icon Button inside Input --}}
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="absolute right-2 w-9 h-9 rounded-xl bg-brand-blue hover:bg-[#003da1] text-white flex items-center justify-center active:scale-95 transition-all cursor-pointer shadow-sm select-none"
                        title="Kirim"
                    >
                        <img
                            src="{{ asset('assets/icn/ci_paper-plane.svg') }}"
                            alt="Kirim"
                            wire:loading.remove
                            wire:target="submitQuickOrder"
                            class="w-4 h-4 select-none brightness-0 invert"
                        />
                        <svg
                            wire:loading
                            wire:target="submitQuickOrder"
                            class="w-4 h-4 text-white animate-spin"
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>

                {{-- Validation Feedback --}}
                @error('customerName')
                    <p class="text-xs text-rose-500 font-poppins font-medium text-left pl-1 -mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror

                {{-- Fun Interactive Chips (Panggilan Cepat) --}}
                <div class="w-full flex flex-col items-start gap-1.5 pt-0.5">
                    <span class="text-[11px] font-bold text-slate-600 font-poppins flex items-center gap-1">
                        <span>💡 Panggilan Cepat:</span>
                    </span>
                    <div class="flex items-center gap-1.5 w-full overflow-x-auto pb-1 no-scrollbar">
                        <button
                            type="button"
                            @click="selectTag('☕ Kak ')"
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-slate-50 hover:bg-blue-50 border-1.5 border-slate-200 hover:border-brand-blue text-xs font-semibold text-slate-700 hover:text-brand-blue transition-all shrink-0 cursor-pointer"
                        >
                            <span>☕ +Kak</span>
                        </button>
                        <button
                            type="button"
                            @click="$wire.set('customerName', 'Bos Kopi')"
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-slate-50 hover:bg-blue-50 border-1.5 border-slate-200 hover:border-brand-blue text-xs font-semibold text-slate-700 hover:text-brand-blue transition-all shrink-0 cursor-pointer"
                        >
                            <span>⚡ Bos Kopi</span>
                        </button>
                        <button
                            type="button"
                            @click="$wire.set('customerName', 'Si Santai')"
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-slate-50 hover:bg-blue-50 border-1.5 border-slate-200 hover:border-brand-blue text-xs font-semibold text-slate-700 hover:text-brand-blue transition-all shrink-0 cursor-pointer"
                        >
                            <span>🥐 Si Santai</span>
                        </button>
                        <button
                            type="button"
                            @click="$wire.set('customerName', 'Chill Mode')"
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-slate-50 hover:bg-blue-50 border-1.5 border-slate-200 hover:border-brand-blue text-xs font-semibold text-slate-700 hover:text-brand-blue transition-all shrink-0 cursor-pointer"
                        >
                            <span>🎧 Chill</span>
                        </button>
                    </div>
                </div>

                {{-- Primary CTA Full-Width Button --}}
                <button
                    type="submit"
                    id="btn-submit-quick-order"
                    wire:loading.attr="disabled"
                    class="w-full h-12.5 bg-brand-blue hover:bg-[#003da1] text-white font-bold font-poppins text-sm rounded-2xl shadow-md shadow-brand-blue/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75 select-none mt-0.5"
                >
                    <span wire:loading.remove wire:target="submitQuickOrder" class="flex items-center gap-2">
                        <span>Lanjut Pilih Menu</span>
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </span>
                    <span wire:loading.inline-flex wire:target="submitQuickOrder" class="inline-flex items-center gap-2 text-white whitespace-nowrap">
                        <svg class="w-5 h-5 animate-spin shrink-0 inline-block" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Menyiapkan Meja...</span>
                    </span>
                </button>

                {{-- 5. 3 Mini Steps Grid (How it works) --}}
                <div class="w-full grid grid-cols-3 gap-2 mt-1 select-none">
                    <div class="bg-blue-50/70 border-1.5 border-blue-200 rounded-xl py-1.5 px-1 flex flex-col items-center text-center">
                        <span class="text-[9px] font-extrabold text-brand-blue bg-white border border-blue-200 rounded-full px-1.5 py-0.2">1</span>
                        <span class="text-[10px] font-bold text-slate-900 mt-0.5">Ketik Nama</span>
                        <span class="text-[8px] text-slate-500">Identitas Meja</span>
                    </div>
                    <div class="bg-slate-50 border-1.5 border-slate-200 rounded-xl py-1.5 px-1 flex flex-col items-center text-center">
                        <span class="text-[9px] font-bold text-slate-400 bg-white border border-slate-200 rounded-full px-1.5 py-0.2">2</span>
                        <span class="text-[10px] font-bold text-slate-700 mt-0.5">Pilih Menu</span>
                        <span class="text-[8px] text-slate-400">Kopi & Camilan</span>
                    </div>
                    <div class="bg-slate-50 border-1.5 border-slate-200 rounded-xl py-1.5 px-1 flex flex-col items-center text-center">
                        <span class="text-[9px] font-bold text-slate-400 bg-white border border-slate-200 rounded-full px-1.5 py-0.2">3</span>
                        <span class="text-[10px] font-bold text-slate-700 mt-0.5">Duduk Santai</span>
                        <span class="text-[8px] text-slate-400">Pesanan Diantar</span>
                    </div>
                </div>

            </form>
        </div>

        {{-- Bottom Footer Note (Mobile only) --}}
        <div class="w-full text-center py-1 sm:hidden text-[11px] text-slate-400 font-poppins select-none shrink-0 relative z-10 flex items-center justify-center gap-1.5">
            <span>NONGKRONG.id</span>
            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
            <span>Smart Cafe Self Order</span>
            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
            <span>Meja {{ $selectedTableNumber ?? '01' }}</span>
        </div>
    </div>
</div>
