{{-- Table Selection Modal Popup (Pilih Meja - Mobile & Desktop Overlay) --}}
<div
    x-data="{ open: @entangle('showTableModal') }"
    x-show="open"
    x-cloak
    @keydown.escape.window="open = false; $wire.closeTableModal()"
    class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="table-selection-modal-title"
>
    {{-- Backdrop Overlay --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false; $wire.closeTableModal()"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"
    ></div>

    {{-- Modal Dialog Box (Bottom Sheet on Mobile, Centered Modal on Tablet/Desktop) --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-none sm:max-w-md md:max-w-3xl lg:max-w-4xl bg-white rounded-t-4xl sm:rounded-3xl rounded-b-none sm:rounded-b-3xl pt-5 pb-6 px-5 sm:p-8 shadow-2xl border-t sm:border border-slate-100 z-10 transition-all select-auto max-h-[90vh] overflow-y-auto"
        @click.stop
    >
        {{-- Close Button (Bisa dipakai di Mobile & Desktop) --}}
        <button
            type="button"
            wire:click="closeTableModal"
            @click="open = false"
            class="absolute top-5 right-5 sm:top-6 sm:right-6 text-slate-300 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-100 active:scale-90 transition-all cursor-pointer focus:outline-none z-20"
            title="Tutup"
            aria-label="Tutup"
        >
            <x-icon name="close" class="w-4 h-4 text-slate-300 hover:text-slate-600" />
        </button>

        {{-- ================================================================= --}}
        {{-- 1. MOBILE VIEW (< md) - PERSIS SAMA / UNTOUCHED (JANGAN DIUBAH)   --}}
        {{-- ================================================================= --}}
        <div class="block md:hidden">
            {{-- Drag Handle for Mobile Sheet --}}
            <div class="w-12 h-1.5 bg-slate-200/90 rounded-full mx-auto mt-0.5 mb-5 shrink-0 block sm:hidden"></div>

            {{-- Centered Logo: NONGKRONG.id --}}
            <div class="flex flex-col items-center justify-center pt-2 sm:pt-1 mb-5 select-none">
                <x-icon name="logo"
                    class="text-brand-blue h-6 sm:h-7 w-auto max-w-[75%] object-contain select-none"
                />
                <h3 id="table-selection-modal-title" class="text-sm sm:text-base font-bold font-poppins text-slate-900 mt-2">
                    Pilih Meja untuk Memulai
                </h3>
                <p class="text-[11px] text-slate-400 font-poppins text-center mt-0.5">
                    Scan QR stiker meja atau ketik 5 digit kode meja Anda
                </p>
            </div>

            {{-- OPTION 1: Kotakan Transparan Kamera Scanner Langsung --}}
            <div
                x-data="{
                    cameraStream: null,
                    cameraActive: false,
                    cameraError: false,
                    scanInterval: null,
                    async startCamera() {
                        if (window.innerWidth >= 768) return;
                        this.cameraError = false;
                        try {
                            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                                this.cameraStream = await navigator.mediaDevices.getUserMedia({
                                    video: { facingMode: 'environment' }
                                });
                                if (this.$refs.cameraFeed) {
                                    this.$refs.cameraFeed.srcObject = this.cameraStream;
                                    this.cameraActive = true;
                                    this.initBarcodeDetector();
                                }
                            } else {
                                this.cameraError = true;
                            }
                        } catch (e) {
                            this.cameraActive = false;
                            this.cameraError = true;
                        }
                    },
                    stopCamera() {
                        if (this.scanInterval) {
                            clearInterval(this.scanInterval);
                            this.scanInterval = null;
                        }
                        if (this.cameraStream) {
                            this.cameraStream.getTracks().forEach(track => track.stop());
                            this.cameraStream = null;
                            this.cameraActive = false;
                        }
                    },
                    initBarcodeDetector() {
                        if ('BarcodeDetector' in window) {
                            try {
                                const detector = new BarcodeDetector({ formats: ['qr_code'] });
                                this.scanInterval = setInterval(async () => {
                                    if (!this.cameraActive || !this.$refs.cameraFeed) return;
                                    try {
                                        const barcodes = await detector.detect(this.$refs.cameraFeed);
                                        if (barcodes.length > 0 && barcodes[0].rawValue) {
                                            if (navigator.vibrate) navigator.vibrate(50);
                                            $wire.scanQrTable(barcodes[0].rawValue);
                                            this.stopCamera();
                                        }
                                    } catch (err) {}
                                }, 400);
                            } catch (err) {}
                        }
                    }
                }"
                x-init="if (window.innerWidth < 768) startCamera()"
                x-effect="if (!open) stopCamera(); else if (window.innerWidth < 768 && !cameraActive && !cameraError) startCamera();"
                class="relative w-full max-w-60 sm:max-w-65 aspect-square mx-auto rounded-3xl overflow-hidden bg-slate-950 border border-slate-800 flex items-center justify-center select-none shadow-inner"
            >
                {{-- Native Video Stream --}}
                <video
                    x-ref="cameraFeed"
                    autoplay
                    playsinline
                    muted
                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300"
                    :class="{ 'opacity-100': cameraActive, 'opacity-0': !cameraActive }"
                ></video>

                {{-- Fallback when camera inactive or permission pending --}}
                <div
                    x-show="!cameraActive"
                    class="absolute inset-0 bg-slate-950/90 flex flex-col items-center justify-center p-3 text-center z-5"
                >
                    <div class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-300 mb-1.5">
                        {{-- <svg class="w-5 h-5 text-brand-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg> --}}
                    </div>
                    <span class="text-xs font-semibold text-white font-poppins">Kamera Scanner Meja</span>
                    <span class="text-[10px] text-slate-400 font-poppins mt-0.5 max-w-45">Izinkan akses kamera untuk scan otomatis</span>
                    <button
                        type="button"
                        @click="startCamera()"
                        class="mt-2 px-3 py-1.5 rounded-xl bg-brand-blue hover:bg-brand-blue/90 text-white text-[11px] font-bold font-poppins shadow-md transition-all active:scale-95 cursor-pointer"
                    >
                        Aktifkan Kamera
                    </button>
                </div>

                {{-- Status Pill at Top --}}
                <div class="absolute top-2 inset-x-0 flex justify-center pointer-events-none z-10">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-black/60 backdrop-blur-xs text-[10px] font-semibold text-white font-poppins border border-white/10">
                        <span class="w-1.5 h-1.5 rounded-full" :class="cameraActive ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400'"></span>
                        <span x-text="cameraActive ? 'Kamera Aktif' : 'Scanner Siap'">Scanner Siap</span>
                    </span>
                </div>

                {{-- Instruction Pill at Bottom --}}
                <div class="absolute bottom-2 inset-x-0 flex justify-center pointer-events-none z-10 px-3">
                    <span class="px-2.5 py-0.5 rounded-full bg-black/60 backdrop-blur-xs text-[9.5px] font-medium text-slate-200 font-poppins text-center border border-white/10">
                        Posisikan QR Code di dalam kotak
                    </span>
                </div>
            </div>

            {{-- Divider OR --}}
            <div class="my-3 flex items-center justify-center gap-3">
                <span class="h-px bg-slate-200 flex-1"></span>
                <span class="text-[11px] font-black text-slate-400 font-poppins uppercase tracking-wider">OR</span>
                <span class="h-px bg-slate-200 flex-1"></span>
            </div>

            {{-- OPTION 2: Form Input 5-Digit Table Code --}}
            <form wire:submit="confirmTableCode" class="w-full">
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3.5 text-center">
                    <label for="input-modal-table-code" class="block text-xs font-bold text-slate-800 font-poppins mb-1">
                        Enter your table code !
                    </label>
                    <p class="text-[11px] text-slate-400 font-poppins mb-3">
                        Ketik 5 digit kode yang tertera pada stiker meja Anda
                    </p>

                    <div class="relative mb-3">
                        <input
                            type="text"
                            wire:model="tableCode"
                            id="input-modal-table-code"
                            placeholder="Contoh: 01234 atau 00001"
                            maxlength="15"
                            autocomplete="off"
                            class="w-full h-11 px-4 rounded-xl bg-white border border-slate-200 text-center text-sm font-mono font-bold tracking-widest text-brand-blue uppercase placeholder:normal-case placeholder:tracking-normal placeholder:font-normal placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition-all"
                        />
                    </div>

                    {{-- Validation Feedback --}}
                    @error('tableCode')
                        <p class="mb-2 text-xs text-red-500 font-poppins text-left pl-1">
                            {{ $message }}
                        </p>
                    @enderror

                    <x-button
                        type="submit"
                        id="btn-modal-confirm-table"
                        class="w-full font-bold shadow-md shadow-brand-blue/25 text-xs py-2.5"
                    >
                        <span wire:loading.remove wire:target="confirmTableCode">Confirm Meja</span>
                        <span wire:loading.inline-flex wire:target="confirmTableCode" class="inline-flex items-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4 animate-spin text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Memverifikasi Meja...</span>
                        </span>
                    </x-button>
                </div>
            </form>

            {{-- Fast Table Selection Simulator (Pilihan Cepat Meja) --}}
            @if (!empty($availableTables) && $availableTables->count() > 0)
                <div class="mt-3.5 pt-3 border-t border-slate-100">
                    <span class="text-[10px] font-semibold text-slate-400 font-poppins uppercase tracking-wider block mb-2 text-center">
                        Simulasikan Meja Tersedia:
                    </span>
                    <div class="flex flex-wrap items-center justify-center gap-1.5">
                        @foreach ($availableTables->take(6) as $t)
                            <button
                                type="button"
                                wire:click="quickFillTable('{{ $t->table_number }}')"
                                class="px-2.5 py-1 rounded-full bg-slate-50 hover:bg-brand-blue hover:text-white text-brand-blue text-[11px] font-semibold border border-slate-200 transition-colors cursor-pointer select-none"
                                title="Pilih Meja {{ $t->table_number }}"
                            >
                                Meja {{ $t->table_number }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ================================================================= --}}
        {{-- 2. DESKTOP VIEW (>= md) - OVERLAY MODAL MEJA DENGAN WEBCAM LIVE   --}}
        {{-- ================================================================= --}}
        <div
            class="hidden md:block"
            x-data="{
                desktopCameraStream: null,
                desktopCameraActive: false,
                desktopCameraError: false,
                desktopScanInterval: null,
                async startDesktopCamera() {
                    this.desktopCameraError = false;
                    try {
                        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                            try {
                                this.desktopCameraStream = await navigator.mediaDevices.getUserMedia({
                                    video: { width: { ideal: 1280 }, height: { ideal: 720 } }
                                });
                            } catch (fallbackErr) {
                                // Fallback jika resolusi ideal tidak disupport perangkat
                                this.desktopCameraStream = await navigator.mediaDevices.getUserMedia({ video: true });
                            }
                            if (this.$refs.desktopCameraFeed) {
                                this.$refs.desktopCameraFeed.srcObject = this.desktopCameraStream;
                                this.desktopCameraActive = true;
                                this.initDesktopBarcodeDetector();
                            }
                        } else {
                            this.desktopCameraError = true;
                        }
                    } catch (e) {
                        this.desktopCameraActive = false;
                        this.desktopCameraError = true;
                    }
                },
                stopDesktopCamera() {
                    if (this.desktopScanInterval) {
                        clearInterval(this.desktopScanInterval);
                        this.desktopScanInterval = null;
                    }
                    if (this.desktopCameraStream) {
                        this.desktopCameraStream.getTracks().forEach(track => track.stop());
                        this.desktopCameraStream = null;
                        this.desktopCameraActive = false;
                    }
                },
                initDesktopBarcodeDetector() {
                    if ('BarcodeDetector' in window) {
                        try {
                            const detector = new BarcodeDetector({ formats: ['qr_code'] });
                            this.desktopScanInterval = setInterval(async () => {
                                if (!this.desktopCameraActive || !this.$refs.desktopCameraFeed) return;
                                try {
                                    const barcodes = await detector.detect(this.$refs.desktopCameraFeed);
                                    if (barcodes.length > 0 && barcodes[0].rawValue) {
                                        if (navigator.vibrate) navigator.vibrate(50);
                                        $wire.scanQrTable(barcodes[0].rawValue);
                                        this.stopDesktopCamera();
                                    }
                                } catch (err) {}
                            }, 400);
                        } catch (err) {}
                    }
                }
            }"
            x-init="if (window.innerWidth >= 768) startDesktopCamera()"
            x-effect="if (!open) stopDesktopCamera(); else if (window.innerWidth >= 768 && !desktopCameraActive && !desktopCameraError) startDesktopCamera();"
        >
            {{-- Desktop Header --}}
            <div class="flex flex-col items-center justify-center text-center pb-5 border-b border-slate-100 select-none">
                <x-icon name="logo" class="text-brand-blue h-7 w-auto max-w-56 object-contain inline-block" />
                <h2 class="text-xl font-bold font-poppins text-slate-900 mt-2.5">
                    Pilih Meja untuk Memulai
                </h2>
                <p class="text-xs text-slate-500 font-poppins mt-1 max-w-md">
                    Scan QR stiker meja Anda dengan webcam atau masukkan 5 digit kode meja.
                </p>
            </div>

            {{-- Desktop 2-Column Body: Kiri Live Webcam, Kanan Form Input Kode --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center py-6">
                {{-- Sisi Kiri: Real Webcam Scanner Live --}}
                <div class="flex flex-col items-center justify-center p-5 bg-slate-50/80 rounded-2xl border border-slate-200/80 text-center">
                    {{-- Frame Video Webcam --}}
                    <div class="relative w-full max-w-65 aspect-square rounded-2xl overflow-hidden bg-slate-950 border border-slate-800 flex items-center justify-center select-none shadow-inner">
                        {{-- Live Video Stream --}}
                        <video
                            x-ref="desktopCameraFeed"
                            autoplay
                            playsinline
                            muted
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300"
                            :class="{ 'opacity-100': desktopCameraActive, 'opacity-0': !desktopCameraActive }"
                        ></video>

                        {{-- Fallback saat kamera belum aktif atau izin ditolak --}}
                        <div
                            x-show="!desktopCameraActive"
                            class="absolute inset-0 bg-slate-950/90 flex flex-col items-center justify-center p-3 text-center z-5"
                        >
                            <div class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-300 mb-1.5">
                                <x-icon name="camera" class="w-5 h-5 text-brand-blue" />
                            </div>
                            <span class="text-xs font-semibold text-white font-poppins">Webcam Scanner Meja</span>
                            <span class="text-[10px] text-slate-400 font-poppins mt-0.5 max-w-47.5">Izinkan akses webcam untuk scan otomatis</span>
                            <button
                                type="button"
                                @click="startDesktopCamera()"
                                class="mt-2.5 px-3 py-1.5 rounded-xl bg-brand-blue hover:bg-brand-blue/90 text-white text-[11px] font-bold font-poppins shadow-md transition-all active:scale-95 cursor-pointer"
                            >
                                Aktifkan Webcam
                            </button>
                        </div>


                        {{-- Status Pill di Atas --}}
                        <div class="absolute top-2 inset-x-0 flex justify-center pointer-events-none z-10">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-black/60 backdrop-blur-xs text-[10px] font-semibold text-white font-poppins border border-white/10">
                                <span class="w-1.5 h-1.5 rounded-full" :class="desktopCameraActive ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400'"></span>
                                <span x-text="desktopCameraActive ? 'Webcam Aktif' : 'Scanner Siap'">Scanner Siap</span>
                            </span>
                        </div>

                        {{-- Instruction Pill di Bawah --}}
                        <div class="absolute bottom-2 inset-x-0 flex justify-center pointer-events-none z-10 px-3">
                            <span class="px-2.5 py-0.5 rounded-full bg-black/60 backdrop-blur-xs text-[9.5px] font-medium text-slate-200 font-poppins text-center border border-white/10">
                                Posisikan QR Code di depan webcam
                            </span>
                        </div>
                    </div>

                    {{-- Tombol Kontrol Webcam & Label --}}
                    <div class="mt-3 flex items-center justify-center gap-2">
                        <span class="text-xs font-bold text-slate-800 font-poppins">Scan QR Code Meja</span>
                       
                        <button
                            type="button"
                            x-show="!desktopCameraActive"
                            @click="startDesktopCamera()"
                            class="text-[11px] text-brand-blue hover:text-blue-700 font-medium font-poppins cursor-pointer underline underline-offset-2"
                        >
                            Buka Cam
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-400 font-poppins mt-0.5 max-w-55">
                        Arahkan stiker QR code meja ke webcam laptop/PC Anda
                    </p>
                </div>

                {{-- Sisi Kanan: Form Input Kode Meja --}}
                <div class="flex flex-col justify-center">
                    <form wire:submit="confirmTableCode" class="w-full">
                        <label for="input-desktop-table-code" class="block text-sm font-bold text-slate-900 font-poppins mb-1">
                            Enter your table code !
                        </label>
                        <p class="text-xs text-slate-500 font-poppins mb-3.5">
                            Ketik 5 digit kode meja (contoh: <span class="font-mono font-semibold text-brand-blue">00001</span> atau nomor meja <span class="font-mono font-semibold text-brand-blue">01</span>)
                        </p>

                        <div class="relative mb-3">
                            <input
                                type="text"
                                wire:model="tableCode"
                                id="input-desktop-table-code"
                                placeholder="Contoh: 00001 atau 01"
                                maxlength="15"
                                autocomplete="off"
                                class="w-full h-12 px-4 rounded-xl bg-slate-50 border border-slate-200 text-base font-mono font-bold tracking-widest text-brand-blue uppercase placeholder:normal-case placeholder:tracking-normal placeholder:font-normal placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition-all"
                            />
                        </div>

                        {{-- Validation Feedback --}}
                        @error('tableCode')
                            <div class="mb-3 p-2.5 rounded-lg bg-red-50 border border-red-200 text-red-600 text-xs font-poppins flex items-center gap-2">
                                <x-icon name="alert-circle" class="w-4 h-4 text-red-500 shrink-0" />
                                <span>{{ $message }}</span>
                            </div>
                        @enderror

                        <x-button
                            type="submit"
                            id="btn-desktop-confirm-table"
                            class="w-full font-bold shadow-md shadow-brand-blue/25 text-sm py-3"
                        >
                            <span wire:loading.remove wire:target="confirmTableCode">Confirm Meja</span>
                            <span wire:loading.inline-flex wire:target="confirmTableCode" class="inline-flex items-center gap-2 whitespace-nowrap">
                                <svg class="w-4 h-4 animate-spin text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memverifikasi Meja...</span>
                            </span>
                        </x-button>
                    </form>
                </div>
            </div>

            {{-- Fast Table Selection Simulator Desktop --}}
            @if (!empty($availableTables) && $availableTables->count() > 0)
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between gap-4 flex-wrap">
                    <span class="text-xs font-semibold text-slate-500 font-poppins">
                        Pilihan Cepat Meja Tersedia:
                    </span>
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach ($availableTables as $t)
                            <button
                                type="button"
                                wire:click="quickFillTable('{{ $t->table_number }}')"
                                class="px-3 py-1.5 rounded-lg bg-slate-50 hover:bg-brand-blue hover:text-white text-brand-blue text-xs font-semibold border border-slate-200 transition-colors cursor-pointer select-none"
                                title="Pilih Meja {{ $t->table_number }}"
                            >
                                Meja {{ $t->table_number }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes scanLaser {
        0% {
            transform: translateY(-56px);
            opacity: 0.3;
        }
        50% {
            opacity: 1;
        }
        100% {
            transform: translateY(56px);
            opacity: 0.3;
        }
    }
</style>
