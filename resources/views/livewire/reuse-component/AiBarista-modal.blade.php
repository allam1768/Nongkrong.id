{{-- AI Barista / Concierge Greeter Chatbot Overlay Modal --}}
<div
    x-data="aiBaristaModal()"
    x-show="showAiChat"
    x-cloak
    @open-ai-barista.window="openModal($event.detail)"
    @close-ai-barista.window="showAiChat = false"
    @keydown.escape.window="showAiChat = false"
    class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="ai-barista-modal-title"
>
    {{-- Backdrop Overlay --}}
    <div
        x-show="showAiChat"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="showAiChat = false"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
    ></div>

    {{-- Chat Window Card (Safe mobile bottom sheet + desktop centered card) --}}
    <div
        x-show="showAiChat"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-y-full sm:translate-y-4 sm:scale-95"
        x-transition:enter-end="translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0 sm:scale-100"
        x-transition:leave-end="translate-y-full sm:translate-y-4 sm:scale-95"
        @click.stop
        class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-lg h-[82dvh] sm:h-150 max-h-[85dvh] sm:max-h-175 shadow-2xl border border-slate-100 flex flex-col font-poppins relative overflow-hidden z-10"
    >
        {{-- 1. Chat Header --}}
        <div class="px-5 py-3.5 bg-white border-b border-slate-100 flex items-center justify-between z-10 shrink-0">
            <div class="flex items-center gap-3">
                {{-- Barista Mascot / AI Avatar with Pulsing Online Dot --}}
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-brand-blue text-white flex items-center justify-center shadow-md shadow-brand-blue/20">
                        <x-icon name="icont_noks" class="w-6 h-6 text-white" />
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white"></span>
                </div>

                <div>
                    <div class="flex items-center gap-1.5">
                        <h3 id="ai-barista-modal-title" class="font-bold text-slate-900 text-sm sm:text-base leading-tight" x-text="headerTitle">AI Panduan Nongkrong</h3>
                        <span class="px-1.5 py-0.5 rounded-full bg-blue-50 text-brand-blue text-[10px] font-bold border border-blue-100/80">Panduan 24/7</span>
                    </div>
                    <p class="text-[11px] text-emerald-600 font-medium flex items-center gap-1 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span x-text="headerSubtitle">Siap bantu jawab kebingunganmu</span>
                    </p>
                </div>
            </div>

            {{-- Action Controls --}}
            <div class="flex items-center gap-1">
                {{-- Reset Chat Button --}}
                <button
                    type="button"
                    @click="resetChat()"
                    title="Mulai Ulang Percakapan"
                    class="w-8 h-8 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors flex items-center justify-center cursor-pointer"
                >
                    <x-icon name="refresh" class="w-4 h-4 text-slate-500" />
                </button>

                {{-- Close Button --}}
                <button
                    type="button"
                    @click="showAiChat = false"
                    title="Tutup Obrolan"
                    class="w-8 h-8 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors flex items-center justify-center cursor-pointer"
                >
                    <x-icon name="close" class="w-4 h-4 text-slate-500" />
                </button>
            </div>
        </div>

        {{-- 2. Chat Messages Stream --}}
        <div x-ref="chatBox" class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-4 bg-slate-50/60 font-sora text-xs">
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex flex-col" :class="msg.sender === 'user' ? 'items-end' : 'items-start'">
                    {{-- Sender Label / Avatar if AI --}}
                    <div
                        class="flex items-end gap-2 max-w-[85%] sm:max-w-[80%]"
                        :class="msg.sender === 'user' ? 'flex-row-reverse' : 'flex-row'"
                    >
                        <template x-if="msg.sender === 'ai'">
                            <div class="w-7 h-7 rounded-full bg-brand-blue text-white flex items-center justify-center shrink-0 mb-1 shadow-2xs">
                                <x-icon name="sparkles" class="w-3.5 h-3.5 text-white" />
                            </div>
                        </template>

                        {{-- Message Bubble --}}
                        <div
                            class="rounded-2xl p-3.5 leading-relaxed shadow-xs"
                            :class="msg.sender === 'user'
                                ? 'bg-brand-blue text-white rounded-br-xs'
                                : 'bg-white text-slate-800 border border-slate-200/80 rounded-bl-xs'"
                        >
                            <p class="whitespace-pre-line" x-html="formatMessage(msg.text)"></p>

                            {{-- Optional CTA Button inside AI Recommendation --}}
                            <template x-if="msg.showCta">
                                <div class="mt-2.5 pt-2 border-t border-slate-100 flex flex-wrap items-center gap-2">
                                    <button
                                        type="button"
                                        @click="handleAction(msg.actionType || 'order', msg.actionPayload || msg.recommendedMenu, msg.menuId)"
                                        class="px-3.5 py-1.5 bg-brand-blue hover:bg-[#003da1] text-white font-bold text-[11px] rounded-full font-poppins transition-all cursor-pointer flex items-center gap-1.5 shadow-2xs active:scale-95"
                                    >
                                        <span x-text="msg.ctaText || 'Pesan Menu Ini Sekarang'">Pesan Menu Ini Sekarang</span>
                                        <x-icon name="arrow-right" class="w-3.5 h-3.5 text-white" />
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
                    <span class="text-[11px] text-slate-500 ml-1 font-medium">Barista AI sedang meracik rekomendasi...</span>
                </div>
            </div>
        </div>

        {{-- 3. Quick Chips Recommendations --}}
        <div class="px-4 py-2 bg-white border-t border-slate-100 flex items-center gap-2 overflow-x-auto no-scrollbar shrink-0 select-none">
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider shrink-0 font-poppins">Pilihan Cepat:</span>
            <template x-for="(prompt, pIndex) in quickPrompts" :key="pIndex">
                <button
                    type="button"
                    @click="sendUserMessage(prompt)"
                    class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-blue-50 hover:text-brand-blue hover:border-blue-200 border border-transparent text-slate-700 text-[11px] font-medium whitespace-nowrap transition-all cursor-pointer shrink-0 font-poppins active:scale-95"
                >
                    <span x-text="prompt"></span>
                </button>
            </template>
        </div>

        {{-- 4. Chat Input Form --}}
        <div class="p-3.5 sm:p-4 bg-white border-t border-slate-100 z-10 shrink-0">
            <form @submit.prevent="sendUserMessage(inputMessage)" class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input
                        type="text"
                        x-model="inputMessage"
                        :placeholder="inputPlaceholder"
                        class="w-full bg-slate-100 focus:bg-white text-slate-900 placeholder:text-slate-400 text-xs sm:text-sm rounded-full pl-4 pr-4 py-2.5 sm:py-3 border border-transparent focus:border-brand-blue focus:outline-none transition-all font-sora"
                    />
                </div>

                {{-- Send Button --}}
                <button
                    type="submit"
                    :disabled="!inputMessage.trim() || isTyping"
                    :class="inputMessage.trim() && !isTyping ? 'bg-brand-blue hover:bg-[#003da1] text-white shadow-md shadow-brand-blue/30 cursor-pointer active:scale-95' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                    class="w-10 h-10 sm:w-11 sm:h-11 rounded-full flex items-center justify-center transition-all shrink-0 group focus:outline-none focus:ring-2 focus:ring-brand-blue/40"
                    title="Kirim Pesan"
                    aria-label="Kirim Pesan"
                >
                    <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 transition-transform duration-150 group-hover:scale-110 -rotate-12 translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m22 2-7 20-4-9-9-4Z"/>
                        <path d="M22 2 11 13"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Encapsulated Component State Script --}}
