<div
    wire:poll.3s="refreshStatus"
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
    class="min-h-screen bg-[#F8FAFC] pb-16 font-sans"
>

    {{-- Toast Notification --}}
    @include('livewire.reuse-component.Toast-notification')

    {{-- Top Navigation Bar --}}
    <header class="sticky top-0 z-40 bg-white border-b-2 border-[#E2E8F0] px-4 sm:px-8 py-3.5">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('customer.order') }}"
                    wire:navigate
                    class="w-9 h-9 rounded-xl border-2 border-[#CBD5E1] bg-white flex items-center justify-center text-slate-700 hover:border-brand-blue hover:text-brand-blue transition font-black text-sm"
                    title="Kembali ke Menu"
                >
                    <x-icon name="toleft" class="w-4 h-4 text-slate-700 hover:text-brand-blue transition" />
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-extrabold text-brand-blue font-mono tracking-tight">{{ $orderCode }}</span>
                        <span class="text-[10px] bg-blue-50 text-brand-blue font-bold px-2 py-0.5 rounded-md border border-blue-200">
                            Meja {{ $tableNumber }}
                        </span>
                    </div>
                    <h1 class="text-sm sm:text-base font-black text-[#0F172A]">Live Order Tracking</h1>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <div class="hidden sm:inline-flex">
                    @include('livewire.reuse-component.StatusBadge', [
                        'variant' => 'live',
                        'label' => 'Live Tracking',
                        'pulse' => true,
                    ])
                </div>
                <a
                    href="{{ route('customer.order') }}"
                    wire:navigate
                    class="px-3.5 py-1.5 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition flex items-center gap-1"
                >
                    + Menu Baru
                </a>
            </div>
        </div>
    </header>

    {{-- Main Tracking Container --}}
    <main class="max-w-4xl mx-auto px-4 sm:px-6 pt-6 space-y-6">
        {{-- Status Hero Card --}}
        <div class="bg-white border-2 border-brand-blue rounded-3xl p-5 sm:p-7 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 border border-blue-200 rounded-full text-brand-blue text-xs font-bold mb-2">
                        <span class="w-2 h-2 rounded-full bg-brand-blue animate-ping"></span>
                        Pesanan Meja {{ $tableNumber }} • {{ $customerName }}
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-[#0F172A]">
                        @if ($orderStatus === 'pending')
                            Pesanan Diterima • Menunggu Dapur
                        @elseif ($orderStatus === 'processing')
                            Sedang Diracik Barista ☕
                        @elseif ($orderStatus === 'ready')
                            Pesanan Siap Saji! 🍽️
                        @elseif ($orderStatus === 'completed')
                            Pesanan Selesai • Selamat Menikmati! ✨
                        @else
                            Status: {{ ucfirst($orderStatus) }}
                        @endif
                    </h2>
                    <p class="text-xs sm:text-sm text-[#64748B] mt-1 font-medium">
                        @if ($orderStatus === 'pending')
                            Pesanan telah masuk antrean kasir & dapur. Mohon tunggu konfirmasi.
                        @elseif ($orderStatus === 'processing')
                            Barista sedang meracik minuman & hidangan sesuai catatan pesananmu.
                        @elseif ($orderStatus === 'ready')
                            Pesanan sudah siap di pickup bar dan segera diantarkan ke Meja {{ $tableNumber }}.
                        @elseif ($orderStatus === 'completed')
                            Semua pesanan telah diantarkan ke meja. Terima kasih sudah nongkrong di sini!
                        @endif
                    </p>
                </div>

                {{-- Time & Status Badge --}}
                <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100">
                    <span class="text-xs text-slate-400 font-bold">Estimasi Selesai</span>
                    <span class="text-lg sm:text-xl font-black text-brand-blue font-mono">
                        @if ($orderStatus === 'completed')
                            SELESAI
                        @elseif ($orderStatus === 'ready')
                            SIAP SAJI
                        @else
                            ~ 8 - 12 Menit
                        @endif
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium">Dipesan pukul {{ $orderTime }} WIB</span>
                </div>
            </div>

            {{-- 4-Step Stepper Progress Bar --}}
            <div class="pt-6">
                <div class="grid grid-cols-4 gap-2 text-center">
                    {{-- Step 1 --}}
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl flex items-center justify-center font-bold text-xs sm:text-sm mb-2 {{ $this->currentStep >= 1 ? 'bg-brand-blue text-white' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                            @if ($this->currentStep > 1) ✓ @else 1 @endif
                        </div>
                        <span class="text-[11px] sm:text-xs font-black {{ $this->currentStep >= 1 ? 'text-brand-blue' : 'text-slate-400' }}">Masuk</span>
                        <span class="text-[10px] text-slate-400 hidden sm:block">Diterima</span>
                    </div>

                    {{-- Step 2 --}}
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl flex items-center justify-center font-bold text-xs sm:text-sm mb-2 {{ $this->currentStep >= 2 ? 'bg-brand-blue text-white' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                            @if ($this->currentStep > 2) ✓ @else 2 @endif
                        </div>
                        <span class="text-[11px] sm:text-xs font-black {{ $this->currentStep >= 2 ? 'text-brand-blue' : 'text-slate-400' }}">Racik</span>
                        <span class="text-[10px] text-slate-400 hidden sm:block">Di Dapur</span>
                    </div>

                    {{-- Step 3 --}}
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl flex items-center justify-center font-bold text-xs sm:text-sm mb-2 {{ $this->currentStep >= 3 ? 'bg-brand-blue text-white' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                            @if ($this->currentStep > 3) ✓ @else 3 @endif
                        </div>
                        <span class="text-[11px] sm:text-xs font-black {{ $this->currentStep >= 3 ? 'text-brand-blue' : 'text-slate-400' }}">Siap</span>
                        <span class="text-[10px] text-slate-400 hidden sm:block">Di Meja Saji</span>
                    </div>

                    {{-- Step 4 --}}
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl flex items-center justify-center font-bold text-xs sm:text-sm mb-2 {{ $this->currentStep >= 4 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                            @if ($this->currentStep >= 4) ✓ @else 4 @endif
                        </div>
                        <span class="text-[11px] sm:text-xs font-black {{ $this->currentStep >= 4 ? 'text-emerald-600' : 'text-slate-400' }}">Selesai</span>
                        <span class="text-[10px] text-slate-400 hidden sm:block">Di Meja</span>
                    </div>
                </div>

                {{-- Progress Bar Line --}}
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden mt-4">
                    <div
                        class="bg-brand-blue h-full transition-all duration-500 rounded-full"
                        style="width: {{ match ($this->currentStep) { 1 => '25%', 2 => '50%', 3 => '75%', default => '100%' } }};"
                    ></div>
                </div>
            </div>
        </div>

        {{-- Payment Status Box --}}
        <div class="bg-white border-2 border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl {{ $paymentStatus === 'paid' ? 'bg-emerald-50 text-emerald-600 border-2 border-emerald-200' : 'bg-amber-50 text-amber-600 border-2 border-amber-200' }} flex items-center justify-center text-xl shrink-0">
                    {{ $paymentStatus === 'paid' ? '💳' : '⏳' }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-400">Status Pembayaran:</span>
                        <span class="text-xs font-black px-2.5 py-0.5 rounded-full {{ $paymentStatus === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $paymentStatus === 'paid' ? 'LUNAS (PAID)' : 'BELUM DIBAYAR (UNPAID)' }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Metode: <strong class="text-slate-800 font-bold">{{ strtoupper($paymentMethod) }}</strong> • Total: <strong class="text-brand-blue font-black">{{ $formattedTotalAmount }}</strong>
                    </p>
                </div>
            </div>

            @if ($paymentStatus !== 'paid')
                @if ($paymentMethod === 'qris')
                    <button
                        type="button"
                        wire:click="$set('showQrisModal', true)"
                        class="px-4 py-2.5 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-black transition cursor-pointer flex items-center justify-center gap-2"
                    >
                        <span>Scan / Bayar QRIS Sekarang</span>
                    </button>
                @else
                    <div class="px-4 py-2 bg-amber-50 rounded-xl border border-amber-200 text-amber-800 text-xs font-bold text-center">
                        Bayar Tunai di Kasir Meja Depan
                    </div>
                @endif
            @else
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200 flex items-center gap-1.5">
                    <span>✓</span> Transaksi Selesai & Terverifikasi
                </span>
            @endif
        </div>

        {{-- Order Items Card --}}
        <div class="bg-white border-2 border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs">
            <h3 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-3 mb-4 flex items-center justify-between">
                <span>Rincian Item Dipesan ({{ count($orderItems) }} Item)</span>
                <span class="font-mono text-xs text-slate-400">{{ $orderCode }}</span>
            </h3>

            <div class="divide-y divide-slate-100">
                @foreach ($orderItems as $item)
                    <div class="py-3 flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-lg bg-blue-50 text-brand-blue text-xs font-black flex items-center justify-center shrink-0 mt-0.5">
                                {{ $item['quantity'] }}x
                            </span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">{{ $item['name'] }}</h4>
                                @if (!empty($item['notes']))
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        📝 {{ $item['notes'] }}
                                    </p>
                                @endif
                                <span class="text-xs text-slate-400">{{ $item['formatted_price'] }} / item</span>
                            </div>
                        </div>
                        <span class="text-sm font-black text-slate-900 shrink-0 font-mono">
                            {{ $item['formatted_subtotal'] }}
                        </span>
                    </div>
                @endforeach
            </div>

            @if ($orderNotes)
                <div class="mt-4 p-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600">
                    <strong class="text-slate-800">Catatan Pesanan:</strong> {{ $orderNotes }}
                </div>
            @endif

            <div class="mt-5 pt-4 border-t-2 border-dashed border-slate-200 flex justify-between items-center">
                <span class="text-sm font-black text-slate-900">Total Pembayaran</span>
                <span class="text-base font-black text-brand-blue font-mono">{{ $formattedTotalAmount }}</span>
            </div>
        </div>
    </main>

    {{-- Interactive QRIS Modal for Unpaid Orders --}}
    @if ($showQrisModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div wire:click="$set('showQrisModal', false)" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
            <div class="relative w-full max-w-sm bg-white rounded-3xl p-6 shadow-2xl border-2 border-slate-200 z-10 text-center">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <span class="text-xs font-black text-brand-blue">QRIS NONGKRONG.ID</span>
                    <button wire:click="$set('showQrisModal', false)" class="text-slate-400 hover:text-slate-700 p-1 cursor-pointer">
                        <x-icon name="close" class="w-4 h-4" />
                    </button>
                </div>

                {{-- QR Code Visual --}}
                <div class="w-48 h-48 mx-auto bg-white p-2 border-2 border-slate-900 rounded-2xl flex items-center justify-center shadow-inner my-2">
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
                </div>

                <div class="text-xs text-slate-500 mb-2">Total Tagihan: <strong class="text-brand-blue font-black">{{ $formattedTotalAmount }}</strong></div>

                <div class="space-y-2 mt-3">
                    <button
                        type="button"
                        wire:click="payWithMidtrans"
                        class="w-full h-11 bg-brand-blue hover:bg-[#003da1] text-white rounded-full font-bold text-xs cursor-pointer shadow-md flex items-center justify-center gap-2 transition"
                    >
                        <x-icon name="card" class="w-4 h-4" />
                        <span>Buka Pop-up Midtrans Snap (Sandbox)</span>
                    </button>

                    <button
                        type="button"
                        wire:click="simulatePayQris"
                        class="w-full h-10 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-bold text-xs cursor-pointer shadow-sm flex items-center justify-center gap-2 transition"
                    >
                        <x-icon name="check" class="w-3.5 h-3.5" />
                        <span>Simulasikan Pembayaran Berhasil (Instant Backup)</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
