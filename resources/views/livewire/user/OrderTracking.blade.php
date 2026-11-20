<div
    wire:poll.10s="refreshStatus"
    x-data="{
        openMidtrans(token) {
            if (window.snap && token) {
                window.snap.pay(token, {
                    onSuccess: (result) => {
                        $wire.handleMidtransSuccess();
                    },
                    onPending: (result) => {
                        console.log('Pending:', result);
                    },
                    onError: (result) => {
                        alert('Pembayaran Midtrans tidak berhasil diselesaikan.');
                    },
                    onClose: () => {
                        console.log('Snap closed');
                    }
                });
            } else {
                $wire.payWithMidtrans();
            }
        }
    }"
    class="min-h-screen bg-[#F8FAFC] pb-16 font-sans text-slate-800"
>
    {{-- Toast Notification --}}
    @include('livewire.reuse-component.Toast-notification')

    {{-- Top Navigation Bar --}}
    <header class="sticky top-0 z-40 bg-white border-b border-slate-200 px-4 sm:px-8 py-3.5 shadow-2xs">
        <div class="max-w-5xl mx-auto flex items-center justify-between gap-4">
            {{-- Left: Back to Order & Title --}}
            <div class="flex items-center gap-3 min-w-0">
                <a
                    href="{{ route('customer.order') }}"
                    wire:navigate
                    class="w-9 h-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 flex items-center justify-center text-slate-700 hover:text-brand-blue hover:border-brand-blue transition active:scale-95 shrink-0"
                    title="Kembali ke Menu"
                >
                    <x-icon name="toleft" class="w-4 h-4 text-slate-700 hover:text-brand-blue transition" />
                </a>

                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight truncate">
                            Status Pesanan
                        </h1>
                        <span class="font-mono text-xs font-bold bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md border border-slate-200">
                            #{{ $orderCode }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 font-medium truncate">
                        Meja {{ $tableNumber }} &bull; Atas Nama <strong class="text-slate-700">{{ $customerName }}</strong>
                    </p>
                </div>
            </div>

            {{-- Right: Live Pulse & Order More --}}
            <div class="flex items-center gap-2.5 shrink-0">
                <div class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Live Update</span>
                </div>

                <a
                    href="{{ route('customer.order') }}"
                    wire:navigate
                    class="px-3 sm:px-3.5 py-1.5 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition active:scale-95 flex items-center gap-1.5 shadow-2xs"
                >
                    <x-icon name="plus" class="w-3.5 h-3.5 text-white" />
                    <span>Pesan Lagi</span>
                </a>
            </div>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="max-w-5xl mx-auto px-4 sm:px-6 pt-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- LEFT COLUMN: Status Hero, Connected Stepper, Order Items --}}
            <div class="lg:col-span-7 space-y-6">
                {{-- 1. Status Hero Card with Connected Stepper --}}
                <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-7 shadow-xs">
                    {{-- Header Row: Status Title & Time Info --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-100">
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold mb-2.5 {{ $paymentStatus === 'paid' ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : 'bg-amber-50 border border-amber-200 text-amber-800' }}">
                                <span class="w-2 h-2 rounded-full {{ $paymentStatus === 'paid' ? 'bg-emerald-500' : 'bg-amber-500 animate-pulse' }}"></span>
                                <span>{{ $paymentStatus === 'paid' ? 'Pesanan Terkonfirmasi' : 'Menunggu Pembayaran' }}</span>
                            </div>

                            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                                @if ($orderStatus === 'pending')
                                    Pesanan Diterima
                                @elseif ($orderStatus === 'processing')
                                    Sedang Diracik Barista
                                @elseif ($orderStatus === 'ready')
                                    Pesanan Siap Saji!
                                @elseif ($orderStatus === 'completed')
                                    Pesanan Selesai
                                @else
                                    Status: {{ ucfirst($orderStatus) }}
                                @endif
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium leading-relaxed">
                                @if ($orderStatus === 'pending')
                                    Pesanan telah diterima sistem dan masuk antrean dapur.
                                @elseif ($orderStatus === 'processing')
                                    Barista sedang meracik minuman & makanan sesuai pesananmu.
                                @elseif ($orderStatus === 'ready')
                                    Hidangan telah selesai dan siap diantarkan ke Meja {{ $tableNumber }}.
                                @elseif ($orderStatus === 'completed')
                                    Semua hidangan telah disajikan di mejamu. Selamat menikmati!
                                @endif
                            </p>
                        </div>

                        {{-- Estimasi & Waktu --}}
                        <div class="sm:text-right bg-slate-50 sm:bg-transparent p-3 sm:p-0 rounded-2xl border sm:border-0 border-slate-100 shrink-0">
                            <span class="text-[11px] font-bold text-slate-400 block uppercase tracking-wider">Estimasi Selesai</span>
                            <span class="text-lg sm:text-xl font-black text-brand-blue font-mono">
                                @if ($orderStatus === 'completed')
                                    SELESAI
                                @elseif ($orderStatus === 'ready')
                                    SIAP DI MEJA
                                @else
                                    ~ 8 - 12 Menit
                                @endif
                            </span>
                            <span class="text-[11px] text-slate-400 block mt-0.5 font-medium">
                                Masuk pukul {{ $orderTime }} WIB
                            </span>
                        </div>
                    </div>

                    {{-- Connected 4-Step Stepper Progress Bar --}}
                    <div class="pt-6 sm:pt-7">
                        <div class="relative">
                            {{-- Continuous Track Line connecting the 4 circles --}}
                            <div class="absolute top-5 h-1.5 bg-slate-200 rounded-full -translate-y-1/2 overflow-hidden z-0" style="left: 12.5%; right: 12.5%;">
                                <div
                                    class="h-full bg-brand-blue rounded-full transition-all duration-500"
                                    style="width: {{ $this->currentStep === 1 ? '0%' : ($this->currentStep === 2 ? '33.33%' : ($this->currentStep === 3 ? '66.66%' : '100%')) }};"
                                ></div>
                            </div>

                            {{-- 4 Stepper Node Circles --}}
                            <div class="relative z-10 grid grid-cols-4 gap-2 text-center">
                                {{-- Step 1: Diterima --}}
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm mb-2 ring-4 ring-white transition-all {{ $this->currentStep >= 1 ? 'bg-brand-blue text-white shadow-xs' : 'bg-white text-slate-400 border-2 border-slate-200' }}">
                                        @if ($this->currentStep > 1)
                                            <x-icon name="check" class="w-4 h-4 text-white" />
                                        @else
                                            1
                                        @endif
                                    </div>
                                    <span class="text-xs font-bold {{ $this->currentStep >= 1 ? 'text-slate-900' : 'text-slate-400' }}">
                                        Masuk
                                    </span>
                                    <span class="text-[10px] text-slate-400 hidden sm:block">Diterima</span>
                                </div>

                                {{-- Step 2: Diracik --}}
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm mb-2 ring-4 ring-white transition-all {{ $this->currentStep >= 2 ? 'bg-brand-blue text-white shadow-xs' : 'bg-white text-slate-400 border-2 border-slate-200' }}">
                                        @if ($this->currentStep > 2)
                                            <x-icon name="check" class="w-4 h-4 text-white" />
                                        @else
                                            2
                                        @endif
                                    </div>
                                    <span class="text-xs font-bold {{ $this->currentStep >= 2 ? 'text-slate-900' : 'text-slate-400' }}">
                                        Diracik
                                    </span>
                                    <span class="text-[10px] text-slate-400 hidden sm:block">Barista</span>
                                </div>

                                {{-- Step 3: Siap Saji --}}
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm mb-2 ring-4 ring-white transition-all {{ $this->currentStep >= 3 ? 'bg-brand-blue text-white shadow-xs' : 'bg-white text-slate-400 border-2 border-slate-200' }}">
                                        @if ($this->currentStep > 3)
                                            <x-icon name="check" class="w-4 h-4 text-white" />
                                        @else
                                            3
                                        @endif
                                    </div>
                                    <span class="text-xs font-bold {{ $this->currentStep >= 3 ? 'text-slate-900' : 'text-slate-400' }}">
                                        Siap Saji
                                    </span>
                                    <span class="text-[10px] text-slate-400 hidden sm:block">Meja Saji</span>
                                </div>

                                {{-- Step 4: Selesai --}}
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm mb-2 ring-4 ring-white transition-all {{ $this->currentStep >= 4 ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-slate-400 border-2 border-slate-200' }}">
                                        @if ($this->currentStep >= 4)
                                            <x-icon name="check" class="w-4 h-4 text-white" />
                                        @else
                                            4
                                        @endif
                                    </div>
                                    <span class="text-xs font-bold {{ $this->currentStep >= 4 ? 'text-emerald-700' : 'text-slate-400' }}">
                                        Selesai
                                    </span>
                                    <span class="text-[10px] text-slate-400 hidden sm:block">Di Meja</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Order Items List Card --}}
                <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs">
                    <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 mb-2">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-extrabold text-slate-900">
                                Rincian Pesanan
                            </h3>
                            <span class="text-xs bg-slate-100 text-slate-600 font-bold px-2 py-0.5 rounded-full border border-slate-200">
                                {{ count($orderItems) }} Menu
                            </span>
                        </div>
                        <span class="text-xs font-mono font-bold text-slate-400">
                            #{{ substr((string) $orderCode, -5) }}
                        </span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach ($orderItems as $item)
                            <div class="py-3 flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3 min-w-0">
                                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-brand-blue text-xs font-black flex items-center justify-center shrink-0 border border-blue-100 mt-0.5">
                                        {{ $item['quantity'] }}x
                                    </span>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold text-slate-900 leading-snug truncate">
                                            {{ $item['name'] }}
                                        </h4>
                                        @if (!empty($item['notes']))
                                            <p class="text-xs text-amber-700 bg-amber-50/70 border border-amber-200/60 rounded-md px-2 py-0.5 mt-1 inline-block">
                                                Catatan: {{ $item['notes'] }}
                                            </p>
                                        @endif
                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $item['formatted_price'] }} / porsi
                                        </div>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-slate-900 shrink-0 font-mono">
                                    {{ $item['formatted_subtotal'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- General Notes if any --}}
                    @if ($orderNotes)
                        <div class="mt-4 p-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 flex items-start gap-2">
                            <x-icon name="alert-circle" class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" />
                            <div>
                                <strong class="text-slate-800 font-bold">Catatan Umum:</strong> {{ $orderNotes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- RIGHT COLUMN: Sticky Payment Receipt & Action Card --}}
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs lg:sticky lg:top-24">
                    {{-- Card Title & Payment Status Pill --}}
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Struk & Pembayaran</span>
                        @if ($paymentStatus === 'paid')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-extrabold text-xs">
                                <x-icon name="check" class="w-3.5 h-3.5 text-emerald-600" />
                                <span>LUNAS</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 font-extrabold text-xs">
                                <x-icon name="clock" class="w-3.5 h-3.5 text-amber-600 animate-pulse" />
                                <span>BELUM BAYAR</span>
                            </span>
                        @endif
                    </div>

                    {{-- Order Metadata --}}
                    <div class="py-4 space-y-2.5 text-xs">
                        <div class="flex justify-between items-center text-slate-500">
                            <span>Nomor Meja</span>
                            <strong class="text-slate-900 font-bold">Meja {{ $tableNumber }}</strong>
                        </div>
                        <div class="flex justify-between items-center text-slate-500">
                            <span>Nama Pemesan</span>
                            <strong class="text-slate-900 font-bold">{{ $customerName }}</strong>
                        </div>
                        <div class="flex justify-between items-center text-slate-500">
                            <span>Metode Pembayaran</span>
                            <strong class="text-slate-900 font-bold uppercase">{{ $paymentMethod }}</strong>
                        </div>
                        <div class="flex justify-between items-center text-slate-500">
                            <span>Waktu Pemesanan</span>
                            <strong class="text-slate-900 font-mono">{{ $orderTime }} WIB</strong>
                        </div>
                    </div>

                    {{-- Transparent Cost Breakdown --}}
                    <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
                        <div class="flex justify-between items-center text-slate-500">
                            <span>Subtotal Menu</span>
                            <span class="font-mono text-slate-800 font-bold">{{ $formattedSubtotalAmount }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-500">
                            <span>Pajak Restoran (PB1 10%)</span>
                            <span class="font-mono text-slate-800 font-bold">{{ $formattedTaxAmount }}</span>
                        </div>
                        <div class="pt-3 border-t-2 border-dashed border-slate-200 flex justify-between items-baseline">
                            <span class="text-sm font-extrabold text-slate-900">Total Tagihan</span>
                            <span class="text-xl font-black text-brand-blue font-mono">{{ $formattedTotalAmount }}</span>
                        </div>
                    </div>

                    {{-- Payment Actions / QRIS / Cash Guide --}}
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        @if ($paymentStatus !== 'paid')
                            @if ($paymentMethod === 'qris')
                                <div class="space-y-3">
                                    <button
                                        type="button"
                                        @click="openMidtrans('{{ $snapToken }}')"
                                        class="w-full py-3 bg-brand-blue hover:bg-[#003da1] text-white rounded-2xl font-bold text-xs shadow-xs transition active:scale-98 flex items-center justify-center gap-2 cursor-pointer"
                                    >
                                        <x-icon name="qrcode" class="w-4 h-4 text-white" />
                                        <span>Buka Tiket Pembayaran QRIS</span>
                                    </button>

                                    @if ($qrisUrl)
                                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 text-center">
                                            <p class="text-[11px] font-bold text-slate-600 mb-2">Scan Langsung via Aplikasi Pembayaran</p>
                                            <div class="w-36 h-36 mx-auto bg-white p-2 rounded-xl border border-slate-200 flex items-center justify-center">
                                                <img src="{{ $qrisUrl }}" alt="QRIS Code" class="w-full h-full object-contain" />
                                            </div>
                                        </div>
                                    @endif

                                    @if (app()->environment('local', 'testing'))
                                        <button
                                            type="button"
                                            wire:click="simulatePayQris"
                                            class="w-full py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-300 rounded-xl font-bold text-[11px] transition cursor-pointer"
                                        >
                                            Simulasikan Bayar Lunas (Testing Sandbox)
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="p-4 bg-amber-50/80 rounded-2xl border border-amber-200 text-center space-y-2">
                                    <div class="w-9 h-9 mx-auto rounded-full bg-amber-100 text-amber-700 flex items-center justify-center">
                                        <x-icon name="alert-circle" class="w-5 h-5" />
                                    </div>
                                    <h4 class="text-xs font-black text-amber-900">Pembayaran Tunai di Kasir</h4>
                                    <p class="text-[11px] text-amber-800 leading-relaxed">
                                        Tunjukkan kode pesanan <strong class="font-mono">#{{ $orderCode }}</strong> ke kasir di counter depan untuk menyelesaikan transaksi.
                                    </p>
                                    @if ($cashQrUrl)
                                        <div class="w-32 h-32 mx-auto bg-white p-2 rounded-xl border border-amber-200 mt-2">
                                            <img src="{{ $cashQrUrl }}" alt="Cash QR" class="w-full h-full object-contain" />
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="p-3.5 bg-emerald-50/70 border border-emerald-200 rounded-2xl flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <x-icon name="check" class="w-4 h-4" />
                                </div>
                                <div class="text-xs">
                                    <strong class="text-emerald-900 block font-bold">Pembayaran Terverifikasi</strong>
                                    <span class="text-emerald-700 text-[11px]">Terima kasih, pesananmu siap diproses!</span>
                                </div>
                            </div>
                        @endif

                        {{-- Secondary Actions --}}
                        <div class="mt-4 pt-3 border-t border-slate-100 flex flex-col gap-2">
                            <a
                                href="{{ route('customer.order') }}"
                                wire:navigate
                                class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs rounded-xl text-center transition active:scale-98"
                            >
                                + Pesan Menu Tambahan
                            </a>
                            <p class="text-[11px] text-slate-400 text-center font-medium">
                                Butuh bantuan? Silakan panggil staf atau barista kami.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Interactive QRIS Modal for Unpaid Orders (Fallback) --}}
    @if ($showQrisModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div wire:click="$set('showQrisModal', false)" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
            <div class="relative w-full max-w-sm bg-white rounded-3xl p-6 shadow-2xl border border-slate-200 z-10 text-center">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <span class="text-xs font-black text-brand-blue">QRIS NONGKRONG.ID</span>
                    <button wire:click="$set('showQrisModal', false)" class="text-slate-400 hover:text-slate-700 p-1 cursor-pointer">
                        <x-icon name="close" class="w-4 h-4" />
                    </button>
                </div>

                <div class="w-44 h-44 mx-auto bg-white p-2 border border-slate-900 rounded-2xl flex items-center justify-center shadow-inner my-2">
                    @if ($qrisUrl)
                        <img src="{{ $qrisUrl }}" alt="QRIS Code" class="w-full h-full object-contain" />
                    @else
                        <svg class="w-full h-full text-slate-900" viewBox="0 0 120 120" fill="currentColor">
                            <rect x="10" y="10" width="30" height="30" rx="4" fill="none" stroke="currentColor" stroke-width="6"/>
                            <rect x="18" y="18" width="14" height="14" rx="2"/>
                            <rect x="80" y="10" width="30" height="30" rx="4" fill="none" stroke="currentColor" stroke-width="6"/>
                            <rect x="88" y="18" width="14" height="14" rx="2"/>
                            <rect x="10" y="80" width="30" height="30" rx="4" fill="none" stroke="currentColor" stroke-width="6"/>
                            <rect x="18" y="88" width="14" height="14" rx="2"/>
                            <rect x="46" y="12" width="6" height="6"/><rect x="58" y="12" width="6" height="6"/>
                            <rect x="46" y="34" width="6" height="6"/><rect x="60" y="34" width="14" height="6"/>
                            <rect x="12" y="46" width="6" height="10"/><rect x="52" y="46" width="16" height="16"/>
                            <rect x="74" y="60" width="6" height="10"/><rect x="86" y="64" width="12" height="6"/>
                            <rect x="46" y="80" width="6" height="14"/><rect x="74" y="80" width="6" height="8"/>
                        </svg>
                    @endif
                </div>

                <div class="text-xs text-slate-500 mb-2">Total Tagihan: <strong class="text-brand-blue font-black">{{ $formattedTotalAmount }}</strong></div>

                <div class="space-y-2 mt-3">
                    <button
                        type="button"
                        wire:click="payWithMidtrans"
                        class="w-full h-11 bg-brand-blue hover:bg-[#003da1] text-white rounded-full font-bold text-xs cursor-pointer shadow-xs flex items-center justify-center gap-2 transition"
                    >
                        <span>Buka Pop-up Midtrans Snap</span>
                    </button>

                    @if (app()->environment('local', 'testing'))
                        <button
                            type="button"
                            wire:click="simulatePayQris"
                            class="w-full h-10 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-bold text-xs cursor-pointer shadow-xs flex items-center justify-center gap-2 transition"
                        >
                            <x-icon name="check" class="w-3.5 h-3.5" />
                            <span>Simulasikan Pembayaran Berhasil</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
