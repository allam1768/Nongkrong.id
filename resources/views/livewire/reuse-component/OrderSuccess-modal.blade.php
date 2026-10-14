{{-- 7. ORDER SUCCESS & PAYMENT SIMULATOR MODAL --}}
@if ($showOrderSuccessModal)
    <div
        x-data
        wire:poll.4s="checkPaymentStatus"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
        role="dialog"
        aria-modal="true"
    >
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

                    {{-- Action Buttons --}}
                    <div class="space-y-2 mt-4">
                        <button
                            type="button"
                            wire:click="simulatePaymentSuccess"
                            id="btn-simulate-qris-paid"
                            class="w-full h-11 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-bold text-xs cursor-pointer shadow-sm flex items-center justify-center gap-2 transition active:scale-95"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Simulasikan Pembayaran Berhasil (Instant Backup)
                        </button>

                        <button
                            type="button"
                            wire:click="goToTracking"
                            class="w-full h-10 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-full font-bold text-xs cursor-pointer transition"
                        >
                            Pantau Status Pesanan (Live Tracking) ➔
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
                                <span>🎫</span> TIKET BAYAR KASIR
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

                    {{-- Action Buttons for Cash Ticket --}}
                    <div class="space-y-2 mt-4">
                        <button
                            type="button"
                            wire:click="simulatePaymentSuccess"
                            id="btn-simulate-cash-paid"
                            class="w-full h-11 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-bold text-xs cursor-pointer shadow-sm flex items-center justify-center gap-2 transition active:scale-95"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Simulasikan Kasir Menerima Tunai (Instant Backup)
                        </button>

                        <button
                            type="button"
                            wire:click="goToTracking"
                            class="w-full h-10 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-full font-bold text-xs cursor-pointer transition"
                        >
                            Pantau Status Pesanan (Live Tracking) ➔
                        </button>
                    </div>
                @endif
            @else
                {{-- Success State (QRIS Paid or Cash Order) --}}
                <div class="w-16 h-16 mx-auto rounded-3xl bg-blue-50 border-2 border-brand-blue/30 flex items-center justify-center mb-3">
                    <img
                        src="{{ asset('assets/img/Star-ic.svg') }}"
                        alt=""
                        class="w-9 h-9 animate-bounce"
                    />
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
                            <span>📌</span> Instruksi Pembayaran Tunai:
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
                        Pantau Status Pesanan (Live Tracking) ➔
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
