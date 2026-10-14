<div
    class="min-h-screen bg-white text-slate-900 relative overflow-hidden flex flex-col justify-between px-4 sm:px-6 lg:px-12 py-5 sm:py-7 select-auto"
>
    {{-- Aksen Dekorasi Bintang Biru (public/assets/img/Star-ic.svg) --}}
    {{-- 1. Bintang Atas Tengah-Kiri (Sesuai referensi: terpotong tepi atas sedikit miring ke kiri) --}}
    <img
        src="{{ asset('assets/img/Star-ic.svg') }}"
        alt=""
        aria-hidden="true"
        class="absolute -top-7 sm:-top-10 md:-top-12 left-[27%] w-16 sm:w-20 md:w-24 -rotate-12 pointer-events-none select-none drop-shadow-xs transition-transform duration-700 hover:rotate-12"
    />

    {{-- 2. Bintang Pojok Kanan Atas (Sesuai referensi: terpotong sudut kanan atas) --}}
    <img
        src="{{ asset('assets/img/Star-ic.svg') }}"
        alt=""
        aria-hidden="true"
        class="absolute -top-6 sm:-top-8 -right-6 sm:-right-8 w-20 sm:w-28 md:w-34 rotate-12 pointer-events-none select-none drop-shadow-xs transition-transform duration-700 hover:scale-105"
    />

    {{-- 3. Bintang Tepi Kiri Tengah (Sesuai referensi: terpotong tepi kiri, rotasi ke kanan bawah) --}}
    <img
        src="{{ asset('assets/img/Star-ic.svg') }}"
        alt=""
        aria-hidden="true"
        class="absolute top-[28%] -left-7 sm:-left-9 md:-left-12 w-16 sm:w-22 md:w-28 -rotate-45 pointer-events-none select-none drop-shadow-xs transition-transform duration-700 hover:rotate-0"
    />

    {{-- 4. Bintang Bawah Tengah-Kiri (Sesuai referensi: terpotong tepi bawah, agak miring) --}}
    <img
        src="{{ asset('assets/img/Star-ic.svg') }}"
        alt=""
        aria-hidden="true"
        class="absolute -bottom-8 sm:-bottom-10 md:-bottom-13 left-[27%] w-18 sm:w-22 md:w-28 rotate-12 pointer-events-none select-none drop-shadow-xs transition-transform duration-700 hover:scale-110"
    />

    {{-- 5. Bintang Pojok Kanan Bawah (Sesuai referensi: terpotong tepi kanan bawah) --}}
    <img
        src="{{ asset('assets/img/Star-ic.svg') }}"
        alt=""
        aria-hidden="true"
        class="absolute -bottom-6 sm:-bottom-8 -right-6 sm:-right-8 w-20 sm:w-28 md:w-34 -rotate-12 pointer-events-none select-none drop-shadow-xs transition-transform duration-700 hover:-rotate-45"
    />

    {{-- Top Header: Brand Logo & Customer Greeting --}}
    <header class="w-full flex flex-col items-center justify-center pt-1 sm:pt-3 z-20">
        {{-- Centered Logo NONGKRONG.id --}}
        <a
            href="{{ route('portal') }}"
            wire:navigate
            class="inline-block transition-transform duration-200 hover:scale-105 focus:outline-none"
            title="Kembali ke Beranda Portal"
        >
            <img
                src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                alt="NONGKRONG.id"
                class="h-8 sm:h-11 md:h-13 w-auto object-contain select-none"
            />
        </a>

        {{-- Customer Badge or Step Indicator --}}
        @if ($customerName && $customerName !== 'Tamu')
            <div class="mt-2.5 inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-slate-50 border border-slate-200/80 shadow-2xs text-xs font-poppins text-slate-600 transition-all">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Halo, <strong class="text-brand-blue font-semibold">{{ $customerName }}</strong></span>
                <span class="text-slate-300">|</span>
                <button
                    type="button"
                    wire:click="changeName"
                    class="text-slate-400 hover:text-brand-blue font-medium text-[11px] underline underline-offset-2 cursor-pointer transition-colors"
                    title="Ganti nama pemesan"
                >
                    Ganti Nama
                </button>
            </div>
        @else
            <div class="mt-2.5 inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-50/80 border border-blue-100 text-xs font-poppins text-brand-blue transition-all">
                <span class="inline-block w-2 h-2 rounded-full bg-brand-blue animate-pulse"></span>
                <span class="font-medium">Langkah 1: Scan QR atau Masukkan Kode Meja</span>
            </div>
        @endif
    </header>

    {{-- Main Dual-Option Section (QR Scan on Left, 'OR' in Middle, Table Code on Right) --}}
    <main class="w-full max-w-5xl mx-auto flex-1 flex flex-col md:flex-row items-center justify-center gap-8 sm:gap-10 md:gap-14 lg:gap-20 py-6 sm:py-10 z-20">
        {{-- SISI KIRI: Scan QR Code Meja --}}
        <div class="flex flex-col items-center justify-center w-full max-w-xs sm:max-w-sm md:max-w-md">
            {{-- Big Square Container with Thick Rounded Blue Border --}}
            <div
                class="w-56 h-56 sm:w-68 sm:h-68 md:w-76 md:h-76 aspect-square rounded-[34px] sm:rounded-[42px] border-4 sm:border-[5px] border-brand-blue bg-white p-4 sm:p-6 flex flex-col items-center justify-center relative shadow-xl shadow-brand-blue/10 transition-all duration-300 hover:shadow-2xl hover:shadow-brand-blue/20 group select-none"
            >
                {{-- Decorative Interactive QR Laser Scan Line --}}
                <div
                    class="absolute inset-x-6 top-6 h-0.5 bg-linear-to-r from-transparent via-brand-blue to-transparent opacity-75 animate-pulse pointer-events-none"
                    style="animation: scanMove 3s ease-in-out infinite alternate;"
                ></div>

                {{-- QR Code Visual (Sharp Vector SVG QR Code with Cafe Center Brand Icon) --}}
                <div class="relative w-full h-full flex items-center justify-center p-2">
                    <svg
                        class="w-full h-full text-slate-900 object-contain"
                        viewBox="0 0 160 160"
                        fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        {{-- Top-Left Finder Pattern --}}
                        <path d="M10 10h40v40H10zM18 18v24h24V18H18zm6 6h12v12H24z" />
                        {{-- Top-Right Finder Pattern --}}
                        <path d="M110 10h40v40h-40zM118 18v24h24V18h-24zm6 6h12v12h-12z" />
                        {{-- Bottom-Left Finder Pattern --}}
                        <path d="M10 110h40v40H10zM18 118v24h24v-24H18zm6 6h12v12H24z" />
                        {{-- Data Modules & Timing Pattern (Artistic Cafe QR layout) --}}
                        <path d="M60 15h8v8h-8zM80 15h8v8h-8zM95 15h6v8h-6zM15 60h8v8h-8zM15 80h8v8h-8zM15 95h8v6h-8z" />
                        <path d="M30 60h8v8h-8zM30 80h8v8h-8zM30 95h8v6h-8zM45 60h8v8h-8zM45 75h8v8h-8z" />
                        <path d="M60 30h8v8h-8zM60 45h8v8h-8zM75 30h8v8h-8zM90 30h8v8h-8zM90 45h8v8h-8z" />
                        <path d="M60 60h12v12H60zM88 60h12v12H88zM60 88h12v12H60zM88 88h12v12H88z" />
                        <path d="M110 60h10v10h-10zM135 60h15v8h-15zM120 75h10v10h-10zM140 75h10v10h-10z" />
                        <path d="M110 90h15v10h-15zM130 90h10v10h-10zM110 110h10v10h-10zM125 110h10v10h-10zM140 110h10v10h-10z" />
                        <path d="M110 130h10v10h-10zM125 125h25v8h-25zM135 140h15v10h-15zM60 110h8v15h-8zM75 110h8v8h-8zM90 110h10v10H90z" />
                        <path d="M60 135h15v15H60zM85 130h15v8H85zM80 142h20v8H80zM35 135h8v8h-8zM45 125h8v8h-8zM45 142h8v8h-8z" />
                    </svg>

                    {{-- Center Brand Badge (Cafe cup mascot in QR center) --}}
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white rounded-xl shadow-md border-2 border-brand-blue flex items-center justify-center p-1">
                            <span class="text-brand-blue font-bold text-xs sm:text-sm font-sora">N.ID</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action & Instructions Below QR (Design Improvement) --}}
            <div class="mt-4 flex flex-col items-center text-center">
                <button
                    type="button"
                    wire:click="openScanner"
                    id="btn-open-scanner"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-brand-blue text-xs sm:text-sm font-poppins font-medium transition-all active:scale-95 cursor-pointer shadow-2xs"
                >
                    <svg class="w-4 h-4 text-brand-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Buka Kamera Scanner</span>
                </button>
                <p class="mt-2 text-[11px] sm:text-xs text-slate-400 font-poppins">
                    Arahkan kamera ke stiker QR code di atas meja Anda
                </p>
            </div>
        </div>

        {{-- TENGAH: Pemisah "OR" --}}
        <div class="flex md:flex-col items-center justify-center gap-3 w-full md:w-auto my-1 md:my-0 select-none">
            <span class="hidden md:block w-px h-16 bg-slate-200"></span>
            <div class="flex items-center gap-3 md:gap-0 w-full md:w-auto justify-center">
                <span class="md:hidden h-px bg-slate-200 flex-1 max-w-20"></span>
                <span class="font-poppins font-bold text-base sm:text-lg text-slate-800 tracking-wider px-2">
                    OR
                </span>
                <span class="md:hidden h-px bg-slate-200 flex-1 max-w-20"></span>
            </div>
            <span class="hidden md:block w-px h-16 bg-slate-200"></span>
        </div>

        {{-- SISI KANAN: Enter Your Table Code ! --}}
        <div class="flex flex-col items-center md:items-start w-full max-w-sm sm:max-w-md">
            {{-- Title: Enter your table code ! --}}
            <h2 class="text-xl sm:text-2xl md:text-[26px] font-bold font-poppins text-slate-900 tracking-tight text-center md:text-left">
                Enter your table code !
            </h2>

            <p class="mt-1 text-xs sm:text-sm text-slate-500 font-poppins text-center md:text-left">
                Masukkan 5 digit kode yang tertera di stiker mejamu
            </p>

            {{-- 5-Digit Code Input Form --}}
            <form
                wire:submit.prevent="confirmTableCode"
                x-data="{
                    digits: ['', '', '', '', ''],
                    init() {
                        const initial = $wire.tableCode || '';
                        if (initial.length > 0) {
                            for (let i = 0; i < 5; i++) {
                                this.digits[i] = initial[i] || '';
                            }
                        }
                        this.$watch('$wire.tableCode', (val) => {
                            if (val && val.length > 0) {
                                for (let i = 0; i < 5; i++) {
                                    this.digits[i] = val[i] || '';
                                }
                            }
                        });
                    },
                    handleInput(index, event) {
                        const raw = event.target.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
                        this.digits[index] = raw ? raw[raw.length - 1] : '';
                        event.target.value = this.digits[index];
                        this.syncLivewire();

                        // Auto advance to next box
                        if (this.digits[index] && index < 4) {
                            this.$nextTick(() => {
                                const nextRef = this.$refs['box' + (index + 1)];
                                if (nextRef) {
                                    nextRef.focus();
                                    nextRef.select();
                                }
                            });
                        }
                    },
                    handleKeydown(index, event) {
                        if (event.key === 'Backspace') {
                            if (!this.digits[index] && index > 0) {
                                const prevRef = this.$refs['box' + (index - 1)];
                                if (prevRef) {
                                    prevRef.focus();
                                    this.digits[index - 1] = '';
                                    this.syncLivewire();
                                }
                            } else {
                                this.digits[index] = '';
                                this.syncLivewire();
                            }
                        } else if (event.key === 'ArrowLeft' && index > 0) {
                            this.$refs['box' + (index - 1)].focus();
                        } else if (event.key === 'ArrowRight' && index < 4) {
                            this.$refs['box' + (index + 1)].focus();
                        }
                    },
                    handlePaste(event) {
                        event.preventDefault();
                        const pasteData = (event.clipboardData || window.clipboardData).getData('text') || '';
                        const clean = pasteData.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
                        if (clean.length > 0) {
                            for (let i = 0; i < 5; i++) {
                                this.digits[i] = clean[i] || '';
                            }
                            this.syncLivewire();
                            const focusIdx = Math.min(clean.length, 4);
                            this.$nextTick(() => {
                                const target = this.$refs['box' + focusIdx];
                                if (target) target.focus();
                            });
                        }
                    },
                    syncLivewire() {
                        const code = this.digits.join('');
                        $wire.set('tableCode', code);
                    }
                }"
                class="w-full mt-6 sm:mt-7"
            >
                {{-- 5 Kotak Input Digit Terpisah [ ][ ][ ][ ][ ] --}}
                <div class="flex items-center justify-center md:justify-start gap-2 sm:gap-3">
                    <template x-for="(digit, idx) in digits" :key="idx">
                        <input
                            type="text"
                            maxlength="1"
                            :x-ref="'box' + idx"
                            :value="digits[idx]"
                            @focus="$event.target.select()"
                            @input="handleInput(idx, $event)"
                            @keydown="handleKeydown(idx, $event)"
                            @paste="handlePaste($event)"
                            class="w-11 h-14 sm:w-13 sm:h-16 md:w-14 md:h-16 rounded-xl sm:rounded-2xl border-2 border-brand-blue text-center font-sora font-bold text-xl sm:text-2xl text-brand-blue bg-white focus:outline-none focus:ring-4 focus:ring-brand-blue/20 transition-all select-none shadow-2xs"
                            autocomplete="off"
                            inputmode="text"
                            :id="'input-table-digit-' + idx"
                        />
                    </template>
                </div>

                {{-- Confirm Button --}}
                <div class="w-full mt-5 sm:mt-6">
                    <x-button
                        size="lg"
                        type="submit"
                        id="btn-confirm-table"
                        class="w-full text-sm sm:text-base font-bold shadow-lg shadow-brand-blue/30"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="confirmTableCode">Confirm</span>
                        <span wire:loading wire:target="confirmTableCode" class="inline-flex items-center gap-2">
                            <svg class="w-5 h-5 text-white animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Memeriksa...</span>
                        </span>
                    </x-button>
                </div>

                {{-- Validation Feedback Error --}}
                @error('tableCode')
                    <div class="mt-3.5 p-3 rounded-xl bg-red-50 border border-red-200/80 text-red-600 text-xs font-poppins flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="flex-1">{{ $message }}</span>
                    </div>
                @enderror
            </form>
        </div>
    </main>

    {{-- Bottom Minimal Footer --}}
    <footer class="w-full text-center py-2 text-[11px] text-neutral-300 font-sora z-20">
        &copy; {{ $currentYear }} NONGKRONG.id &bull; Smart Cafe Ordering System
    </footer>

    {{-- Success Confirmation Modal Popup --}}
    @if ($showSuccessModal)
        <div
            x-data
            class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-6"
            role="dialog"
            aria-modal="true"
        >
            {{-- Backdrop --}}
            <div
                wire:click="resetTable"
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"
            ></div>

            {{-- Content Card: Mobile Bottom Sheet, Desktop Centered Card --}}
            <div class="relative w-full max-w-sm sm:max-w-md bg-white rounded-t-3xl sm:rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-100 z-10 text-center animate-sheet-up sm:animate-none">
                {{-- Handle Bar for Mobile Sheet --}}
                <div class="w-10 h-1 bg-slate-200 rounded-full mx-auto mb-4 block sm:hidden"></div>

                {{-- Success Icon with Star Accent --}}
                <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto rounded-3xl bg-blue-50 border-2 border-brand-blue/20 flex items-center justify-center mb-3 sm:mb-4 relative">
                    <img
                        src="{{ asset('assets/img/Star-ic.svg') }}"
                        alt=""
                        class="w-8 h-8 sm:w-10 sm:h-10 animate-bounce"
                    />
                </div>

                <h3 class="text-xl sm:text-2xl font-bold font-poppins text-slate-900">
                    Meja Berhasil Terhubung!
                </h3>

                <p class="mt-1 text-xs sm:text-sm text-slate-500 font-poppins">
                    Sesi meja aktif. Masukkan nama Anda untuk mulai memesan.
                </p>

                {{-- Table Details Pill --}}
                <div class="my-4 p-3.5 sm:p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                    <div class="text-left">
                        <span class="text-[11px] text-slate-400 font-poppins uppercase tracking-wider block">Nomor Meja</span>
                        <span class="text-lg sm:text-xl font-bold font-sora text-brand-blue">Meja {{ $selectedTableNumber }}</span>
                        @if ($selectedTableAccessCode)
                            <span class="text-[11px] text-slate-400 font-mono block mt-0.5">Kode: {{ $selectedTableAccessCode }}</span>
                        @endif
                    </div>
                    <div class="text-right">
                        <span class="text-[11px] text-slate-400 font-poppins uppercase tracking-wider block">Kapasitas</span>
                        <span class="text-sm font-semibold font-poppins text-slate-700">{{ $selectedTableCapacity ?? 4 }} Orang</span>
                    </div>
                </div>

                {{-- Form Input Nama Pemesan --}}
                <form wire:submit.prevent="proceedToOrder" class="w-full text-left">
                    <div class="mb-4">
                        <label for="input-customer-name" class="block text-xs font-semibold font-poppins text-slate-700 mb-1.5">
                            Nama Pemesan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="input-customer-name"
                                wire:model.defer="customerName"
                                placeholder="Masukkan nama Anda (misal: Rian)"
                                autofocus
                                class="w-full h-12 px-4 rounded-xl bg-slate-50 border border-slate-200 text-sm font-poppins text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition-all"
                            />
                        </div>
                        @error('customerName')
                            <span class="text-rose-500 text-xs font-poppins mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-2.5">
                        <x-button
                            type="submit"
                            size="lg"
                            id="btn-confirm-success"
                            class="w-full font-bold shadow-lg shadow-brand-blue/25"
                        >
                            Lanjut ke Menu Pemesanan
                        </x-button>

                        <button
                            type="button"
                            wire:click="resetTable"
                            class="text-xs text-slate-400 hover:text-slate-600 font-poppins py-1 transition-colors cursor-pointer text-center"
                        >
                            Ganti Pilihan Meja
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Scanner Camera Modal Popup --}}
    @if ($showScannerModal)
        <div
            x-data
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
            role="dialog"
            aria-modal="true"
        >
            {{-- Backdrop --}}
            <div
                wire:click="closeScanner"
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"
            ></div>

            {{-- Modal Body --}}
            <div class="relative w-full max-w-sm sm:max-w-md bg-white rounded-3xl p-6 sm:p-7 shadow-2xl border border-slate-100 z-10 text-center">
                <button
                    type="button"
                    wire:click="closeScanner"
                    class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-100 transition-colors cursor-pointer"
                    title="Tutup"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <div class="w-12 h-12 mx-auto rounded-2xl bg-blue-50 text-brand-blue flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </div>

                <h3 class="text-lg sm:text-xl font-bold font-poppins text-slate-900">
                    Scan QR Code Meja
                </h3>

                <p class="mt-1 text-xs text-slate-500 font-poppins">
                    Arahkan kamera perangkat Anda langsung ke QR code stiker meja.
                </p>

                {{-- Simulated Viewfinder Frame --}}
                <div class="my-5 w-48 h-48 mx-auto rounded-2xl border-2 border-dashed border-brand-blue relative flex items-center justify-center bg-slate-50 overflow-hidden">
                    <div class="absolute inset-x-2 top-2 h-0.5 bg-brand-blue animate-pulse" style="animation: scanMove 2.5s ease-in-out infinite alternate;"></div>
                    <span class="text-xs text-slate-400 font-poppins px-4 text-center">Menunggu deteksi QR code meja...</span>
                </div>

                {{-- Fast Simulator for Available Tables --}}
                <div class="bg-slate-50 rounded-2xl p-3 border border-slate-200/80 text-left">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block mb-2">Simulasikan Scan Cepat:</span>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach ($availableTables as $t)
                            <button
                                type="button"
                                wire:click="scanQrTable('{{ $t->qr_code_token ?? $t->table_number }}')"
                                class="px-2 py-2 rounded-xl bg-white hover:bg-brand-blue hover:text-white text-brand-blue text-xs font-semibold shadow-2xs border border-slate-200/80 transition-colors text-center cursor-pointer"
                            >
                                Meja {{ $t->table_number }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    @keyframes scanMove {
        0% { transform: translateY(0); opacity: 0.4; }
        50% { opacity: 1; }
        100% { transform: translateY(180px); opacity: 0.4; }
    }
</style>
