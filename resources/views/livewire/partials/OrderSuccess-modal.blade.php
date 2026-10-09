{{-- 7. ORDER SUCCESS MODAL --}}
@if ($showOrderSuccessModal)
    <div
        x-data
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
        role="dialog"
        aria-modal="true"
    >
        <div
            wire:click="closeOrderSuccess"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
        ></div>

        <div
            class="relative w-full max-w-sm sm:max-w-md bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-100 z-10 text-center"
            @click.stop
        >
            <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-3xl bg-blue-50 border-2 border-[#004AAD]/20 flex items-center justify-center mb-4 relative">
                <img
                    src="{{ asset('assets/img/Star-ic.svg') }}"
                    alt=""
                    class="w-10 h-10 sm:w-12 sm:h-12 animate-bounce"
                />
            </div>

            <h3 class="text-xl sm:text-2xl font-bold text-slate-900">
                Pesanan Berhasil Dikirim!
            </h3>

            <p class="mt-1.5 text-xs sm:text-sm text-slate-500 leading-relaxed">
                Terima kasih, <strong>{{ $customerName ?: 'Teman Nongkrong' }}</strong>! Barista kami sedang meracik pesanan Anda.
            </p>

            <div class="my-5 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-left space-y-2 text-xs">
                <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                    <span class="text-slate-400">Kode Pesanan</span>
                    <span class="font-mono font-bold text-[#004AAD] text-sm">
                        {{ $placedOrderCode }}
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Tujuan Meja</span>
                    <span class="font-bold text-slate-800">
                        Meja {{ $tableNumber ?: '01' }}
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Estimasi Racik</span>
                    <span class="font-bold text-emerald-600">
                        ~ 10 - 15 Menit
                    </span>
                </div>

                <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                    <span class="text-slate-500 font-semibold">Total Tagihan</span>
                    <span class="font-black text-[#004AAD] text-sm">
                        {{ $formattedPlacedOrderTotal }}
                    </span>
                </div>
            </div>

            <button
                type="button"
                wire:click="closeOrderSuccess"
                id="btn-close-order-success"
                class="w-full h-11 bg-[#004AAD] hover:bg-[#003da1] text-white rounded-full font-bold text-xs cursor-pointer shadow-md"
            >
                Pesan Menu Lainnya
            </button>
        </div>
    </div>
@endif
