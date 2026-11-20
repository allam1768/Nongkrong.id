<div
    class="min-h-screen bg-[#FAFAFC] text-slate-900 relative overflow-hidden flex flex-col justify-between px-4 sm:px-6 lg:px-12 py-5 sm:py-7 select-auto"
>
    {{-- Aksen Bintang Biru Ringan & Estetik --}}
    <img
        src="{{ asset('assets/img/Star-ic.svg') }}"
        alt=""
        aria-hidden="true"
        class="absolute -top-7 sm:-top-10 left-[20%] w-16 sm:w-20 -rotate-12 pointer-events-none select-none opacity-40"
    />
    <img
        src="{{ asset('assets/img/Star-ic.svg') }}"
        alt=""
        aria-hidden="true"
        class="absolute -top-6 -right-6 w-20 sm:w-28 rotate-12 pointer-events-none select-none opacity-30"
    />
    <img
        src="{{ asset('assets/img/Star-ic.svg') }}"
        alt=""
        aria-hidden="true"
        class="absolute -bottom-8 left-[15%] w-18 sm:w-24 rotate-12 pointer-events-none select-none opacity-30"
    />
    <img
        src="{{ asset('assets/img/Star-ic.svg') }}"
        alt=""
        aria-hidden="true"
        class="absolute -bottom-6 -right-6 w-20 sm:w-28 -rotate-12 pointer-events-none select-none opacity-40"
    />

    {{-- Top Header --}}
    <header class="w-full flex flex-col items-center justify-center pt-1 sm:pt-3 z-20 select-none">
        <a
            href="{{ route('portal') }}"
            wire:navigate
            class="inline-block transition-transform duration-200 hover:scale-105 focus:outline-none"
            title="Kembali ke Beranda Portal"
        >
            <img
                src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                alt="NONGKRONG.id"
                class="h-8 sm:h-10 w-auto object-contain select-none"
            />
        </a>

        @if ($customerName && $customerName !== 'Tamu')
            <div class="mt-3 inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white border border-slate-200/80 shadow-2xs text-xs font-poppins text-slate-700">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Halo, <strong class="text-brand-blue font-semibold">{{ $customerName }}</strong></span>
                <span class="text-slate-300">|</span>
                <button
                    type="button"
                    wire:click="changeName"
                    class="text-slate-400 hover:text-brand-blue font-medium text-[11px] underline underline-offset-2 cursor-pointer transition-colors"
                >
                    Ganti Nama
                </button>
            </div>
        @else
            <div class="mt-3 inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white border border-blue-100 shadow-2xs text-xs font-poppins text-brand-blue">
                <span class="inline-block w-2 h-2 rounded-full bg-brand-blue animate-pulse"></span>
                <span class="font-medium">Langkah 1: Tentukan Meja Pesanan</span>
            </div>
        @endif
    </header>

    {{-- Main Integrated Scanner Card --}}
    <main
        x-data="{
            activeTab: 'camera', // 'camera' | 'manual'
            scanner: null,
            cameraActive: false,
            cameraError: null,
            scanLocked: false,
            initScanner() {
                if (typeof Html5Qrcode === 'undefined') {
                    this.cameraError = 'Library pemindai belum siap. Gunakan input kode manual.';
                    return;
                }
                this.$nextTick(() => {
                    this.startScanning();
                });
            },
            startScanning() {
                this.cameraError = null;
                if (this.scanLocked) return;
                try {
                    if (this.scanner) {
                        this.stopScanning();
                    }
                    this.scanner = new Html5Qrcode('integrated-qr-camera-viewport', {
                        verbose: false,
                        experimentalFeatures: { useBarCodeDetectorIfSupported: true }
                    });

                    const config = {
                        fps: 20,
                        aspectRatio: 1.0,
                        qrbox: (w, h) => {
                            const min = Math.min(w, h);
                            return { width: Math.floor(min * 0.82), height: Math.floor(min * 0.82) };
                        }
                    };

                    const handleDecoded = (decodedText) => {
                        if (navigator.vibrate) navigator.vibrate(50);
                        this.scanLocked = true;
                        this.stopScanning();
                        $wire.scanQrTable(decodedText);
                    };

                    if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
                        Html5Qrcode.getCameras().then(devices => {
                            let cam = { facingMode: 'environment' };
                            if (devices && devices.length > 0) {
                                let backCam = devices.find(d => /back|rear|environment/i.test(d.label));
                                cam = backCam ? { deviceId: { exact: backCam.id } } : { deviceId: { exact: devices[0].id } };
                            }
                            this.scanner.start(cam, config, handleDecoded, () => {})
                                .then(() => { this.cameraActive = true; })
                                .catch(() => {
                                    this.scanner.start({ facingMode: 'user' }, config, handleDecoded, () => {})
                                        .then(() => { this.cameraActive = true; })
                                        .catch(err => {
                                            console.warn('Error starting camera:', err);
                                            this.cameraError = 'Izin kamera belum aktif. Gunakan tab Kode Manual di atas.';
                                            this.cameraActive = false;
                                        });
                                });
                        }).catch(() => {
                            this.scanner.start({ facingMode: 'user' }, config, handleDecoded, () => {})
                                .then(() => { this.cameraActive = true; })
                                .catch(err => {
                                    console.warn(err);
                                    this.cameraError = 'Kamera tidak terdeteksi. Gunakan tab Kode Manual di atas.';
                                    this.cameraActive = false;
                                });
                        });
                    } else {
                        this.cameraError = 'Browser tidak mendukung akses kamera langsung.';
                    }
                } catch (e) {
                    console.error(e);
                    this.cameraError = 'Terjadi kendala saat menyalakan kamera.';
                    this.cameraActive = false;
                }
            },
            stopScanning() {
                if (this.scanner && this.cameraActive) {
                    try {
                        this.scanner.stop().then(() => {
                            this.scanner.clear();
                            this.cameraActive = false;
                        }).catch(() => {});
                    } catch (e) {}
                }
                this.cameraActive = false;
            },
            switchTab(tab) {
                this.activeTab = tab;
                if (tab === 'camera') {
                    this.scanLocked = false;
                    this.$nextTick(() => this.startScanning());
                } else {
                    this.stopScanning();
                }
            }
        }"
        x-init="initScanner()"
        class="w-full max-w-xl mx-auto flex-1 flex flex-col justify-center py-6 z-20"
    >
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
            {{-- Tabs --}}
            <div class="p-1 bg-slate-100 rounded-2xl flex items-center max-w-sm mx-auto font-poppins select-none mb-6">
                <button
                    type="button"
                    @click="switchTab('camera')"
                    :class="activeTab === 'camera' ? 'bg-white text-brand-blue shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800 font-medium'"
                    class="flex-1 py-2.5 rounded-xl text-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
                        <circle cx="12" cy="13" r="3"/>
                    </svg>
                    <span>Scan QR Kamera</span>
                </button>
                <button
                    type="button"
                    @click="switchTab('manual')"
                    :class="activeTab === 'manual' ? 'bg-white text-brand-blue shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800 font-medium'"
                    class="flex-1 py-2.5 rounded-xl text-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="3" rx="2"/>
                        <path d="M7 7h10M7 12h10M7 17h10"/>
                    </svg>
                    <span>Ketik Kode Meja</span>
                </button>
            </div>

            {{-- 1. Camera Tab View --}}
            <div x-show="activeTab === 'camera'" class="flex flex-col items-center text-center">
                <div class="relative w-64 h-64 sm:w-72 sm:h-72 mx-auto rounded-3xl bg-slate-950 overflow-hidden shadow-lg ring-1 ring-black/5 flex items-center justify-center select-none group">
                    <div id="integrated-qr-camera-viewport" class="w-full h-full"></div>

                    {{-- Reticle corners --}}
                    <div class="absolute inset-5 pointer-events-none z-10 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <div class="w-6 h-6 border-t-3 border-l-3 border-white/90 rounded-tl-xl shadow-xs"></div>
                            <div class="w-6 h-6 border-t-3 border-r-3 border-white/90 rounded-tr-xl shadow-xs"></div>
                        </div>
                        <div class="flex justify-between items-end">
                            <div class="w-6 h-6 border-b-3 border-l-3 border-white/90 rounded-bl-xl shadow-xs"></div>
                            <div class="w-6 h-6 border-b-3 border-r-3 border-white/90 rounded-br-xl shadow-xs"></div>
                        </div>
                    </div>

                    {{-- Laser scan line --}}
                    <div
                        x-show="cameraActive && !scanLocked"
                        class="absolute inset-x-6 h-0.5 bg-gradient-to-r from-transparent via-brand-blue to-transparent shadow-[0_0_12px_#0057FF] animate-qr-laser pointer-events-none z-10"
                    ></div>

                    {{-- Inactive / Permission Prompt --}}
                    <div
                        x-show="!cameraActive || cameraError"
                        class="absolute inset-0 bg-slate-950/95 flex flex-col items-center justify-center p-4 text-center z-20 backdrop-blur-xs font-poppins"
                    >
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white mb-2 shadow-inner">
                            <x-icon name="camera" class="w-6 h-6 text-brand-blue" />
                        </div>
                        <span class="text-xs font-bold text-white">Pemindai Kamera Meja</span>
                        <p class="text-[11px] text-slate-300 mt-1 max-w-[210px] leading-relaxed" x-text="cameraError ? cameraError : 'Arahkan stiker QR meja tepat ke tengah kotak pemindai.'"></p>
                        <button
                            type="button"
                            @click="startScanning()"
                            class="mt-3 px-4 py-2 rounded-xl bg-brand-blue hover:bg-[#003da1] text-white text-xs font-bold shadow-md transition-all active:scale-95 cursor-pointer"
                        >
                            Nyalakan Kamera
                        </button>
                    </div>

                    {{-- Pills --}}
                    <div x-show="cameraActive" class="absolute top-3 inset-x-0 flex justify-center pointer-events-none z-20">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-[10px] font-semibold text-white/90 font-poppins border border-white/10 shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Kamera Aktif</span>
                        </span>
                    </div>

                    <div x-show="cameraActive" class="absolute bottom-3 inset-x-0 flex justify-center pointer-events-none z-20 px-3">
                        <span class="px-3 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-[10px] font-medium text-white/90 font-poppins text-center border border-white/10 shadow-xs">
                            Posisikan QR di dalam bingkai
                        </span>
                    </div>
                </div>

                {{-- Validation Error Feedback if scanned QR is occupied/invalid --}}
                @error('tableCode')
                    <div class="mt-4 p-2.5 max-w-xs mx-auto rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs font-poppins flex items-center gap-2 text-left">
                        <x-icon name="alert-circle" class="w-4 h-4 text-red-500 shrink-0" />
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                <div x-show="scanLocked" class="mt-4 flex justify-center">
                    <button
                        type="button"
                        @click="scanLocked = false; startScanning();"
                        class="px-4 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold font-poppins flex items-center gap-1.5 cursor-pointer transition"
                    >
                        <x-icon name="refresh" class="w-3.5 h-3.5 text-brand-blue" />
                        <span>Scan Ulang Kamera</span>
                    </button>
                </div>
            </div>

            {{-- 2. Manual Code Input View --}}
            <div x-show="activeTab === 'manual'" class="max-w-sm mx-auto">
                <form wire:submit="confirmTableCode" class="space-y-3 font-poppins">
                    <div class="bg-[#FAFAFC] border border-slate-200 rounded-2xl p-4 text-center">
                        <label for="input-standalone-table-code" class="block text-xs font-bold text-slate-900 mb-1">
                            Masukkan Nomor atau Kode Meja
                            <span class="sr-only">Enter your table code !</span>
                        </label>
                        <p class="text-[11px] text-slate-400 mb-3">
                            Contoh: nomor meja <span class="font-mono font-semibold text-brand-blue">01</span> atau kode <span class="font-mono font-semibold text-brand-blue">00001</span>
                            <span class="sr-only">OR</span>
                        </p>

                        <div class="relative mb-3">
                            <input
                                type="text"
                                wire:model="tableCode"
                                id="input-standalone-table-code"
                                placeholder="Contoh: 01 atau 00001"
                                maxlength="15"
                                autocomplete="off"
                                class="w-full h-12 px-4 rounded-xl bg-white border border-slate-200 text-center text-base font-mono font-bold tracking-widest text-brand-blue uppercase placeholder:normal-case placeholder:tracking-normal placeholder:font-normal placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all"
                            />
                        </div>

                        @error('tableCode')
                            <p class="mb-2 text-xs text-red-500 text-left pl-1">
                                {{ $message }}
                            </p>
                        @enderror

                        <button
                            type="submit"
                            class="w-full h-11 bg-brand-blue hover:bg-[#003da1] text-white font-bold text-xs rounded-xl shadow-md shadow-brand-blue/25 transition-all active:scale-98 cursor-pointer flex items-center justify-center"
                        >
                            <span wire:loading.remove wire:target="confirmTableCode">Konfirmasi Meja <span class="sr-only">Confirm</span></span>
                            <span wire:loading.inline-flex wire:target="confirmTableCode" class="inline-flex items-center gap-2 whitespace-nowrap">
                                <svg class="w-4 h-4 animate-spin text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memverifikasi Meja...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Fast Table Selection (Pilihan Meja Cepat Tersedia) --}}
            @if (!empty($availableTables) && $availableTables->count() > 0)
                <div class="mt-6 pt-4 border-t border-slate-100 font-poppins">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mb-2 text-center">
                        Simulasi Langsung Meja Tersedia:
                    </span>
                    <div class="flex flex-wrap items-center justify-center gap-1.5">
                        @foreach ($availableTables->take(8) as $t)
                            <button
                                type="button"
                                wire:click="quickFillTable('{{ $t->table_number }}')"
                                class="px-3 py-1 rounded-full bg-slate-50 hover:bg-brand-blue hover:text-white text-brand-blue text-[11px] font-semibold border border-slate-200 transition-colors cursor-pointer select-none"
                                title="Pilih Meja {{ $t->table_number }}"
                            >
                                Meja {{ $t->table_number }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </main>

    {{-- Subtle Minimal Footer --}}
    <footer class="w-full text-center py-2 text-[11px] text-slate-400 font-sora z-20 select-none">
        &copy; {{ date('Y') }} NONGKRONG.id &bull; Sistem Pemesanan Meja Mandiri
    </footer>

    {{-- Success / Confirm Dialog Modal --}}
    @if ($showSuccessModal && $selectedTableNumber)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            role="dialog"
            aria-modal="true"
        >
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

            <div class="relative w-full max-w-sm bg-white rounded-3xl p-6 shadow-2xl border border-slate-100 z-10 text-center font-poppins">
                <div class="w-14 h-14 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                    <x-icon name="check" class="w-7 h-7 text-emerald-600" />
                </div>

                <h3 class="text-lg font-bold text-slate-900">
                    Meja Berhasil Dipilih!
                </h3>

                <p class="mt-1 text-xs text-slate-500">
                    Anda akan memesan untuk:
                </p>

                <div class="my-4 py-3 px-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-2xl font-black text-brand-blue font-sora tracking-tight">
                        Meja {{ $selectedTableNumber }}
                    </span>
                    @if ($selectedTableCapacity)
                        <span class="block text-[11px] text-slate-400 mt-0.5">Kapasitas {{ $selectedTableCapacity }} Orang</span>
                    @endif
                </div>

                <form wire:submit="proceedToOrder" class="space-y-3">
                    <div>
                        <input
                            type="text"
                            wire:model="customerName"
                            placeholder="Ketik nama Anda (opsional)"
                            class="w-full h-11 px-4 rounded-xl bg-slate-50 border border-slate-200 text-center text-xs font-semibold text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all"
                        />
                        @error('customerName')
                            <p class="mt-1 text-[11px] text-red-500 text-left pl-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-2">
                        <button
                            type="button"
                            wire:click="resetTable"
                            class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer"
                        >
                            Ganti Meja
                        </button>
                        <button
                            type="submit"
                            class="flex-1 py-2.5 rounded-xl bg-brand-blue hover:bg-[#003da1] text-white text-xs font-bold transition shadow-md shadow-brand-blue/25 cursor-pointer"
                        >
                            Lanjut Pesan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

<style>
    @keyframes qrLaserScan {
        0% { top: 10%; opacity: 0.2; }
        50% { opacity: 0.95; }
        100% { top: 86%; opacity: 0.2; }
    }
    .animate-qr-laser {
        animation: qrLaserScan 2.4s ease-in-out infinite alternate;
    }
    #integrated-qr-camera-viewport {
        border: none !important;
        background: transparent !important;
    }
    #integrated-qr-camera-viewport video {
        object-fit: cover !important;
        width: 100% !important;
        height: 100% !important;
        border-radius: 1.5rem !important;
    }
    #integrated-qr-camera-viewport img {
        display: none !important;
    }
</style>
