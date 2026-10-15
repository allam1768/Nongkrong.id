{{-- AI Barista Chatbot Overlay Modal --}}
<div x-show="showAiChat" x-cloak
    class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-900/60 backdrop-blur-xs"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

    {{-- Chat Window Card --}}
    <div @click.away="showAiChat = false"
        class="bg-white rounded-t-4xl sm:rounded-3xl w-full max-w-lg h-[86vh] sm:h-150 max-h-175 shadow-2xl border border-slate-100 flex flex-col font-poppins relative overflow-hidden"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-y-full sm:translate-y-4 sm:scale-95"
        x-transition:enter-end="translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0 sm:scale-100"
        x-transition:leave-end="translate-y-full sm:translate-y-4 sm:scale-95">

        {{-- 1. Chat Header --}}
        <div class="px-5 py-3.5 bg-white border-b border-slate-100 flex items-center justify-between z-10 shrink-0">
            <div class="flex items-center gap-3">
                {{-- Barista Mascot / AI Avatar with Pulsing Online Dot --}}
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-brand-blue text-white flex items-center justify-center shadow-md shadow-brand-blue/20">
                        <x-icon name="coffee" class="w-5 h-5 text-white" />
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white"></span>
                </div>

                <div>
                    <div class="flex items-center gap-1.5">
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base leading-tight">AI Barista Assistant</h3>
                        <span class="px-1.5 py-0.5 rounded-full bg-blue-50 text-brand-blue text-[10px] font-bold border border-blue-100/80">AI 2.0</span>
                    </div>
                    <p class="text-[11px] text-emerald-600 font-medium flex items-center gap-1 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Online • Siap rekomendasikan kopi favoritmu
                    </p>
                </div>
            </div>

            {{-- Action Controls --}}
            <div class="flex items-center gap-1">
                {{-- Reset Chat Button --}}
                <button type="button" @click="resetChat()"
                    title="Mulai Ulang Percakapan"
                    class="w-8 h-8 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors flex items-center justify-center cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>

                {{-- Close Button --}}
                <button type="button" @click="showAiChat = false"
                    title="Tutup Obrolan"
                    class="w-8 h-8 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors flex items-center justify-center cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- 2. Chat Messages Stream --}}
        <div x-ref="chatBox" class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-4 bg-slate-50/60 font-sora text-xs">
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex flex-col" :class="msg.sender === 'user' ? 'items-end' : 'items-start'">
                    {{-- Sender Label / Avatar if AI --}}
                    <div class="flex items-end gap-2 max-w-[85%] sm:max-w-[80%]"
                        :class="msg.sender === 'user' ? 'flex-row-reverse' : 'flex-row'">

                        <template x-if="msg.sender === 'ai'">
                            <div class="w-7 h-7 rounded-full bg-brand-blue text-white flex items-center justify-center shrink-0 mb-1 shadow-2xs">
                                <x-icon name="sparkles" class="w-3.5 h-3.5 text-white" />
                            </div>
                        </template>

                        {{-- Message Bubble --}}
                        <div class="rounded-2xl p-3.5 leading-relaxed shadow-xs"
                            :class="msg.sender === 'user'
                                ? 'bg-brand-blue text-white rounded-br-xs'
                                : 'bg-white text-slate-800 border border-slate-200/80 rounded-bl-xs'">
                            <p class="whitespace-pre-line" x-html="formatMessage(msg.text)"></p>

                            {{-- Optional CTA Button inside AI Recommendation --}}
                            <template x-if="msg.showCta">
                                <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center gap-2">
                                    <button type="button"
                                        @click="showAiChat = false; if (window.innerWidth < 768) { $wire.openTableModal() } else { $wire.openTableSelection() }"
                                        class="px-3 py-1.5 bg-brand-blue hover:bg-[#003da1] text-white font-bold text-[11px] rounded-full font-poppins transition-all cursor-pointer flex items-center gap-1.5 shadow-2xs">
                                        <span>Pesan Menu Ini Sekarang</span>
                                        <svg class="w-3.5 h-3.5 -rotate-12" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Timestamp --}}
                    <span class="text-[10px] text-slate-400 mt-1 px-1" x-text="msg.time"></span>
                </div>
            </template>

            {{-- Typing Indicator --}}
            <div x-show="isTyping" class="flex items-center gap-2 text-slate-400 text-xs py-1">
                <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center shrink-0">
                    <x-icon name="sparkles" class="w-3.5 h-3.5 text-slate-500" />
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl px-3.5 py-2.5 flex items-center gap-1.5 shadow-2xs">
                    <span class="w-1.5 h-1.5 bg-brand-blue rounded-full animate-bounce"></span>
                    <span class="w-1.5 h-1.5 bg-brand-blue rounded-full animate-bounce [animation-delay:0.2s]"></span>
                    <span class="w-1.5 h-1.5 bg-brand-blue rounded-full animate-bounce [animation-delay:0.4s]"></span>
                    <span class="text-[11px] text-slate-500 ml-1">Barista AI sedang meracik rekomendasi...</span>
                </div>
            </div>
        </div>

        {{-- 3. Quick Chips Recommendations --}}
        <div class="px-4 py-2 bg-white border-t border-slate-100 flex items-center gap-2 overflow-x-auto no-scrollbar shrink-0 select-none">
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider shrink-0 font-poppins">Pilihan Cepat:</span>
            <template x-for="(prompt, pIndex) in quickPrompts" :key="pIndex">
                <button type="button" @click="sendUserMessage(prompt)"
                    class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-blue-50 hover:text-brand-blue hover:border-blue-200 border border-transparent text-slate-700 text-[11px] font-medium whitespace-nowrap transition-all cursor-pointer shrink-0 font-poppins">
                    <span x-text="prompt"></span>
                </button>
            </template>
        </div>

        {{-- 4. Chat Input Form --}}
        <div class="p-3.5 sm:p-4 bg-white border-t border-slate-100 z-10 shrink-0">
            <form @submit.prevent="sendUserMessage(inputMessage)" class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input type="text" x-model="inputMessage"
                        placeholder="Tanya rekomendasi kopi favoritmu..."
                        class="w-full bg-slate-100 focus:bg-white text-slate-900 placeholder:text-slate-400 text-xs sm:text-sm rounded-full pl-4 pr-10 py-2.5 sm:py-3 border border-transparent focus:border-brand-blue focus:outline-none transition-all font-sora" />
                </div>

                {{-- Send Button --}}
                <button type="submit"
                    :disabled="!inputMessage.trim() || isTyping"
                    :class="inputMessage.trim() && !isTyping ? 'bg-brand-blue hover:bg-[#003da1] text-white shadow-md shadow-brand-blue/30 cursor-pointer active:scale-95' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                    class="w-10 h-10 sm:w-11 sm:h-11 rounded-full flex items-center justify-center transition-all shrink-0">
                    <svg class="w-4 h-4 -rotate-12 translate-x-0.5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
