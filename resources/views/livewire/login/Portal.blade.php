<div x-data="portalState()"
    class="fixed inset-0 h-dvh w-full bg-white text-slate-900 flex flex-col justify-between overflow-hidden overscroll-none select-none px-0 md:px-6 lg:px-12 py-2 sm:py-3 md:py-4">
    {{-- 1. TOP HEADERS --}}
    {{-- Desktop Header (md and up): Logo on Left, Navigation (AI Barista Chat, Bahasa ID / EN) on Right --}}
    <header class="w-full hidden md:flex justify-between items-center max-w-7xl mx-auto pt-1 pb-1 z-30 shrink-0">
        <a href="{{ route('splash') }}" wire:navigate
            class="inline-block transition-transform duration-200 hover:opacity-90 focus:outline-none"
            title="NONGKRONG.id">
            <x-icon name="logo"
                class="text-brand-blue h-5 sm:h-6 w-auto max-w-45 sm:max-w-52.5 object-contain inline-block select-none" />
        </a>

        <div class="flex items-center gap-4 lg:gap-6 text-sm font-medium text-slate-800 font-poppins select-none">


            {{-- Pilihan Bahasa ID / EN (Identik Mockup: Clean Typography) --}}
            <div class="flex items-center gap-1.5 text-sm font-poppins select-none">
                <button type="button" @click="lang = 'ID'"
                    :class="lang === 'ID' ? 'font-bold text-slate-900' : 'font-normal text-slate-400 hover:text-slate-700'"
                    class="transition-colors cursor-pointer py-0.5">
                    ID
                </button>
                <span class="text-slate-400 font-light select-none">/</span>
                <button type="button" @click="lang = 'EN'"
                    :class="lang === 'EN' ? 'font-bold text-slate-900' : 'font-normal text-slate-400 hover:text-slate-700'"
                    class="transition-colors cursor-pointer py-0.5">
                    EN
                </button>

            </div>

            {{-- AI Barista Chatbot Button Desktop --}}
            <button type="button" @click="showAiChat = true"
                class="flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50/80 hover:bg-blue-100/80 text-brand-blue border border-blue-200/60 transition-all cursor-pointer focus:outline-none text-xs font-semibold font-poppins shadow-2xs hover:shadow-xs group"
                title="Tanya AI Barista Assistant">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <svg class="w-4 h-4 text-brand-blue group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                    <path d="M8 12h.01M12 12h.01M16 12h.01" stroke-width="2.5"/>
                </svg>
                <span>AI Barista</span>
            </button>
        </div>
    </header>

    {{-- Mobile Header (< md): Logo on Left, AI Barista Chat & Bahasa ID / EN on Right --}}
    <header class="w-full flex md:hidden justify-between items-center pt-2 pb-1 z-30 select-none shrink-0 px-5">
        <a href="{{ route('splash') }}" wire:navigate class="flex items-center group focus:outline-none"
            title="NONGKRONG.id">
            <x-icon name="logo"
                class="text-brand-blue h-5 w-auto max-w-36 object-contain inline-block select-none" />
        </a>

        <div class="flex items-center gap-3.5 text-xs font-medium text-slate-800 font-poppins select-none">
            {{-- AI Barista Chatbot Button Mobile --}}


            {{-- Pilihan Bahasa ID / EN (Identik Mockup: Clean Typography) --}}
            <div class="flex items-center gap-1 text-xs font-poppins select-none">
                <button type="button" @click="lang = 'ID'"
                    :class="lang === 'ID' ? 'font-bold text-slate-900' : 'font-normal text-slate-400 hover:text-slate-700'"
                    class="transition-colors cursor-pointer py-0.5">
                    ID
                </button>
                <span class="text-slate-400 font-light select-none">/</span>
                <button type="button" @click="lang = 'EN'"
                    :class="lang === 'EN' ? 'font-bold text-slate-900' : 'font-normal text-slate-400 hover:text-slate-700'"
                    class="transition-colors cursor-pointer py-0.5">
                    EN
                </button>
            </div>

            <button type="button" @click="showAiChat = true"
                class="relative text-slate-700 hover:text-brand-blue transition-colors cursor-pointer focus:outline-none flex items-center justify-center p-1.5 rounded-full hover:bg-slate-100"
                aria-label="Tanya AI Barista" title="AI Barista Chatbot">
                <span class="absolute top-1 right-1 flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <svg class="w-5 h-5 text-slate-800 hover:text-brand-blue transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                    <path d="M8 12h.01M12 12h.01M16 12h.01" stroke-width="2.5"/>
                </svg>
            </button>
        </div>
    </header>

    {{-- Flash Notification / Warning Alert --}}
    @if (session()->has('warning') || session()->has('alert') || session()->has('error'))
        <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4 pointer-events-auto">
            <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 shadow-xl flex items-center gap-3 text-xs font-poppins font-semibold">
                <x-icon name="alert-circle" class="w-4 h-4 text-amber-600 shrink-0" />
                <span class="flex-1">{{ session('warning') ?? session('alert') ?? session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- 2. MOBILE VIEW (< md): Mengikuti Persis Proporsi Mockup Pengguna --}}
    <div class="flex md:hidden flex-col items-center justify-between flex-1 w-full min-h-0 overflow-hidden z-20 pt-1 pb-0">
        {{-- Top Flow: Title, Buttons, Subtitle with evenly distributed vertical rhythm --}}
        <div class="flex-1 w-full flex flex-col items-center justify-evenly text-center px-6 min-h-0 py-2 sm:py-3">
            {{-- Section A: Title --}}
            <div class="shrink-0 pt-1 sm:pt-2">
                <h1 class="text-[34px] sm:text-4xl font-extrabold text-slate-900 tracking-tight text-center font-poppins leading-[1.18]">
                    <span class="block">Fall in Love</span>
                    <span class="block mt-1">with Coffee</span>
                </h1>
            </div>

            {{-- Section B: Action Buttons --}}
            <div class="w-full max-w-70 sm:max-w-75 flex flex-col items-center gap-3 shrink-0 z-30">
                {{-- Pesan Cepat Button (Top: Solid Brand Blue Pill + Paper Plane Icon) --}}
                <button type="button" id="btn-pesan-cepat-mobile"
                    wire:click="openTableModal"
                    class="w-full h-12 bg-brand-blue hover:bg-[#003da1] text-white font-bold text-sm rounded-full shadow-lg shadow-brand-blue/35 transition-all active:scale-98 cursor-pointer flex items-center justify-center gap-2 font-poppins">
                    <span>Pesan Cepat</span>

                </button>

                {{-- Login Karyawan Button (Bottom: White Pill with Brand Blue Border) --}}
                <button type="button" id="btn-login-karyawan-mobile"
                    wire:click="openLogin"
                    class="w-full h-12 bg-white hover:bg-slate-50 text-brand-blue border border-brand-blue font-bold text-sm rounded-full transition-all active:scale-98 cursor-pointer flex items-center justify-center font-poppins select-none">
                    Login Karyawan
                </button>
            </div>

            {{-- Section C: Subtitle --}}
            <div class="shrink-0 px-2 pb-1">
                <p class="text-xs sm:text-[13px] text-slate-800 font-sora text-center max-w-[320px] leading-relaxed mx-auto">
                    Selamat datang di sudut kopi kami yang nyaman, tempat setiap cangkir dibuat penuh cinta untuk Anda.
                </p>
            </div>
        </div>

        {{-- Section D: Barista Illustration Touching Bottom Edge --}}
        <div class="w-full flex-1 min-h-0 flex items-end justify-center select-none pointer-events-none overflow-hidden">
            <img src="{{ asset('assets/img/portal.svg') }}" alt="Barista Nongkrong.id"
                class="w-[122%] max-w-none h-auto max-h-[52vh] object-contain object-bottom pointer-events-none" />
        </div>
    </div>

    {{-- 3. DESKTOP VIEW (>= md): Layout 2 Kolom --}}
    <main
        class="hidden md:flex w-full max-w-7xl mx-auto flex-1 flex-row items-center justify-end md:pr-0 lg:pr-2 xl:pr-6 py-1 sm:py-2 z-20 min-h-0 overflow-hidden">
        {{-- Left: Barista Illustration Fixed to Bottom Left --}}
        <div
            class="relative w-full flex items-center justify-center select-none my-0 md:fixed md:bottom-0 md:left-0 md:w-[38vw] lg:w-[42vw] xl:w-[46vw] md:max-w-130 lg:max-w-150 md:h-[75vh] lg:h-[82vh] xl:h-[88vh] pointer-events-none z-10 overflow-visible">
            <img src="{{ asset('assets/img/portal.svg') }}" alt="Barista Nongkrong.id"
                class="w-full h-full object-contain object-bottom-left pointer-events-none" />
        </div>

        {{-- Right Side: Typography & Action Buttons --}}
        <div
            class="flex flex-col items-start text-left px-0 max-w-xl lg:max-w-2xl w-full my-0 z-20 shrink-0 md:translate-x-3 lg:translate-x-6">

            {{-- Main Title --}}
            <h1
                class="text-[32px] lg:text-4xl xl:text-[44px] font-bold text-slate-900 leading-[1.15] tracking-tight font-poppins">
                <span class="block">Fall in Love</span>
                <span class="inline-flex items-center gap-2 mt-1 whitespace-nowrap">
                    <span>with Coffee</span>
                </span>
            </h1>

            {{-- Desktop Subtitle --}}
            <p class="mt-3.5 text-sm text-neutral-600 font-sora max-w-105 leading-relaxed">
                Selamat datang di sudut kopi kami yang nyaman, tempat setiap cangkir dibuat penuh cinta untuk Anda.
            </p>

            {{-- Desktop Action Buttons: Pesan Cepat & Login Karyawan --}}
            <div class="mt-6 sm:mt-7 w-full flex flex-col items-start gap-3 md:gap-3.5">
                {{-- Pesan Cepat Button --}}
                <button type="button" id="btn-pesan-cepat-desktop"
                    wire:click="openTableModal"
                    class="w-60 h-12 bg-brand-blue hover:bg-[#003da1] text-white font-bold text-sm rounded-full shadow-md shadow-brand-blue/25 transition-all active:scale-98 cursor-pointer flex items-center justify-center gap-2 font-poppins">
                    <span>Pesan Cepat</span>
                </button>

                {{-- Login Karyawan Button --}}
                <button type="button" id="btn-login-karyawan-desktop" wire:click="openLogin"
                    class="w-60 h-12 bg-white hover:bg-slate-50 text-brand-blue border border-brand-blue font-bold text-sm rounded-full shadow-2xs transition-all active:scale-98 cursor-pointer flex items-center justify-center font-poppins select-none">
                    Login Karyawan
                </button>
            </div>
        </div>
    </main>

    {{-- 4. MODALS & POPUPS --}}
    {{-- AI Barista Chatbot Overlay Modal --}}
    @include('livewire.reuse-component.AiBarista-modal')

    {{-- Employee Login Modal Popup (Separated into Partial) --}}
    @include('livewire.login.Login-modal')

    {{-- Mobile Table Selection Modal Popup (Separated into Partial) --}}
    @include('livewire.user.TableSelection-modal')

    {{-- Quick Order Name Modal Popup (Separated into Partial) --}}
    @include('livewire.user.NameInput-modal')

    {{-- Subtle Minimal Footer (Desktop Only) --}}
    <footer class="hidden md:block w-full text-center py-1 sm:py-2 text-[11px] text-neutral-400 font-sora z-30 select-none shrink-0">
        &copy; {{ $currentYear }} NONGKRONG.id
    </footer>

    {{-- State Logic for AI Barista & UI --}}
    <script>
        function portalState() {
            return {
                showAiChat: false,
                lang: 'ID',
                inputMessage: '',
                isTyping: false,
                messages: [
                    {
                        sender: 'ai',
                        text: 'Halo! Saya **AI Barista Nongkrong.id**\nBingung mau pesan apa hari ini? Ceritakan seleramu (misal: kopi manis creamy, strong melek, atau non-kopi segar), atau pilih opsi cepat di bawah!',
                        time: 'Baru saja',
                        showCta: false
                    }
                ],
                quickPrompts: [
                    'Kopi Manis & Creamy',
                    'Kopi Strong / Melek',
                    'Minuman Segar Non-Kopi',
                    'Rekomendasi Cemilan',
                    'Cara Pesan ke Meja'
                ],
                sendUserMessage(text) {
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

                    this.$nextTick(() => {
                        let box = this.$refs.chatBox;
                        if (box) box.scrollTop = box.scrollHeight;
                    });

                    setTimeout(() => {
                        this.isTyping = false;
                        let res = this.getAiReply(msg);
                        this.messages.push({
                            sender: 'ai',
                            text: res.text,
                            time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                            showCta: res.showCta
                        });
                        this.$nextTick(() => {
                            let box = this.$refs.chatBox;
                            if (box) box.scrollTop = box.scrollHeight;
                        });
                    }, 750);
                },
                getAiReply(query) {
                    let q = query.toLowerCase();
                    if (q.includes('manis') || q.includes('creamy') || q.includes('susu') || q.includes('aren') || q.includes('caramel')) {
                        return {
                            text: 'Rekomendasi terbaik buat kamu:\n1. **Es Kopi Susu Gula Aren** (Best Seller — manis legit gurih)\n2. **Caramel Macchiato** (Espresso lembut berpadu saus karamel)\n\nKeduanya menggunakan susu segar pilihan yang creamy banget!',
                            showCta: true
                        };
                    }
                    if (q.includes('strong') || q.includes('melek') || q.includes('kerja') || q.includes('begadang') || q.includes('pahit') || q.includes('americano') || q.includes('espresso')) {
                        return {
                            text: 'Pilihan paling pas untuk booster energi:\n1. **Double Shot Americano** (Bold, aromatic, zero sugar)\n2. **V60 Single Origin Aceh Gayo** (Manual brew aroma fruity & floral)\n\nDijamin fokus seharian!',
                            showCta: true
                        };
                    }
                    if (q.includes('non') || q.includes('segar') || q.includes('teh') || q.includes('matcha') || q.includes('cokelat') || q.includes('berry')) {
                        return {
                            text: 'Menu non-kopi segar favorit:\n1. **Matcha Latte Ice Premium** (Uji matcha kental kaya rasa)\n2. **Sparkling Berry Mojito** (Soda dingin dengan sari buah beri asli)\n\nSegar dan dingin untuk menemani harimu!',
                            showCta: true
                        };
                    }
                    if (q.includes('cemilan') || q.includes('makan') || q.includes('roti') || q.includes('snack') || q.includes('croissant') || q.includes('toast')) {
                        return {
                            text: 'Pasangan ngopi paling pas:\n1. **Butter Croissant** hangat & renyah\n2. **Choco Lava Toast** lumeran cokelat hangat\n3. **Truffle French Fries** untuk cemilan gurih!',
                            showCta: true
                        };
                    }
                    if (q.includes('pesan') || q.includes('meja') || q.includes('qr') || q.includes('cara') || q.includes('order')) {
                        return {
                            text: 'Caranya sangat mudah! Klik tombol **Pesan Cepat** di bawah untuk memilih meja atau scan kode QR meja kamu. Pesananmu akan segera disiapkan barista!',
                            showCta: true
                        };
                    }
                    return {
                        text: 'Pilihan yang menarik! Menu rekomendasi barista kami hari ini adalah **Signature Creamy Latte**. Mau langsung pesan ke meja sekarang?',
                        showCta: true
                    };
                },
                formatMessage(text) {
                    if (!text) return '';
                    return text.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-slate-900">$1</strong>');
                },
                resetChat() {
                    this.messages = [
                        {
                            sender: 'ai',
                            text: 'Halo! Saya **AI Barista Nongkrong.id**\nBingung mau pesan apa hari ini? Ceritakan seleramu atau pilih opsi cepat di bawah!',
                            time: 'Baru saja',
                            showCta: false
                        }
                    ];
                    this.inputMessage = '';
                }
            };
        }
    </script>
</div>

