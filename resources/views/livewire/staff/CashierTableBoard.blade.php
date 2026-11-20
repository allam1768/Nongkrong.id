<div class="min-h-screen bg-[#F8FAFC] text-slate-800 font-sans pb-16">
    {{-- Toast Notification (Top Right - Unified Single Toast) --}}
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

    {{-- Top Bar (Clean & Precision Aligned) --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            {{-- Brand, Breadcrumb & Title --}}
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('portal') }}" wire:navigate class="shrink-0 focus:outline-none">
                    <img
                        src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                        alt="Nongkrong.id"
                        class="h-7 sm:h-8 w-auto object-contain select-none"
                    />
                </a>
                <span class="w-px h-5 bg-slate-200 shrink-0"></span>
                <div class="flex items-center gap-2 min-w-0">
                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                        Kasir POS
                    </span>
                    <h1 class="text-xs sm:text-sm font-black text-slate-900 tracking-tight whitespace-nowrap">
                        Status Meja Kasir
                    </h1>
                </div>
            </div>

            {{-- Right: Scan Ticket, Cashier Identity & Logout --}}
            <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                <button
                    type="button"
                    wire:click="openScannerModal"
                    class="h-9 px-3.5 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-2xs active:scale-95 shrink-0"
                    title="Scan QR Tiket Pembeli Tunai"
                >
                    <x-icon name="camera" class="w-3.5 h-3.5 text-white" />
                    <span>Scan Tiket Kasir</span>
                </button>

                <span class="hidden sm:inline-block w-px h-5 bg-slate-200 shrink-0"></span>

                <div class="hidden sm:flex flex-col text-right">
                    <span class="text-xs font-bold text-slate-900 leading-tight">{{ auth()->user()->name ?? 'Kasir' }}</span>
                    <span class="text-[10px] text-slate-500 font-medium">Staf Kasir Bertugas</span>
                </div>

                <button
                    type="button"
                    wire:click="confirmLogout"
                    class="h-9 px-3.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5 cursor-pointer shadow-2xs active:scale-95"
                    title="Keluar dari sesi kasir"
                >
                    <x-icon name="logout" class="w-3.5 h-3.5 text-slate-500" />
                    <span>Keluar</span>
                </button>
            </div>
        </div>
    </header>

    {{-- Main Content (Realtime polling scoped to tables & queue) --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6" wire:poll.10s>
        {{-- Pending Cash Orders Notification Banner --}}
        @if ($pendingCashOrders->isNotEmpty())
            <div class="bg-amber-50/70 border-2 border-amber-200 rounded-2xl p-4 shadow-2xs space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse shrink-0"></span>
                        <span class="text-xs font-black text-slate-900 uppercase tracking-wide truncate">
                            {{ $pendingCashOrders->count() }} Pesanan Meja Menunggu Pembayaran Tunai
                        </span>
                    </div>
                    <button
                        type="button"
                        wire:click="openScannerModal"
                        class="px-3 py-1.5 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold flex items-center gap-1.5 cursor-pointer transition shadow-2xs active:scale-95 shrink-0"
                    >
                        <x-icon name="camera" class="w-3.5 h-3.5 text-white" />
                        <span>Scan Kamera</span>
                    </button>
                </div>
                <div class="flex gap-3 overflow-x-auto pb-1">
                    @foreach ($pendingCashOrders as $order)
                        <button
                            type="button"
                            wire:click="openTicketValidation({{ $order->id }})"
                            class="px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 hover:border-slate-400 text-left shrink-0 transition shadow-2xs group cursor-pointer space-y-1"
                        >
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-md bg-slate-900 text-white font-mono font-black text-[10px]">
                                    {{ $order->table ? 'MEJA '.$order->table->table_number : 'TAKEAWAY' }}
                                </span>
                                <span class="font-mono text-[10px] font-bold text-slate-500">
                                    #{{ $order->order_code }}
                                </span>
                            </div>
                            <div class="text-xs font-bold text-slate-900 truncate max-w-[150px]">
                                {{ $order->customer_name }}
                            </div>
                            <div class="text-xs font-mono font-black text-slate-900">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Status Counter Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            {{-- Kosong --}}
            <div class="p-4 rounded-2xl bg-white border border-slate-200 flex flex-col justify-between shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Meja Kosong</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 leading-none font-mono">{{ $this->available_count }}</span>
                    <span class="text-xs text-slate-500 font-medium">Tersedia</span>
                </div>
            </div>

            {{-- Terisi --}}
            <div class="p-4 rounded-2xl bg-white border border-slate-200 flex flex-col justify-between shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Meja Terisi</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-brand-blue"></span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 leading-none font-mono">{{ $this->occupied_count }}</span>
                    <span class="text-xs text-slate-500 font-medium">Sedang Digunakan</span>
                </div>
            </div>

            {{-- Selesai & Kotor --}}
            <div class="p-4 rounded-2xl bg-white border border-slate-200 flex flex-col justify-between shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Selesai / Kotor</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 leading-none font-mono">{{ $this->dirty_count }}</span>
                    <span class="text-xs text-slate-500 font-medium">Perlu Dibersihkan</span>
                </div>
            </div>

            {{-- Total Meja --}}
            <div class="p-4 rounded-2xl bg-white border border-slate-200 flex flex-col justify-between shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Meja</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 leading-none font-mono">{{ $this->total_count }}</span>
                    <span class="text-xs text-slate-500 font-medium">Unit Meja</span>
                </div>
            </div>
        </div>

        {{-- Filter & Search Toolbar --}}
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-3 shadow-2xs">
            <div class="flex flex-wrap items-center gap-1.5 w-full md:w-auto">
                <button
                    type="button"
                    wire:click="setFilter('all')"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $activeFilter === 'all' ? 'bg-brand-blue text-white shadow-xs font-black' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                >
                    Semua ({{ $this->total_count }})
                </button>
                <button
                    type="button"
                    wire:click="setFilter('available')"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $activeFilter === 'available' ? 'bg-brand-blue text-white shadow-xs font-black' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                >
                    Kosong ({{ $this->available_count }})
                </button>
                <button
                    type="button"
                    wire:click="setFilter('occupied')"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $activeFilter === 'occupied' ? 'bg-brand-blue text-white shadow-xs font-black' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                >
                    Terisi ({{ $this->occupied_count }})
                </button>
                <button
                    type="button"
                    wire:click="setFilter('dirty')"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $activeFilter === 'dirty' ? 'bg-brand-blue text-white shadow-xs font-black' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                >
                    Selesai / Kotor ({{ $this->dirty_count }})
                </button>
            </div>

            <div class="w-full md:w-64 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <x-icon name="search" class="w-3.5 h-3.5" />
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.250ms="search"
                    placeholder="Cari nomor meja..."
                    class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:bg-white transition"
                />
            </div>
        </div>

        {{-- Table Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse ($tables as $tbl)
                @php
                    $isAvailable = ($tbl->status === 'available');
                    $isOccupied = ($tbl->status === 'occupied');
                    $isDirty = ($tbl->status === 'dirty');
                    $latestOrder = $tbl->orders->first();
                @endphp

                <div
                    wire:key="cashier-table-{{ $tbl->id }}"
                    wire:click="selectAndOpenPos({{ $tbl->id }})"
                    class="p-4 rounded-2xl bg-white border-2 border-slate-200 hover:border-slate-400 transition cursor-pointer shadow-2xs flex flex-col justify-between group active:scale-[0.99]"
                >
                    <div>
                        {{-- Top: Table Number & Status Badge --}}
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">MEJA</span>
                                <h3 class="text-2xl font-black text-slate-900 font-mono tracking-tight group-hover:text-brand-blue transition">
                                    {{ $tbl->table_number }}
                                </h3>
                            </div>

                            @if ($isAvailable)
                                <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    Kosong
                                </span>
                            @elseif ($isOccupied)
                                <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-blue-50 text-brand-blue border border-blue-200">
                                    Terisi
                                </span>
                            @elseif ($isDirty)
                                <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-amber-50 text-amber-900 border border-amber-200">
                                    Selesai / Kotor
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ ucfirst($tbl->status) }}
                                </span>
                            @endif
                        </div>

                        {{-- Details Info --}}
                        <div class="text-xs text-slate-600 space-y-1.5 mb-4">
                            <div class="flex items-center gap-1.5 font-medium">
                                <x-icon name="user" class="w-3.5 h-3.5 text-slate-400" />
                                <span>Kapasitas: <strong class="text-slate-800 font-bold">{{ $tbl->capacity }} Kursi</strong></span>
                            </div>

                            @if ($isOccupied && $latestOrder)
                                <div class="mt-2 p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-0.5">
                                    <div class="font-bold text-slate-900 truncate">Tamu: {{ $latestOrder->customer_name }}</div>
                                    <div class="text-slate-500 font-mono text-[11px]">#{{ $latestOrder->order_code }}</div>
                                </div>
                            @elseif ($isDirty)
                                <div class="mt-2 p-2.5 rounded-xl bg-amber-50/50 border border-amber-200 text-xs text-amber-900 font-medium">
                                    Meja selesai bersantap. Menunggu dibersihkan.
                                </div>
                            @elseif ($isAvailable)
                                <div class="mt-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-500 font-medium">
                                    Meja bersih & siap ditempati pelanggan baru.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions: Ubah Status di Card & Tombol Buka Kasir --}}
                    <div class="pt-3 border-t border-slate-100 space-y-2.5" wire:click.stop>
                        {{-- Status Modifier Buttons --}}
                        <div class="flex items-center justify-between gap-1 text-[11px]">
                            <span class="text-slate-400 font-bold">Ubah:</span>
                            <div class="inline-flex rounded-lg border border-slate-200 bg-slate-50 p-0.5">
                                <button
                                    type="button"
                                    wire:click.stop="updateTableStatus({{ $tbl->id }}, 'available')"
                                    class="px-2 py-0.5 rounded text-[10px] font-bold transition cursor-pointer {{ $isAvailable ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                                    title="Tandai Kosong"
                                >
                                    Kosong
                                </button>
                                <button
                                    type="button"
                                    wire:click.stop="updateTableStatus({{ $tbl->id }}, 'occupied')"
                                    class="px-2 py-0.5 rounded text-[10px] font-bold transition cursor-pointer {{ $isOccupied ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                                    title="Tandai Terisi"
                                >
                                    Terisi
                                </button>
                                <button
                                    type="button"
                                    wire:click.stop="updateTableStatus({{ $tbl->id }}, 'dirty')"
                                    class="px-2 py-0.5 rounded text-[10px] font-bold transition cursor-pointer {{ $isDirty ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                                    title="Tandai Kotor"
                                >
                                    Kotor
                                </button>
                            </div>
                        </div>

                        {{-- Main Open POS Button --}}
                        <button
                            type="button"
                            wire:click.stop="selectAndOpenPos({{ $tbl->id }})"
                            class="w-full py-2.5 px-3 rounded-xl bg-brand-blue hover:bg-[#003da1] text-white text-xs font-black transition flex items-center justify-center gap-1.5 cursor-pointer shadow-xs active:scale-98"
                        >
                            <span>Buka Kasir POS</span>
                            <x-icon name="arrow-right" class="w-3.5 h-3.5 text-white" />
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-slate-500 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                    <p class="text-sm font-bold">Tidak ada meja ditemukan.</p>
                </div>
            @endforelse
        </div>
    </main>

    {{-- Modal Scan Kamera QR Tiket Pelanggan --}}
    @if ($showScannerModal)
        <div
            x-data="{
                scanner: null,
                cameraActive: false,
                cameraError: null,
                initScanner() {
                    this.cameraError = null;
                    this.cameraActive = false;
                    if (typeof Html5Qrcode === 'undefined') {
                        this.cameraError = 'Pemindai kamera belum terpasang sempurna.';
                        return;
                    }
                    this.$nextTick(() => {
                        this.startScanning();
                    });
                },
                startScanning() {
                    this.cameraError = null;
                    try {
                        if (this.scanner) {
                            try {
                                if (this.scanner.isScanning) {
                                    this.scanner.stop().then(() => this.scanner.clear()).catch(() => {});
                                } else {
                                    this.scanner.clear();
                                }
                            } catch (e) {}
                        }

                        this.scanner = new Html5Qrcode('qr-camera-stream', {
                            verbose: false,
                            experimentalFeatures: {
                                useBarCodeDetectorIfSupported: true
                            }
                        });

                        const config = {
                            fps: 20,
                            qrbox: (w, h) => {
                                const min = Math.min(w, h);
                                return { width: Math.floor(min * 0.82), height: Math.floor(min * 0.82) };
                            }
                        };

                        const handleDecoded = (decodedText) => {
                            if (navigator.vibrate) navigator.vibrate(50);
                            this.stopScanning();
                            $wire.handleScannedTicket(decodedText);
                        };

                        if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
                            Html5Qrcode.getCameras().then(devices => {
                                if (!devices || devices.length === 0) {
                                    this.cameraError = 'Tidak ada kamera yang terdeteksi.';
                                    this.cameraActive = false;
                                    return;
                                }

                                let backCam = devices.find(d => /back|rear|environment/i.test(d.label));
                                let selectedCam = backCam ? { deviceId: { exact: backCam.id } } : { deviceId: { exact: devices[0].id } };

                                this.scanner.start(selectedCam, config, handleDecoded, () => {})
                                    .then(() => {
                                        this.cameraActive = true;
                                        this.cameraError = null;
                                    })
                                    .catch(err => {
                                        console.warn('Gagal start kamera spesifik, coba fallback...', err);
                                        this.scanner.start({ facingMode: 'user' }, config, handleDecoded, () => {})
                                            .then(() => {
                                                this.cameraActive = true;
                                                this.cameraError = null;
                                            })
                                            .catch(fallbackErr => {
                                                console.error('Kamera gagal dibuka:', fallbackErr);
                                                this.cameraError = 'Izin kamera ditolak atau kamera sedang digunakan aplikasi lain.';
                                                this.cameraActive = false;
                                            });
                                    });
                            }).catch(err => {
                                console.warn('Gagal getCameras, coba generic...', err);
                                this.scanner.start({ facingMode: 'user' }, config, handleDecoded, () => {})
                                    .then(() => {
                                        this.cameraActive = true;
                                        this.cameraError = null;
                                    })
                                    .catch(genericErr => {
                                        console.error(genericErr);
                                        this.cameraError = 'Izin kamera belum aktif. Gunakan input kode manual.';
                                        this.cameraActive = false;
                                    });
                            });
                        } else {
                            this.cameraError = 'Browser tidak mendukung akses kamera langsung.';
                            this.cameraActive = false;
                        }
                    } catch (e) {
                        console.error('Crash scanner start:', e);
                        this.cameraError = 'Terjadi kendala saat menyalakan kamera.';
                        this.cameraActive = false;
                    }
                },
                stopScanning() {
                    if (this.scanner) {
                        try {
                            if (this.scanner.isScanning) {
                                this.scanner.stop().then(() => {
                                    this.scanner.clear();
                                    this.cameraActive = false;
                                }).catch(() => {
                                    this.cameraActive = false;
                                });
                            } else {
                                this.scanner.clear();
                                this.cameraActive = false;
                            }
                        } catch (e) {
                            this.cameraActive = false;
                        }
                    }
                    this.cameraActive = false;
                },
                closeModal() {
                    this.stopScanning();
                    $wire.closeScannerModal();
                }
            }"
            x-init="initScanner()"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
        >
            <div @click="closeModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
            <div class="relative w-full max-w-md bg-white rounded-3xl p-6 shadow-2xl border-2 border-slate-200 z-10 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-2xl bg-blue-50 text-brand-blue flex items-center justify-center">
                            <x-icon name="camera" class="w-4 h-4 text-brand-blue" />
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">Arahkan Kamera ke QR Tiket</h3>
                            <p class="text-[11px] text-slate-500 font-medium">Pindai QR order dari HP pelanggan</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="closeModal()"
                        class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center cursor-pointer transition active:scale-95"
                    >
                        <x-icon name="x" class="w-4 h-4" />
                    </button>
                </div>

                {{-- Camera Viewfinder Frame --}}
                <div wire:ignore class="relative w-full aspect-square max-h-72 bg-slate-950 rounded-2xl overflow-hidden flex items-center justify-center shadow-lg ring-1 ring-black/5">
                    <div wire:ignore id="qr-camera-stream" class="w-full h-full"></div>

                    {{-- Scanner Target Reticle (4 Corner Accents) --}}
                    <div class="absolute inset-5 pointer-events-none z-10 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <div class="w-6 h-6 border-t-3 border-l-3 border-white/85 rounded-tl-xl shadow-xs"></div>
                            <div class="w-6 h-6 border-t-3 border-r-3 border-white/85 rounded-tr-xl shadow-xs"></div>
                        </div>
                        <div class="flex justify-between items-end">
                            <div class="w-6 h-6 border-b-3 border-l-3 border-white/85 rounded-bl-xl shadow-xs"></div>
                            <div class="w-6 h-6 border-b-3 border-r-3 border-white/85 rounded-br-xl shadow-xs"></div>
                        </div>
                    </div>

                    {{-- Status Pill at Top --}}
                    <div class="absolute top-3 inset-x-0 flex justify-center pointer-events-none z-20">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/90 backdrop-blur-md text-[10px] font-bold text-slate-800 border border-white/60 shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full" :class="cameraActive ? 'bg-emerald-500 animate-pulse' : 'bg-amber-400'"></span>
                            <span x-text="cameraActive ? 'Kamera Kasir Aktif' : 'Menyiapkan Scanner...'">Scanner</span>
                        </span>
                    </div>

                    {{-- Instruction Pill at Bottom --}}
                    <div class="absolute bottom-3 inset-x-0 flex justify-center pointer-events-none z-20 px-3">
                        <span class="px-3 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-[10px] font-bold text-white text-center border border-white/10 shadow-xs">
                            Posisikan QR tiket di dalam kotak
                        </span>
                    </div>

                    <template x-if="cameraError">
                        <div class="absolute inset-0 bg-slate-900/95 text-white p-4 flex flex-col items-center justify-center text-center space-y-2 z-30">
                            <x-icon name="alert-circle" class="w-8 h-8 text-rose-500" />
                            <p class="text-xs font-bold text-rose-300" x-text="cameraError"></p>
                            <p class="text-[11px] text-slate-400">Gunakan kotak input manual di bawah atau upload file QR.</p>
                        </div>
                    </template>
                </div>

                {{-- Input Manual Kode Tiket --}}
                <div class="space-y-2 pt-1">
                    <form wire:submit.prevent="scanOrSearchTicket" class="flex gap-2">
                        <input
                            type="text"
                            wire:model="ticketSearch"
                            placeholder="Atau ketik kode (misal ORD-...)"
                            class="flex-1 h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:bg-white transition"
                        />
                        <button
                            type="submit"
                            class="px-4 h-10 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold cursor-pointer transition flex items-center justify-center shrink-0 shadow-2xs active:scale-95"
                        >
                            <span>Cari</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Konfirmasi Tiket Tunai & Terima Pembayaran --}}
    @if ($showTicketModal && $selectedTicketOrder)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div wire:click="closeTicketModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
            <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl border-2 border-slate-200 z-10 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-md bg-slate-900 text-white font-mono font-black text-xs">
                                {{ $selectedTicketOrder->table ? 'MEJA '.$selectedTicketOrder->table->table_number : 'TAKEAWAY' }}
                            </span>
                            <span class="font-mono text-xs font-bold text-slate-500">
                                {{ $selectedTicketOrder->order_code }}
                            </span>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 mt-1">
                            Pesanan: {{ $selectedTicketOrder->customer_name }}
                        </h3>
                    </div>
                    <button
                        type="button"
                        wire:click="closeTicketModal"
                        class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center cursor-pointer transition"
                    >
                        <x-icon name="x" class="w-4 h-4" />
                    </button>
                </div>

                {{-- Item List --}}
                <div class="max-h-48 overflow-y-auto space-y-2 pr-1">
                    @foreach ($selectedTicketOrder->orderItems as $item)
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-md bg-slate-900 text-white font-black text-xs font-mono">
                                    {{ $item->quantity }}x
                                </span>
                                <div>
                                    <span class="font-bold text-slate-900">{{ $item->menu?->name }}</span>
                                    @if ($item->item_notes)
                                        <div class="text-[10px] text-slate-600 font-semibold">{{ $item->item_notes }}</div>
                                    @endif
                                </div>
                            </div>
                            <span class="font-mono font-bold text-slate-800">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                {{-- Tagihan & Uang Diterima --}}
                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 space-y-2.5 text-xs">
                    <div class="flex items-center justify-between font-bold text-slate-700">
                        <span>Total Tagihan:</span>
                        <span class="font-mono font-black text-slate-900 text-base">
                            Rp {{ number_format($selectedTicketOrder->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                        <span class="font-bold text-slate-600">Uang Diterima:</span>
                        <input
                            type="number"
                            wire:model.live="ticketCashGiven"
                            class="w-36 h-9 px-3 text-right bg-white border border-slate-300 rounded-lg font-mono font-black text-slate-900 focus:outline-none focus:border-slate-800"
                        />
                    </div>

                    <div class="flex gap-1.5 pt-1">
                        <button
                            type="button"
                            wire:click="setExactTicketCash"
                            class="flex-1 py-1 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-[11px] font-bold text-slate-700 cursor-pointer"
                        >
                            Uang Pas
                        </button>
                        <button
                            type="button"
                            wire:click="setTicketCashGiven(50000)"
                            class="flex-1 py-1 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-[11px] font-bold text-slate-700 cursor-pointer"
                        >
                            50.000
                        </button>
                        <button
                            type="button"
                            wire:click="setTicketCashGiven(100000)"
                            class="flex-1 py-1 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-[11px] font-bold text-slate-700 cursor-pointer"
                        >
                            100.000
                        </button>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                        <span class="font-bold text-slate-600">Kembalian:</span>
                        <span class="font-mono font-black text-emerald-600 text-base">
                            {{ $this->formatted_ticket_change }}
                        </span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2 pt-1">
                    <button
                        type="button"
                        wire:click="closeTicketModal"
                        class="flex-1 h-11 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        wire:click="confirmTicketPayment"
                        @disabled($ticketCashGiven < (float)$selectedTicketOrder->total_amount)
                        class="flex-2 h-11 bg-brand-blue hover:bg-[#003da1] disabled:opacity-40 text-white rounded-xl text-xs font-black transition cursor-pointer flex items-center justify-center gap-1.5 shadow-xs disabled:cursor-not-allowed active:scale-98"
                    >
                        <x-icon name="check" class="w-4 h-4 text-white" />
                        <span>Terima Tunai & Kirim ke Dapur</span>
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

    <style>
        #qr-camera-stream {
            border: none !important;
            background: transparent !important;
            width: 100% !important;
            height: 100% !important;
        }
        #qr-camera-stream video {
            display: block !important;
            object-fit: cover !important;
            width: 100% !important;
            height: 100% !important;
            border-radius: 1rem !important;
        }
        #qr-camera-stream img,
        #qr-camera-stream svg,
        #qr-shaded-region {
            display: none !important;
        }
    </style>
</div>
