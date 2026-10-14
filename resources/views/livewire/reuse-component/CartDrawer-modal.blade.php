{{-- 4. MOBILE SLIDE-UP CART BOTTOM SHEET --}}
@if ($showCartDrawer)
    <div
        x-data
        class="lg:hidden fixed inset-0 z-50 flex items-end justify-center p-0"
        role="dialog"
        aria-modal="true"
    >
        <div
            wire:click="closeCart"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity animate-backdrop-in"
        ></div>

        <div
            class="relative w-full max-w-lg bg-white rounded-t-[28px] rounded-b-none shadow-2xl border-t border-slate-100 z-10 overflow-hidden flex flex-col max-h-[85vh] animate-sheet-up"
            @click.stop
        >
            {{-- Mobile Drag Handle Pill --}}
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mt-3 -mb-1 shrink-0"></div>

            {{-- Cart Header --}}
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🛒</span>
                    <h3 class="font-bold text-base text-slate-900">
                        Pesanan Meja {{ $tableNumber ?: '03' }}
                    </h3>
                    <span class="text-xs font-bold text-brand-blue bg-[#DBEAFE] px-2 py-0.5 rounded-md">
                        {{ $cartCount }} Item
                    </span>
                </div>
                <button
                    type="button"
                    wire:click="closeCart"
                    class="text-slate-400 hover:text-slate-600 p-2 rounded-full hover:bg-slate-100 transition-colors cursor-pointer"
                >
                    ✕
                </button>
            </div>

            {{-- Cart Items List (Scrollable) --}}
            <div class="p-5 flex-1 overflow-y-auto space-y-3.5">
                @forelse ($cart as $key => $item)
                    <div class="p-3 rounded-xl bg-[#F8FAFC] border border-slate-200 flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                                {{ $item['name'] }}
                            </div>
                            @if (!empty($item['options_summary']))
                                <div class="text-[11px] text-[#64748B] italic mt-0.5">
                                    Catatan: {{ $item['options_summary'] }}
                                </div>
                            @endif
                            <div class="text-xs font-extrabold text-brand-blue mt-1">
                                {{ $item['formatted_subtotal'] }}
                            </div>
                        </div>
                        <div class="flex items-center gap-2 bg-white border border-[#CBD5E1] rounded-lg px-2 py-1">
                            <button type="button" wire:click="decreaseItem('{{ $key }}')" class="text-brand-blue font-bold text-xs cursor-pointer">-</button>
                            <span class="text-xs font-bold text-slate-800">{{ $item['quantity'] }}</span>
                            <button type="button" wire:click="increaseItem('{{ $key }}')" class="text-brand-blue font-bold text-xs cursor-pointer">+</button>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center text-slate-400">
                        <div class="text-4xl mb-2">🛒</div>
                        <p class="text-xs font-bold text-slate-600">Keranjang Masih Kosong</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Pilih menu favorit Anda dengan menekan tombol (+)</p>
                    </div>
                @endforelse
            </div>

            {{-- Cart Footer Summary --}}
            <div class="p-5 border-t border-[#E2E8F0] bg-[#F8FAFC]">
                {{-- Payment Method Selection (1: Cash, 2: QRIS) --}}
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-bold text-slate-700">Pilihan Pembayaran</label>
                        <span class="text-[11px] text-slate-400 font-medium">Pilih salah satu</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        {{-- 1. Cash --}}
                        <button
                            type="button"
                            wire:click="setPaymentMethod('cash')"
                            class="flex items-center gap-2.5 p-2.5 rounded-xl border text-left transition-all cursor-pointer {{ $paymentMethod === 'cash' ? 'border-brand-blue bg-blue-50/80 text-brand-blue ring-1 ring-brand-blue' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50' }}"
                        >
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $paymentMethod === 'cash' ? 'bg-brand-blue/10 text-brand-blue' : 'bg-slate-100 text-slate-500' }}">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="12" x="2" y="6" rx="2"/>
                                    <circle cx="12" cy="12" r="2"/>
                                    <path d="M6 12h.01M18 12h.01"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-bold leading-tight">Cash</div>
                                <div class="text-[10px] {{ $paymentMethod === 'cash' ? 'text-brand-blue/80' : 'text-slate-400' }} leading-tight truncate">Bayar di Kasir</div>
                            </div>
                            <div class="w-3.5 h-3.5 rounded-full border flex items-center justify-center shrink-0 {{ $paymentMethod === 'cash' ? 'border-brand-blue' : 'border-slate-300' }}">
                                @if ($paymentMethod === 'cash')
                                    <div class="w-1.5 h-1.5 rounded-full bg-brand-blue"></div>
                                @endif
                            </div>
                        </button>

                        {{-- 2. QRIS --}}
                        <button
                            type="button"
                            wire:click="setPaymentMethod('qris')"
                            class="flex items-center gap-2.5 p-2.5 rounded-xl border text-left transition-all cursor-pointer {{ $paymentMethod === 'qris' ? 'border-brand-blue bg-blue-50/80 text-brand-blue ring-1 ring-brand-blue' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50' }}"
                        >
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $paymentMethod === 'qris' ? 'bg-brand-blue/10 text-brand-blue' : 'bg-slate-100 text-slate-500' }}">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="5" height="5" x="3" y="3" rx="1"/>
                                    <rect width="5" height="5" x="16" y="3" rx="1"/>
                                    <rect width="5" height="5" x="3" y="16" rx="1"/>
                                    <path d="M21 16h-3a2 2 0 0 0-2 2v3"/>
                                    <path d="M21 21v.01"/>
                                    <path d="M12 7v3a2 2 0 0 1-2 2H7"/>
                                    <path d="M3 12h.01"/>
                                    <path d="M12 3h.01"/>
                                    <path d="M12 16v.01"/>
                                    <path d="M16 12h1"/>
                                    <path d="M21 12v.01"/>
                                    <path d="M12 21v-1"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-bold leading-tight">QRIS</div>
                                <div class="text-[10px] {{ $paymentMethod === 'qris' ? 'text-brand-blue/80' : 'text-slate-400' }} leading-tight truncate">Scan E-Wallet</div>
                            </div>
                            <div class="w-3.5 h-3.5 rounded-full border flex items-center justify-center shrink-0 {{ $paymentMethod === 'qris' ? 'border-brand-blue' : 'border-slate-300' }}">
                                @if ($paymentMethod === 'qris')
                                    <div class="w-1.5 h-1.5 rounded-full bg-brand-blue"></div>
                                @endif
                            </div>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between text-xs text-[#64748B] mb-1.5 font-medium">
                    <span>Subtotal</span>
                    <span>{{ $formattedCartSubtotal }}</span>
                </div>
                <div class="flex justify-between text-xs text-[#64748B] mb-1.5 font-medium">
                    <span>PB1 (Pajak Resto 10%)</span>
                    <span>{{ $formattedCartTax }}</span>
                </div>
                <div class="flex justify-between text-sm font-black text-slate-900 pt-2 border-t border-dashed border-[#CBD5E1]">
                    <span>Total Tagihan</span>
                    <span class="text-brand-blue">{{ $formattedCartTotal }}</span>
                </div>
                <button
                    type="button"
                    wire:click="checkoutOrder"
                    @disabled(empty($cart))
                    class="w-full h-11 bg-brand-blue hover:bg-[#003da1] disabled:opacity-40 text-white rounded-full text-xs font-extrabold flex items-center justify-center gap-2 mt-4 cursor-pointer transition-all shadow-md active:scale-98 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="checkoutOrder">Lanjut ke Checkout ➔</span>
                    <span wire:loading wire:target="checkoutOrder" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin text-white" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Memproses Pesanan...</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
@endif
