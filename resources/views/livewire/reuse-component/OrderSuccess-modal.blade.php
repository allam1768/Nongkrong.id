{{-- 7. ORDER SUCCESS & PAYMENT SIMULATOR MODAL --}}
@if ($showOrderSuccessModal)
    <div
        x-data
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
        role="dialog"
        aria-modal="true"
    >
        {{-- Polling watcher for Auto Cek when enabled and order is still pending --}}
        @if ($autoCheckPayment && ! $placedOrderPaid)
            <div wire:poll.3s="checkPaymentStatus" class="hidden"></div>
        @endif

        <div
            wire:click="closeOrderSuccess"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
        ></div>

        <div
            class="relative w-full max-w-sm sm:max-w-md bg-white rounded-3xl p-6 sm:p-7 shadow-2xl border-2 border-slate-200 z-10 text-center max-h-[92vh] overflow-y-auto"
            @click.stop
        >
            {{-- Header Icon & Title --}}
            @if (! $placedOrderPaid)
                @if (($placedPaymentMethod ?? $paymentMethod) === 'qris')
                    {{-- QRIS Pending State --}}
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 border border-amber-200 rounded-full text-amber-700 text-xs font-bold mb-3">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Menunggu Pembayaran QRIS Midtrans
                    </div>
                    <h3 class="text-xl font-black text-slate-900">
                        Scan QRIS untuk Bayar
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Selesaikan pembayaran agar pesanan otomatis terkonfirmasi dan diracik barista.
                    </p>

                    {{-- QRIS Card --}}
                    <div class="my-4 p-4 rounded-2xl bg-white border-2 border-brand-blue/30 shadow-xs text-center space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-600 border-b border-slate-100 pb-2">
                            <span class="text-brand-blue font-black tracking-wider flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                QRIS RESMI MIDTRANS
                            </span>
                            <span class="font-mono text-slate-500 text-[11px]">{{ $placedOrderCode }}</span>
                        </div>

                        {{-- QR Code Image Directly from Midtrans --}}
                        @if ($placedQrisUrl)
                            <div class="w-48 sm:w-56 h-48 sm:h-56 mx-auto bg-white p-2 border-2 border-slate-900 rounded-2xl flex items-center justify-center shadow-xs overflow-hidden">
                                <img
                                    src="{{ $placedQrisUrl }}"
                                    alt="QRIS Midtrans - {{ $placedOrderCode }}"
                                    class="w-full h-full object-contain select-none"
                                />
                            </div>
                        @else
                            <div class="w-48 sm:w-56 h-48 sm:h-56 mx-auto bg-slate-50 p-4 border-2 border-dashed border-slate-300 rounded-2xl flex flex-col items-center justify-center gap-2 text-slate-500">
                                <span class="text-3xl animate-bounce">📱</span>
                                <span class="text-xs font-bold text-center">Memuat Barcode QRIS...</span>
                            </div>
                        @endif

                        <div class="text-[11px] text-slate-500 font-medium">
                            Didukung oleh BCA Mobile, GoPay, OVO, ShopeePay, DANA & Livin'
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-500 font-bold">Total Pembayaran:</span>
                            <span class="text-base font-black text-brand-blue font-mono">{{ $formattedPlacedOrderTotal }}</span>
                        </div>
                    </div>

                    {{-- Auto Cek Status Card --}}
                    <div class="mb-4 p-3 bg-slate-50 border border-slate-200/90 rounded-2xl flex items-center justify-between gap-3 text-left">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="relative flex items-center justify-center w-8 h-8 rounded-xl {{ $autoCheckPayment ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-500' }} shrink-0">
                                @if ($autoCheckPayment)
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-xl bg-emerald-400 opacity-30"></span>
                                    <x-icon name="refresh" class="w-4 h-4 animate-spin text-emerald-600" style="animation-duration: 3s;" />
                                @else
                                    <x-icon name="alert-circle" class="w-4 h-4 text-slate-500" />
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-bold text-slate-800">Auto Cek Transaksi</span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $autoCheckPayment ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                        {{ $autoCheckPayment ? '● Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 truncate">
                                    {{ $autoCheckPayment ? 'Mengecek otomatis setiap 3 detik' : 'Pemeriksaan otomatis dijeda' }}
                                </p>
                            </div>
                        </div>

                        <button
                            type="button"
                            wire:click="toggleAutoCheck"
                            class="shrink-0 px-3 py-1.5 text-[11px] font-bold rounded-xl border transition cursor-pointer active:scale-95 {{ $autoCheckPayment ? 'bg-white text-slate-700 border-slate-300 hover:bg-slate-100' : 'bg-emerald-600 text-white border-emerald-600 hover:bg-emerald-700' }}"
                        >
                            {{ $autoCheckPayment ? 'Jeda' : 'Aktifkan' }}
                        </button>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="space-y-2 mt-4">
                        {{-- Tombol Cek Transaksi Manual --}}
                        <button
                            type="button"
                            wire:click="checkPaymentStatus(true)"
                            wire:loading.attr="disabled"
                            id="btn-check-transaction"
                            class="w-full h-11 bg-brand-blue hover:bg-[#003da1] text-white rounded-full font-bold text-xs cursor-pointer shadow-md flex items-center justify-center gap-2 transition active:scale-95 disabled:opacity-75 disabled:cursor-wait"
                        >
                            <span wire:loading.remove wire:target="checkPaymentStatus" class="inline-flex items-center gap-2">
                                <x-icon name="refresh" class="w-4 h-4 text-white" />
                                <span>Cek Status Transaksi</span>
                            </span>
                            <span wire:loading.inline-flex wire:target="checkPaymentStatus" class="items-center gap-2" style="display: none;">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Sedang Mengecek Transaksi...</span>
                            </span>
                        </button>

                        <button
                            type="button"
                            wire:click="simulatePaymentSuccess"
                            id="btn-simulate-qris-paid"
                            class="w-full h-11 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-bold text-xs cursor-pointer shadow-sm flex items-center justify-center gap-2 transition active:scale-95"
                        >
                            <x-icon name="check" class="w-4 h-4 text-white" />
                            <span>Simulasikan Pembayaran Berhasil (Instant Backup)</span>
                        </button>

                        <button
                            type="button"
                            wire:click="goToTracking"
                            class="w-full h-10 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-full font-bold text-xs cursor-pointer transition flex items-center justify-center gap-1.5"
                        >
                            <span>Pantau Status Pesanan (Live Tracking)</span>
                            <x-icon name="arrow-right" class="w-3.5 h-3.5 text-slate-600" />
                        </button>

                        @if ($placedSnapToken)
                            <button
                                type="button"
                                wire:click="payWithMidtrans"
                                id="btn-midtrans-snap-pay"
                                class="w-full py-1 text-slate-400 hover:text-brand-blue text-[11px] font-semibold transition cursor-pointer underline"
                            >
                                Opsi: Buka Jendela Snap Pop-up
                            </button>
                        @endif
                    </div>
                @else
                    {{-- CASH TICKET (Scan-to-Pay) State --}}
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 border border-amber-200 rounded-full text-amber-700 text-xs font-bold mb-3">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Menunggu Kasir Scan & Bayar Tunai
                    </div>
                    <h3 class="text-xl font-black text-slate-900">
                        Tunjukkan QR ke Kasir
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Bawa HP ke meja kasir untuk scan tiket ini & bayar tunai agar pesanan langsung diracik.
                    </p>

                    {{-- Cash QR Ticket Card --}}
                    <div class="my-4 p-4 rounded-2xl bg-white border-2 border-amber-400/60 shadow-xs text-center space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-600 border-b border-slate-100 pb-2">
                            <span class="text-amber-700 font-black tracking-wider flex items-center gap-1.5">
                                <x-icon name="cash" class="w-4 h-4 text-amber-600" />
                                TIKET BAYAR KASIR
                            </span>
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-900 rounded-md text-[11px] font-black">
                                Meja {{ $tableNumber ?: '01' }}
                            </span>
                        </div>

                        {{-- QR Code Image for Cash Ticket --}}
                        @if ($placedCashQrUrl)
                            <div class="w-48 sm:w-56 h-48 sm:h-56 mx-auto bg-white p-2 border-2 border-slate-900 rounded-2xl flex items-center justify-center shadow-xs overflow-hidden">
                                <img
                                    src="{{ $placedCashQrUrl }}"
                                    alt="Tiket Bayar Kasir - {{ $placedOrderCode }}"
                                    class="w-full h-full object-contain select-none"
                                />
                            </div>
                        @else
                            <div class="w-48 sm:w-56 h-48 sm:h-56 mx-auto bg-slate-50 p-4 border-2 border-dashed border-slate-300 rounded-2xl flex flex-col items-center justify-center gap-2 text-slate-500">
                                <span class="text-3xl animate-bounce">🎫</span>
                                <span class="text-xs font-bold text-center">Membuat Barcode Tiket...</span>
                            </div>
                        @endif

                        {{-- Compact Code Backup Pill --}}
                        <div class="bg-amber-50/80 border border-amber-200 rounded-xl py-1.5 px-3 flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-bold text-[11px]">Kode Cepat:</span>
                            <span class="font-mono font-black text-amber-800 text-sm tracking-wider">
                                #{{ substr((string) $placedOrderCode, -5) }}
                            </span>
                        </div>

                        <div class="text-[11px] text-slate-500 font-medium">
                            Kasir bisa scan barcode di atas atau memasukkan kode cepat secara manual.
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-500 font-bold">Total Siap Bayar:</span>
                            <span class="text-base font-black text-amber-700 font-mono">{{ $formattedPlacedOrderTotal }}</span>
                        </div>
                    </div>

                    {{-- Auto Cek Status Card for Cash --}}
                    <div class="mb-4 p-3 bg-slate-50 border border-slate-200/90 rounded-2xl flex items-center justify-between gap-3 text-left">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="relative flex items-center justify-center w-8 h-8 rounded-xl {{ $autoCheckPayment ? 'bg-amber-100 text-amber-600' : 'bg-slate-200 text-slate-500' }} shrink-0">
                                @if ($autoCheckPayment)
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-xl bg-amber-400 opacity-30"></span>
                                    <x-icon name="refresh" class="w-4 h-4 animate-spin text-amber-600" style="animation-duration: 3s;" />
                                @else
                                    <x-icon name="alert-circle" class="w-4 h-4 text-slate-500" />
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-bold text-slate-800">Auto Cek Kasir</span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $autoCheckPayment ? 'bg-amber-100 text-amber-800' : 'bg-slate-200 text-slate-600' }}">
                                        {{ $autoCheckPayment ? '● Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 truncate">
                                    {{ $autoCheckPayment ? 'Mengecek konfirmasi kasir otomatis' : 'Pemeriksaan kasir dijeda' }}
                                </p>
                            </div>
                        </div>

                        <button
                            type="button"
                            wire:click="toggleAutoCheck"
                            class="shrink-0 px-3 py-1.5 text-[11px] font-bold rounded-xl border transition cursor-pointer active:scale-95 {{ $autoCheckPayment ? 'bg-white text-slate-700 border-slate-300 hover:bg-slate-100' : 'bg-amber-600 text-white border-amber-600 hover:bg-amber-700' }}"
                        >
                            {{ $autoCheckPayment ? 'Jeda' : 'Aktifkan' }}
                        </button>
                    </div>

                    {{-- Action Buttons for Cash Ticket --}}
                    <div class="space-y-2 mt-4">
                        {{-- Tombol Cek Transaksi Kasir Manual --}}
                        <button
                            type="button"
                            wire:click="checkPaymentStatus(true)"
                            wire:loading.attr="disabled"
                            id="btn-check-cash-status"
                            class="w-full h-11 bg-brand-blue hover:bg-[#003da1] text-white rounded-full font-bold text-xs cursor-pointer shadow-md flex items-center justify-center gap-2 transition active:scale-95 disabled:opacity-75 disabled:cursor-wait"
                        >
                            <span wire:loading.remove wire:target="checkPaymentStatus" class="inline-flex items-center gap-2">
                                <x-icon name="refresh" class="w-4 h-4 text-white" />
                                <span>Cek Status Transaksi</span>
                            </span>
                            <span wire:loading.inline-flex wire:target="checkPaymentStatus" class="items-center gap-2" style="display: none;">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Mengecek Kasir...</span>
                            </span>
                        </button>

                        <button
                            type="button"
                            wire:click="simulatePaymentSuccess"
                            id="btn-simulate-cash-paid"
                            class="w-full h-11 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-bold text-xs cursor-pointer shadow-sm flex items-center justify-center gap-2 transition active:scale-95"
                        >
                            <x-icon name="check" class="w-4 h-4 text-white" />
                            <span>Simulasikan Kasir Menerima Tunai (Instant Backup)</span>
                        </button>

                        <button
                            type="button"
                            wire:click="goToTracking"
                            class="w-full h-10 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-full font-bold text-xs cursor-pointer transition flex items-center justify-center gap-1.5"
                        >
                            <span>Pantau Status Pesanan (Live Tracking)</span>
                            <x-icon name="arrow-right" class="w-3.5 h-3.5 text-slate-600" />
                        </button>
                    </div>
                @endif
            @else
                {{-- Success State (QRIS Paid or Cash Order) --}}
                <div class="w-16 h-16 mx-auto rounded-3xl bg-blue-50 border-2 border-brand-blue/30 flex items-center justify-center mb-3">
                    <x-icon name="star" class="w-9 h-9 text-brand-blue animate-bounce" />
                </div>

                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 border border-emerald-200 rounded-full text-emerald-700 text-xs font-bold mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    {{ ($placedPaymentMethod ?? $paymentMethod) === 'cash' ? 'Pesanan Dicatat (Menunggu Kasir)' : 'Pembayaran QRIS Terverifikasi (Lunas)' }}
                </div>

                <h3 class="text-xl font-black text-slate-900">
                    Pesanan Berhasil Dikirim!
                </h3>

                <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                    Terima kasih, <strong>{{ $customerName ?: 'Teman Nongkrong' }}</strong>! Barista kami sedang menyiapkan pesananmu.
                </p>

                {{-- Order Snapshot Card --}}
                <div class="my-4 p-4 rounded-2xl bg-slate-50 border-2 border-slate-200 text-left space-y-2 text-xs">
                    <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                        <span class="text-slate-400 font-bold">Kode Pesanan</span>
                        <span class="font-mono font-bold text-brand-blue text-sm">
                            {{ $placedOrderCode }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-bold">Tujuan Meja</span>
                        <span class="font-bold text-slate-800">
                            Meja {{ $tableNumber ?: '01' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-bold">Metode Bayar</span>
                        <span class="font-bold {{ ($placedPaymentMethod ?? $paymentMethod) === 'cash' ? 'text-amber-600' : 'text-emerald-600' }}">
                            {{ ($placedPaymentMethod ?? $paymentMethod) === 'cash' ? '💵 Tunai di Kasir' : '💳 QRIS Lunas' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                        <span class="text-slate-500 font-bold">Total Tagihan</span>
                        <span class="font-black text-brand-blue text-sm">
                            {{ $formattedPlacedOrderTotal }}
                        </span>
                    </div>
                </div>

                @if (($placedPaymentMethod ?? $paymentMethod) === 'cash')
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-left text-xs text-amber-800 space-y-1 mb-4">
                        <div class="font-bold flex items-center gap-1.5">
                            <x-icon name="alert-circle" class="w-4 h-4 text-amber-600" />
                            <span>Instruksi Pembayaran Tunai:</span>
                        </div>
                        <p class="text-[11px] text-amber-700 leading-relaxed">
                            Silakan menuju meja kasir dan sebutkan kode pesanan <strong class="font-mono text-slate-900">{{ $placedOrderCode }}</strong> untuk menyelesaikan pembayaran.
                        </p>
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="space-y-2">
                    <button
                        type="button"
                        wire:click="goToTracking"
                        id="btn-go-to-tracking"
                        class="w-full h-11 bg-brand-blue hover:bg-[#003da1] text-white rounded-full font-bold text-xs cursor-pointer shadow-md flex items-center justify-center gap-2 transition"
                    >
                        <span>Pantau Status Pesanan (Live Tracking)</span>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5 text-white" />
                    </button>

                    <button
                        type="button"
                        wire:click="closeOrderSuccess"
                        id="btn-close-order-success"
                        class="w-full h-10 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-full font-bold text-xs cursor-pointer transition"
                    >
                        Pesan Menu Lainnya
                    </button>
                </div>
            @endif
        </div>
    </div>
@endif