<script>
    function aiBaristaModal() {
        return {
            showAiChat: false,
            context: 'portal',
            inputMessage: '',
            isTyping: false,
            headerTitle: 'AI Panduan Kafe',
            headerSubtitle: 'Siap bantu arahkan langkahmu',
            inputPlaceholder: 'Tanya apa saja kalau bingung...',
            messages: [],
            quickPrompts: [],
            openModal(detail) {
                let prevCtx = this.context;
                this.context = (detail && detail.context) ? detail.context : (window.location.pathname.includes('/order') ? 'order' : 'portal');
                this.showAiChat = true;

                if (this.messages.length === 0 || prevCtx !== this.context) {
                    this.setupContextMessages();
                }
                this.scrollToBottom();
            },
            setupContextMessages() {
                if (this.context === 'portal') {
                    this.headerTitle = 'AI Panduan Nongkrong';
                    this.headerSubtitle = 'Standby layaknya staf ramah di pintu kafe';
                    this.inputPlaceholder = 'Ada yang dibingungkan? Tanya di sini...';
                    this.quickPrompts = [
                        'Saya bingung mau mulai dari mana',
                        'Gimana cara pesan kopi ke meja?',
                        'Saya staf / karyawan mau login',
                        'Mau lihat daftar menu dan harga',
                        'Bisa bayar pakai QRIS / Cash?'
                    ];
                    this.messages = [
                        {
                            sender: 'ai',
                            text: 'Halo! Selamat datang di **NONGKRONG.id**.\n\nAda yang lagi dibingungkan kak? Jangan sungkan, saya siap bantu arahkan langkahmu di kafe ini:\n\n• Mau pesan makanan/minuman langsung ke meja?\n• Bingung cara scan QR meja?\n• Atau mau login sebagai staf/karyawan?\n\nKetik langsung pertanyaanmu atau pilih tombol cepat di bawah ya!',
                            time: 'Baru saja',
                            showCta: false
                        }
                    ];
                } else {
                    this.headerTitle = 'AI Barista Assistant';
                    this.headerSubtitle = 'Online • Siap rekomendasikan menu favoritmu';
                    this.inputPlaceholder = 'Tanya rekomendasi rasa / menu...';
                    this.quickPrompts = [
                        'Kopi Manis & Creamy',
                        'Kopi Strong / Melek',
                        'Minuman Segar Non-Kopi',
                        'Rekomendasi Cemilan',
                        'Cara Pesan ke Meja'
                    ];
                    this.messages = [
                        {
                            sender: 'ai',
                            text: 'Halo! Saya **AI Barista Nongkrong.id**\nBingung mau pesan apa hari ini? Ceritakan seleramu (misal: kopi manis creamy, strong melek, atau non-kopi segar), atau pilih opsi cepat di bawah!',
                            time: 'Baru saja',
                            showCta: false,
                            recommendedMenu: ''
                        }
                    ];
                }
            },
            getWire() {
                try {
                    if (this.$wire) return this.$wire;
                } catch (e) {}
                if (window.Livewire) {
                    let comps = window.Livewire.all();
                    return comps.length > 0 ? comps[0] : null;
                }
                return null;
            },
            scrollToBottom() {
                this.$nextTick(() => {
                    setTimeout(() => {
                        let box = this.$refs.chatBox;
                        if (box) box.scrollTop = box.scrollHeight;
                    }, 50);
                });
            },
            async sendUserMessage(text) {
                let msg = (text || this.inputMessage || '').trim();
                if (!msg || this.isTyping) return;

                let timeStr = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                this.messages.push({
                    sender: 'user',
                    text: msg,
                    time: timeStr
                });
                this.inputMessage = '';
                this.isTyping = true;
                this.scrollToBottom();

                try {
                    let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    let wire = this.getWire();
                    let tableNo = wire && wire.tableNumber ? wire.tableNumber : '';
                    let response = await fetch('/api/ai/barista-chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            message: msg,
                            context: this.context,
                            table_number: tableNo
                        })
                    });

                    let res = await response.json();
                    this.isTyping = false;
                    this.messages.push({
                        sender: 'ai',
                        text: res.text || 'Maaf kak, barista sedang sibuk. Bisa ulangi pertanyaannya?',
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                        showCta: res.showCta ?? true,
                        ctaText: res.ctaText || 'Pesan Menu Ini',
                        actionType: res.actionType || 'order',
                        actionPayload: res.actionPayload || res.recommendedMenu || '',
                        menuId: res.menuId || null
                    });
                } catch (err) {
                    this.isTyping = false;
                    this.messages.push({
                        sender: 'ai',
                        text: 'Maaf kak, koneksi AI Barista sedang terganggu sejenak. Kamu bisa langsung memilih menu favorit di katalog bawah ya!',
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                        showCta: true,
                        ctaText: 'Lihat Katalog Menu',
                        actionType: 'order',
                        actionPayload: '',
                        menuId: null
                    });
                }
                this.scrollToBottom();
            },
            handleAction(actionType, payload, menuId) {
                this.showAiChat = false;
                let wire = this.getWire();

                if (actionType === 'open-login') {
                    if (wire && typeof wire.openLogin === 'function') {
                        wire.openLogin();
                    }
                    return;
                }

                if (actionType === 'open-table') {
                    if (wire) {
                        if (typeof wire.openTableModal === 'function') {
                            wire.openTableModal();
                        } else if (typeof wire.openTableSelection === 'function') {
                            wire.openTableSelection();
                        }
                    }
                    return;
                }

                // Order action: directly open customization detail modal!
                if (menuId) {
                    let idNum = parseInt(menuId, 10);
                    if (wire && typeof wire.openDetail === 'function') {
                        wire.openDetail(idNum);
                        return;
                    }
                    if (window.Livewire) {
                        window.Livewire.dispatch('open-menu-detail', { menuId: idNum });
                        return;
                    }
                }

                if (payload) {
                    if (wire && typeof wire.openDetailByName === 'function') {
                        wire.openDetailByName(payload);
                        return;
                    }
                    if (window.Livewire) {
                        window.Livewire.dispatch('open-menu-detail-by-name', { name: payload });
                        return;
                    }
                    if (wire && typeof wire.searchQuery !== 'undefined') {
                        wire.set('searchQuery', payload);
                    }
                }
            },
            formatMessage(text) {
                if (!text) return '';
                return text.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-slate-900">$1</strong>');
            },
            resetChat() {
                this.setupContextMessages();
                this.inputMessage = '';
                this.scrollToBottom();
            }
        };
    }
</script>
