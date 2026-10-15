{{-- 6. MENU CUSTOMIZATION DETAIL MODAL --}}
@if ($showDetailModal)
    <div
        x-data
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
        role="dialog"
        aria-modal="true"
    >
        <div
            wire:click="closeDetail"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
        ></div>

        <div
            class="relative w-full max-w-lg bg-white rounded-t-3xl sm:rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-100 z-10 max-h-[92vh] overflow-y-auto"
            @click.stop
        >
            {{-- Mobile Drag Handle --}}
            <div class="sm:hidden w-12 h-1.5 bg-slate-200 rounded-full mx-auto -mt-2 mb-4 shrink-0"></div>
            <button
                type="button"
                wire:click="closeDetail"
                class="absolute top-4 right-4 sm:top-5 sm:right-5 text-slate-400 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-100 transition-colors cursor-pointer"
            >
                <x-icon name="close" class="w-4 h-4" />
            </button>

            <div class="mb-5">
                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-brand-blue text-[11px] font-bold uppercase tracking-wider">
                    {{ $detailMenuCategory ?? 'Nongkrong.id' }}
                </span>
                <h3 class="mt-2 text-xl sm:text-2xl font-bold text-slate-900">
                    {{ $detailMenuName }}
                </h3>
                <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                    {{ $detailMenuDesc }}
                </p>
                <div class="mt-3 text-xl font-extrabold text-brand-blue">
                    {{ $formattedDetailMenuPrice }}
                </div>
            </div>

            <div class="space-y-4 pt-3 border-t border-slate-100">
                {{-- Suhu --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Penyajian Suhu:</label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <button
                            type="button"
                            wire:click="$set('detailTemperature', 'cold')"
                            @class([
                                'py-2.5 px-3 rounded-xl text-xs font-semibold border transition-all flex items-center justify-center gap-2 cursor-pointer',
                                'bg-blue-50 border-brand-blue text-brand-blue' => $detailTemperature === 'cold',
                                'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $detailTemperature !== 'cold',
                            ])
                        >
                            <span>🧊 Dingin (Iced)</span>
                        </button>
                        <button
                            type="button"
                            wire:click="$set('detailTemperature', 'hot')"
                            @class([
                                'py-2.5 px-3 rounded-xl text-xs font-semibold border transition-all flex items-center justify-center gap-2 cursor-pointer',
                                'bg-blue-50 border-brand-blue text-brand-blue' => $detailTemperature === 'hot',
                                'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $detailTemperature !== 'hot',
                            ])
                        >
                            <span>♨️ Panas (Hot)</span>
                        </button>
                    </div>
                </div>

                {{-- Tingkat Gula --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Tingkat Manis / Gula:</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button
                            type="button"
                            wire:click="$set('detailSugar', 'normal')"
                            @class([
                                'py-2 px-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer',
                                'bg-blue-50 border-brand-blue text-brand-blue' => $detailSugar === 'normal',
                                'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $detailSugar !== 'normal',
                            ])
                        >
                            Normal
                        </button>
                        <button
                            type="button"
                            wire:click="$set('detailSugar', 'less')"
                            @class([
                                'py-2 px-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer',
                                'bg-blue-50 border-brand-blue text-brand-blue' => $detailSugar === 'less',
                                'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $detailSugar !== 'less',
                            ])
                        >
                            Less Sugar
                        </button>
                        <button
                            type="button"
                            wire:click="$set('detailSugar', 'zero')"
                            @class([
                                'py-2 px-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer',
                                'bg-blue-50 border-brand-blue text-brand-blue' => $detailSugar === 'zero',
                                'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' => $detailSugar !== 'zero',
                            ])
                        >
                            No Sugar
                        </button>
                    </div>
                </div>

                {{-- Catatan --}}
                <div>
                    <label for="input-detail-notes" class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Tambahan:</label>
                    <input
                        type="text"
                        wire:model="detailNotes"
                        id="input-detail-notes"
                        placeholder="Contoh: Less sugar 50%, dihangatkan, dll..."
                        class="w-full h-11 px-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-brand-blue focus:outline-none"
                    />
                </div>

                {{-- Kuantitas --}}
                <div class="flex items-center justify-between pt-2">
                    <span class="text-xs font-bold text-slate-700">Jumlah:</span>
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            wire:click="decrementDetailQty"
                            class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold flex items-center justify-center cursor-pointer"
                        >
                            -
                        </button>
                        <span class="font-bold text-base w-6 text-center text-slate-900">
                            {{ $detailQuantity }}
                        </span>
                        <button
                            type="button"
                            wire:click="incrementDetailQty"
                            class="w-9 h-9 rounded-xl bg-brand-blue text-white font-bold flex items-center justify-center cursor-pointer"
                        >
                            +
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <button
                    type="button"
                    wire:click="addCustomizedToCart"
                    id="btn-add-to-cart"
                    class="w-full h-12 bg-brand-blue hover:bg-[#003da1] text-white rounded-full text-sm font-extrabold flex items-center justify-center gap-2 shadow-md cursor-pointer"
                >
                    <span>Tambahkan ke Pesanan</span>
                    <span>• {{ $formattedDetailSubtotal }}</span>
                </button>
            </div>
        </div>
    </div>
@endif
