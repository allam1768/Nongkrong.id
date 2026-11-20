<div x-data="{
    open: @entangle('showTableModal'),
    showConfirmExit: false,
    activeTab: 'camera', // 'camera' | 'manual'
    scannerInstance: null,
    cameraActive: false,
    cameraError: null,
    scanLocked: false,
    initScanner() {
        if (this.scannerInstance) return;
        if (typeof Html5Qrcode === 'undefined') {
            this.cameraError = 'Library kamera belum dimuat. Silakan ketik kode meja manual.';
            return;
        }
        this.$nextTick(() => {
            this.startCameraStream();
        });
    },
    startCameraStream() {
        this.cameraError = null;
        if (this.scanLocked) return;
        try {
            if (this.scannerInstance) {
                this.stopCameraStream();
            }
            this.scannerInstance = new Html5Qrcode('table-modal-qr-viewport', {
                verbose: false,
                experimentalFeatures: { useBarCodeDetectorIfSupported: true }
            });
            const config = {
                fps: 20,
                aspectRatio: 1.0,
                qrbox: (w, h) => {
                    const m = Math.min(w, h);
                    return { width: Math.floor(m * 0.82), height: Math.floor(m * 0.82) };
                }
            };
            const onScanSuccess = (decoded) => {
                if (navigator.vibrate) navigator.vibrate(50);
                this.scanLocked = true;
                this.stopCameraStream();
                $wire.scanQrTable(decoded);
            };

            // Detect cameras or fallback to environment / user
            if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
                Html5Qrcode.getCameras().then(devices => {
                    let cam = { facingMode: 'environment' };
                    if (devices && devices.length > 0) {
                        let backCam = devices.find(d => /back|rear|environment/i.test(d.label));
                        cam = backCam ? { deviceId: { exact: backCam.id } } : { deviceId: { exact: devices[0].id } };
                    }
                    this.scannerInstance.start(cam, config, onScanSuccess, () => {})
                        .then(() => { this.cameraActive = true; })
                        .catch(() => {
                            this.scannerInstance.start({ facingMode: 'user' }, config, onScanSuccess, () => {})
                                .then(() => { this.cameraActive = true; })
                                .catch(err => {
                                    console.warn('Camera error:', err);
                                    this.cameraError = 'Izin kamera belum aktif. Gunakan tab Kode Manual di atas.';
                                    this.cameraActive = false;
                                });
                        });
                }).catch(() => {
                    this.scannerInstance.start({ facingMode: 'user' }, config, onScanSuccess, () => {})
                        .then(() => { this.cameraActive = true; })
                        .catch(err => {
                            console.warn('Camera error:', err);
                            this.cameraError = 'Kamera tidak dapat diakses. Gunakan tab Kode Manual di atas.';
                            this.cameraActive = false;
                        });
                });
            } else {
                this.cameraError = 'Browser tidak mendukung akses kamera langsung.';
            }
        } catch (e) {
            console.error(e);
            this.cameraError = 'Kendala memulai pemindai kamera.';
            this.cameraActive = false;
        }
    },
    stopCameraStream() {
        if (this.scannerInstance && this.cameraActive) {
            try {
                this.scannerInstance.stop().then(() => {
                    this.scannerInstance.clear();
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
            this.$nextTick(() => this.startCameraStream());
        } else {
            this.stopCameraStream();
        }
    },
    attemptExit() {
        this.showConfirmExit = true;
    },
    cancelExit() {
        this.showConfirmExit = false;
    },
    confirmExitAction() {
        this.showConfirmExit = false;
        this.stopCameraStream();
        this.open = false;
        $wire.closeTableModal();
    }
}" x-show="open" x-cloak x-init="$watch('open', val => {
    if (val) {
        scanLocked = false;
        if (activeTab === 'camera') {
            $nextTick(() => initScanner());
        }
    } else {
        stopCameraStream();
    }
});"
    @keydown.escape.window="if (open) { attemptExit(); }"
    class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 md:p-6" role="dialog"
    aria-modal="true" aria-labelledby="table-selection-modal-title">

    {{-- Backdrop Overlay (Diperbarui mengikuti style Login Modal) --}}
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="attemptExit()"
        class="fixed inset-0 bg-slate-900/75 backdrop-blur-md transition-opacity cursor-pointer"></div>

    {{-- Main Modal Card (Penyesuaian shadow & border hanya pada sm:) --}}
    <div x-show="open" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-none sm:max-w-md bg-white rounded-t-3xl sm:rounded-3xl rounded-b-none sm:rounded-b-3xl pt-5 sm:pt-6 pb-6 px-5 sm:px-6 shadow-2xl sm:shadow-[0_20px_50px_rgba(8,_112,_184,_0.1)] border-t border-slate-100 sm:border sm:border-slate-100/50 z-10 transition-all select-auto max-h-[92vh] overflow-y-auto"
        @click.stop>

        {{-- Close Button (Hover effect merah hanya pada sm:) --}}
        <button type="button" @click="attemptExit()"
            class="absolute top-5 right-5 p-2 rounded-full active:scale-90 transition-all cursor-pointer focus:outline-none z-20 text-slate-400 hover:text-slate-700 hover:bg-slate-100 sm:hover:text-red-500 sm:hover:bg-red-50"
            title="Tutup" aria-label="Tutup">
            <svg class="w-5 h-5 sm:w-4 sm:h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        {{-- Exit Confirmation Dialog --}}
        <div x-show="showConfirmExit" x-cloak x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute inset-0 z-30 bg-slate-900/80 backdrop-blur-xs flex items-center justify-center p-4 rounded-t-3xl sm:rounded-3xl">
            <div class="bg-white rounded-2xl p-5 max-w-xs w-full text-center shadow-2xl border border-slate-100 space-y-3 font-poppins">
                <div class="w-10 h-10 mx-auto rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                    <x-icon name="alert-circle" class="w-5 h-5 text-amber-600" />
                </div>
                <h4 class="text-sm font-bold text-slate-900">Batalkan Pemilihan Meja?</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Anda belum menentukan meja pesanan. Tetap ingin kembali ke beranda?
                </p>
                <div class="flex gap-2 pt-1">
                    <button type="button" @click="cancelExit()"
                        class="flex-1 py-2 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition cursor-pointer">
                        Lanjut Pilih
                    </button>
                    <button type="button" @click="confirmExitAction()"
                        class="flex-1 py-2 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer">
                        Ya, Keluar
                    </button>
                </div>
            </div>
        </div>

        {{-- Header Section --}}
        <div class="flex flex-col items-center justify-center text-center select-none pt-1">
            <x-icon name="logo" class="text-brand-blue h-6 sm:h-7 w-auto max-w-44 object-contain inline-block" />
            <h3 id="table-selection-modal-title" class="text-lg sm:text-xl font-bold font-poppins text-slate-900 mt-2">
                Pilih Meja untuk Memulai Pesanan
            </h3>
            <p class="text-xs text-slate-500 font-poppins mt-0.5">
                Arahkan kamera ke stiker QR meja atau ketik kode nomor meja
            </p>
        </div>

        {{-- Segmented Tab Switcher --}}
        <div class="mt-4 p-1 bg-slate-100 rounded-2xl flex items-center max-w-sm mx-auto font-poppins select-none">
            <button type="button" @click="switchTab('camera')"
                :class="activeTab === 'camera' ? 'bg-white text-brand-blue shadow-xs font-bold' :
                    'text-slate-500 hover:text-slate-800 font-medium'"
                class="flex-1 py-2 rounded-xl text-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
                    <circle cx="12" cy="13" r="3" />
                </svg>
                <span>Scan Kamera</span>
            </button>
            <button type="button" @click="switchTab('manual')"
                :class="activeTab === 'manual' ? 'bg-white text-brand-blue shadow-xs font-bold' :
                    'text-slate-500 hover:text-slate-800 font-medium'"
                class="flex-1 py-2 rounded-xl text-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="3" rx="2" />
                    <path d="M7 7h10M7 12h10M7 17h10" />
                </svg>
                <span>Kode Manual</span>
            </button>
        </div>

        {{-- TAB 1: Live Camera Scanner View --}}
        <div x-show="activeTab === 'camera'" class="mt-5">
            <div class="relative w-full max-w-[280px] sm:max-w-[300px] aspect-square mx-auto rounded-3xl overflow-hidden bg-slate-950 shadow-md ring-1 ring-black/5 flex items-center justify-center select-none group">
                {{-- Live Video Output --}}
                <div id="table-modal-qr-viewport" class="w-full h-full"></div>

                {{-- Standby / Inactive / Error Overlay --}}
                <div x-show="!cameraActive || cameraError"
                    class="absolute inset-0 bg-slate-950/95 flex flex-col items-center justify-center p-4 text-center z-20 backdrop-blur-xs font-poppins">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white mb-2 shadow-inner">
                        <x-icon name="camera" class="w-6 h-6 text-brand-blue" />
                    </div>
                    <span class="text-xs font-bold text-white">Pemindai Kamera Meja</span>
                    <p class="text-[11px] text-slate-300 mt-1 max-w-[210px] leading-relaxed"
                        x-text="cameraError ? cameraError : 'Arahkan stiker QR meja tepat ke tengah kotak pemindai.'">
                    </p>
                    <button type="button" @click="startCameraStream()"
                        class="mt-3 px-4 py-2 rounded-xl bg-brand-blue hover:bg-[#003da1] text-white text-xs font-bold shadow-md transition-all active:scale-95 cursor-pointer">
                        Nyalakan Kamera
                    </button>
                </div>

                {{-- Status Pill Top --}}
                <div x-show="cameraActive" class="absolute top-3 inset-x-0 flex justify-center pointer-events-none z-20">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-[10px] font-semibold text-white/90 font-poppins border border-white/10 shadow-xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Kamera Aktif</span>
                    </span>
                </div>

                {{-- Status Pill Bottom --}}
                <div x-show="cameraActive" class="absolute bottom-3 inset-x-0 flex justify-center pointer-events-none z-20 px-3">
                    <span class="px-3 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-[10px] font-medium text-white/90 font-poppins text-center border border-white/10 shadow-xs">
                        Posisikan QR di dalam bingkai
                    </span>
                </div>
            </div>

            {{-- Validation Error Feedback --}}
            @error('tableCode')
                <div class="mt-3 p-2.5 max-w-[280px] sm:max-w-[300px] mx-auto rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs font-poppins flex items-center gap-2 text-left">
                    <x-icon name="alert-circle" class="w-4 h-4 text-red-500 shrink-0" />
                    <span>{{ $message }}</span>
                </div>
            @enderror

            {{-- Scan Locked Controls --}}
            <div x-show="scanLocked" class="mt-3 flex justify-center">
                <button type="button" @click="scanLocked = false; startCameraStream();"
                    class="px-4 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold font-poppins flex items-center gap-1.5 cursor-pointer transition">
                    <x-icon name="refresh" class="w-3.5 h-3.5 text-brand-blue" />
                    <span>Scan Ulang</span>
                </button>
            </div>
        </div>

        {{-- TAB 2: Manual Table Code Input Form --}}
        <div x-show="activeTab === 'manual'" class="mt-5 max-w-sm mx-auto">
            <form wire:submit="confirmTableCode" class="space-y-3 font-poppins">
                <div class="bg-[#FAFAFC] border border-slate-200 rounded-2xl p-4 text-center">
                    <label for="input-modal-table-code-clean" class="block text-xs font-bold text-slate-900 mb-1">
                        Masukkan Nomor atau Kode Meja
                    </label>
                    <p class="text-[11px] text-slate-400 mb-3">
                        Contoh: <span class="font-mono font-semibold text-brand-blue">01</span> atau <span
                            class="font-mono font-semibold text-brand-blue">00001</span>
                    </p>

                    <div class="relative mb-3">
                        <input type="text" wire:model="tableCode" id="input-modal-table-code-clean"
                            placeholder="Contoh: 01 atau 00001" maxlength="15" autocomplete="off"
                            class="w-full h-12 px-4 rounded-xl bg-white border border-slate-200 text-center text-base font-mono font-bold tracking-widest text-brand-blue uppercase placeholder:normal-case placeholder:tracking-normal placeholder:font-normal placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all" />
                    </div>

                    @error('tableCode')
                        <p class="mb-2 text-xs text-red-500 text-left pl-1">
                            {{ $message }}
                        </p>
                    @enderror

                    <button type="submit"
                        class="w-full h-11 bg-brand-blue hover:bg-[#003da1] text-white font-bold text-xs rounded-xl shadow-md shadow-brand-blue/25 transition-all active:scale-98 cursor-pointer flex items-center justify-center">
                        <span wire:loading.remove wire:target="confirmTableCode">Konfirmasi Meja</span>
                        <span wire:loading.inline-flex wire:target="confirmTableCode"
                            class="inline-flex items-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4 animate-spin text-white shrink-0 inline-block" viewBox="0 0 24 24"
                                fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>Memverifikasi Meja...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Fast Table Selection (Pilihan Meja Cepat Tersedia) --}}
        @if (!empty($availableTables) && $availableTables->count() > 0)
            <div class="mt-5 pt-4 border-t border-slate-100 font-poppins">

            </div>
        @endif
    </div>
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
    #table-modal-qr-viewport {
        border: none !important;
        background: transparent !important;
    }
    #table-modal-qr-viewport video {
        object-fit: cover !important;
        width: 100% !important;
        height: 100% !important;
        border-radius: 1.5rem !important;
    }
    #table-modal-qr-viewport img {
        display: none !important;
    }
    #table-modal-qr-viewport>svg {
        display: none !important;
    }
    #qr-shaded-region {
        border: none !important;
        display: none !important;
    }
    #qr-shaded-region div {
        border: none !important;
        display: none !important;
    }
</style>
