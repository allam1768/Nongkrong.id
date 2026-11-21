<div x-data="portalState()"
    class="fixed inset-0 h-dvh w-full bg-white text-slate-900 flex flex-col justify-between overflow-hidden overscroll-none select-none px-0 md:px-6 lg:px-12 py-2 sm:py-3 md:py-4">
    {{-- 1. TOP HEADERS --}}
    {{-- Desktop Header (md and up): Logo on Left, Navigation (AI Barista Chat, Bahasa ID / EN) on Right --}}
    <header class="w-full hidden md:flex justify-between items-center max-w-7xl mx-auto pt-1 pb-1 z-30 shrink-0">
        <a href="{{ route('splash') }}" wire:navigate
            class="inline-block transition-transform duration-200 hover:opacity-90 focus:outline-none"
            title="NONGKRONG.id">
            <img
                src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                alt="Nongkrong.id"
                class="h-5 sm:h-6 w-auto object-contain inline-block select-none"
            />
        </a>

        <div class="flex items-center gap-4 lg:gap-6 text-sm font-medium text-slate-800 font-poppins select-none">



        </div>
    </header>

    {{-- Mobile Header (< md): Logo on Left, AI Barista Chat & Bahasa ID / EN on Right --}}
    <header class="w-full flex md:hidden justify-between items-center pt-2 pb-1 z-30 select-none shrink-0 px-5">
        <a href="{{ route('splash') }}" wire:navigate class="flex items-center group focus:outline-none"
            title="NONGKRONG.id">
            <img
                src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                alt="Nongkrong.id"
                class="h-5 w-auto object-contain inline-block select-none"
            />
        </a>
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
            <picture class="w-[122%] max-w-none h-auto max-h-[52vh] flex items-end justify-center pointer-events-none">
                <source srcset="{{ asset('assets/img/portal.webp') }}" type="image/webp">
                <img src="{{ asset('assets/img/portal.svg') }}" alt="Barista Nongkrong.id"
                    class="w-full h-auto object-contain object-bottom pointer-events-none" />
            </picture>
        </div>
    </div>

    {{-- 3. DESKTOP VIEW (>= md): Layout 2 Kolom --}}
    <main
        class="hidden md:flex w-full max-w-7xl mx-auto flex-1 flex-row items-center justify-end md:pr-0 lg:pr-2 xl:pr-6 py-1 sm:py-2 z-20 min-h-0 overflow-hidden">
        {{-- Left: Barista Illustration Fixed to Bottom Left --}}
        <div
            class="relative w-full flex items-center justify-center select-none my-0 md:fixed md:bottom-0 md:left-0 md:w-[38vw] lg:w-[42vw] xl:w-[46vw] md:max-w-130 lg:max-w-150 md:h-[75vh] lg:h-[82vh] xl:h-[88vh] pointer-events-none z-10 overflow-visible">
            <picture class="w-full h-full pointer-events-none">
                <source srcset="{{ asset('assets/img/portal.webp') }}" type="image/webp">
                <img src="{{ asset('assets/img/portal.svg') }}" alt="Barista Nongkrong.id"
                    class="w-full h-full object-contain object-bottom-left pointer-events-none" />
            </picture>
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

    {{-- Global Toast Notification --}}
    @include('livewire.reuse-component.Toast-notification')

    {{-- Subtle Minimal Footer (Desktop Only) --}}
    <footer class="hidden md:block w-full text-center py-1 sm:py-2 text-[11px] text-neutral-400 font-sora z-30 select-none shrink-0">
        &copy; {{ $currentYear }} NONGKRONG.id
    </footer>

    {{-- State Logic for Portal --}}
    <script>
        function portalState() {
            return {
                lang: 'ID'
            };
        }
    </script>
</div>

