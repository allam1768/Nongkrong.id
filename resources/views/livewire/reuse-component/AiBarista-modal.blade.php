{{-- 5. BARISTA AI VIRTUAL CONSULTATION MODAL (04_web_ai_barista.html) --}}
@if ($showAiModal)
    <div
        x-data
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-6"
        role="dialog"
        aria-modal="true"
    >
        <div
            wire:click="closeAiModal"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity animate-backdrop-in"
        ></div>

        <div
            class="relative w-full max-w-2xl bg-white rounded-t-[28px] sm:rounded-3xl rounded-b-none sm:rounded-b-3xl shadow-2xl border-t sm:border border-slate-100 z-10 overflow-hidden flex flex-col max-h-[85vh] sm:max-h-[90vh] animate-sheet-up sm:animate-modal-pop"
            @click.stop
        >
            {{-- Mobile Drag Handle Pill --}}
            <div class="sm:hidden w-12 h-1.5 bg-slate-200 rounded-full mx-auto mt-3 -mb-1 shrink-0"></div>

            {{-- AI Modal Header --}}
            <div class="p-5 px-6 border-b border-[#E2E8F0] bg-[#F8FAFC] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-brand-blue text-white flex items-center justify-center text-xl shadow-sm">
                        ☕
                    </div>
                    <div>
                        <div class="text-xs font-bold text-[#15803D] flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#22C55E] animate-pulse"></span>
                            Coffee Assistant Connected
                        </div>
                        <h3 class="text-base font-black text-brand-blue">
                            Konsultasi Barista AI Virtual
                        </h3>
                    </div>
                </div>

                <button
                    type="button"
                    wire:click="closeAiModal"
                    class="text-slate-400 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-200 transition-colors cursor-pointer"
                >
                    ✕
                </button>
            </div>

            {{-- AI Modal Body --}}
            <div class="p-6 overflow-y-auto space-y-4 flex-1">
                {{-- Quick Mood Chips --}}
                <div>
                    <span class="block text-xs font-bold text-slate-500 mb-2">Pilih Mood atau Karakter Rasa:</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($moodChips as $key => $label)
                            <button
                                type="button"
                                wire:click="askAiBarista('{{ $key }}')"
                                @class([
                                    'px-3.5 py-1.5 rounded-full text-xs font-bold cursor-pointer transition-all',
                                    'bg-brand-blue text-white' => $aiMoodInput === $key,
                                    'bg-[#F8FAFC] text-slate-700 border border-[#CBD5E1] hover:bg-slate-100' => $aiMoodInput !== $key,
                                ])
                            >
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- AI Message Bubble --}}
                @if ($aiRecommendationResponse)
                    <div class="flex items-start gap-3 bg-[#F8FAFC] border-[1.5px] border-[#CBD5E1] p-4 rounded-2xl">
                        <span class="text-2xl">🤖</span>
                        <div class="text-xs sm:text-sm text-[#0F172A] leading-relaxed">
                            {{ $aiRecommendationResponse }}
                        </div>
                    </div>
                @endif

                {{-- Recommendations Grid --}}
                @if (!empty($aiRecommendedMenus))
                    <div>
                        <span class="block text-xs font-bold text-slate-500 mb-2.5">Menu Rekomendasi Barista:</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($aiRecommendedMenus as $rec)
                                <div class="p-3.5 rounded-xl border-[1.5px] border-[#BFDBFE] bg-[#EFF6FF]/40 flex flex-col justify-between gap-2">
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-[10px] font-extrabold text-brand-blue bg-[#DBEAFE] px-2 py-0.5 rounded-sm">
                                                {{ $rec['flavor'] }}
                                            </span>
                                            <span class="text-xl">{{ $rec['visual'] }}</span>
                                        </div>
                                        <h5 class="text-xs sm:text-sm font-extrabold text-[#0F172A]">{{ $rec['name'] }}</h5>
                                        <p class="text-[11px] text-[#64748B] line-clamp-2 mt-0.5">{{ $rec['description'] }}</p>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 border-t border-blue-100">
                                        <span class="text-xs font-black text-brand-blue">{{ $rec['formatted_price'] }}</span>
                                        <button
                                            type="button"
                                            wire:click="quickAddToCart({{ $rec['id'] }})"
                                            class="px-3 py-1 rounded-full bg-brand-blue hover:bg-[#003da1] text-white text-[11px] font-bold cursor-pointer transition-all"
                                        >
                                            + Tambah
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- AI Modal Footer Chat Input --}}
            <div class="p-4 px-6 border-t border-[#E2E8F0] bg-white flex items-center gap-3">
                <input
                    type="text"
                    wire:model="aiMoodInput"
                    wire:keydown.enter="askAiBarista"
                    placeholder="Ketik apa yang kamu inginkan (misal: kopi dingin manis, cemilan renyah)..."
                    class="flex-1 h-11 bg-[#F8FAFC] border-[1.5px] border-[#CBD5E1] rounded-full px-4 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-brand-blue"
                />
                <button
                    type="button"
                    wire:click="askAiBarista"
                    class="h-11 px-5 rounded-full bg-brand-blue hover:bg-[#003da1] text-white font-bold text-xs cursor-pointer shadow-sm"
                >
                    Tanya
                </button>
            </div>
        </div>
    </div>
@endif
