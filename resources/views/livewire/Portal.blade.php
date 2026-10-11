<div class="min-h-screen bg-white text-slate-900 flex flex-col justify-between relative overflow-hidden px-4 sm:px-6 lg:px-12 py-4 sm:py-6">
    {{-- 1. TOP HEADER --}}
    {{-- Desktop Header (md and up): Horizontal Logo --}}
    <header class="w-full hidden md:flex justify-center pt-2 sm:pt-4 pb-2 z-30">
        <a href="{{ route('splash') }}" wire:navigate class="inline-block transition-transform duration-200 hover:scale-105 focus:outline-none" title="NONGKRONG.id">
            <img
                src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                alt="NONGKRONG.id"
                class="h-8 sm:h-12 md:h-16 w-auto object-contain select-none"
            />
        </a>
    </header>

    {{-- Mobile Header (< md): Mascot on Top + NONGKRONG.id below (Identik Mockup) --}}
    <header class="w-full flex md:hidden flex-col items-center justify-center pt-3 pb-2 z-30 select-none">
        <a href="{{ route('splash') }}" wire:navigate class="flex flex-col items-center group focus:outline-none" title="NONGKRONG.id">
            <img
                src="{{ asset('assets/img/icont_noks-blue.svg') }}"
                alt="Icon Nongkrong.id"
                class="w-11 h-11 object-contain transition-transform group-hover:scale-105 mb-1.5 select-none"
            />
        </a>
    </header>

    {{-- 2. MAIN CONTENT --}}
    <main class="w-full max-w-7xl mx-auto flex-1 flex flex-col md:flex-row items-center justify-center md:justify-end md:pr-12 lg:pr-20 xl:pr-28 py-2 sm:py-6 z-20">
        {{-- Baristas Illustration Container (Barista 2 Mepet ke Kiri di Mobile & Fixed di Desktop) --}}
        <div class="relative w-[calc(100%+2rem)] -mx-4 sm:w-[calc(100%+3rem)] sm:-mx-6 md:mx-0 max-w-none md:max-w-190 lg:max-w-215 xl:max-w-240 aspect-5/4 sm:aspect-4/3 md:aspect-auto max-h-75 md:max-h-180 flex items-end select-none my-auto md:my-0 md:fixed md:bottom-0 md:left-0 md:w-[55vw] md:h-[75vh] pointer-events-none z-10">
            {{-- Male Barista with Espresso Machine --}}
            <img
                src="{{ asset('assets/img/barista1.png') }}"
                alt="Barista operasional mesin espresso"
                class="absolute -right-4 sm:-right-6 md:right-auto md:left-[16%] lg:left-[18%] bottom-0 w-[80%] sm:w-[78%] md:w-[78%] lg:w-[80%] h-auto md:h-full object-contain object-bottom drop-shadow-sm pointer-events-none"
                loading="eager"
            />

            {{-- Female Barista Pouring Latte Art (Mepet ke Kiri Penuh di Layar Mobile) --}}
            <img
                src="{{ asset('assets/img/barista2.png') }}"
                alt="Barista menuangkan latte art"
                class="absolute left-0 bottom-0 w-[52%] sm:w-[50%] md:w-[46%] lg:w-[48%] h-auto md:max-h-[88%] object-contain object-bottom drop-shadow-sm pointer-events-none"
                loading="eager"
            />
        </div>

        {{-- Right Side / Mobile Bottom: Typography & Action Buttons --}}
        <div class="flex flex-col items-center text-center px-2 sm:px-4 max-w-sm w-full mt-2 sm:mt-6 md:mt-0 z-20">
            {{-- Main Title --}}
            <h1 class="text-2xl sm:text-3xl lg:text-[34px] font-bold text-black leading-tight tracking-tight">
                <span class="block font-poppins">Fall in Love with</span>
                <span class="inline-flex items-center justify-center gap-1.5 flex-wrap font-poppins mt-0.5">
                    <span>Coffee in</span>
                    <img
                        src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                        alt="NONGKRONG.id"
                        class="h-5 sm:h-6 lg:h-7 w-auto max-w-45 sm:max-w-52.5 object-contain inline-block select-none"
                    />
                </span>
            </h1>

            {{-- Subtitle --}}
            <p class="mt-2.5 sm:mt-3 text-[11px] sm:text-xs text-neutral-400 font-sora max-w-70 sm:max-w-xs leading-relaxed">
                Welcome to our cozy coffee corner, where every cup is a delightful for you.
            </p>

            {{-- Action Buttons: Stacked Full-Width Pills Identik Mockup --}}
            <div class="mt-5 sm:mt-6 w-full max-w-[320px] sm:max-w-xs flex flex-col gap-3">
                {{-- Login Karyawan Button (Primary Brand Blue Pill) --}}
                <button
                    type="button"
                    id="btn-login-karyawan"
                    wire:click="openLogin"
                    class="w-full h-12 bg-white hover:bg-slate-50 text-slate-900 border border-slate-200 font-bold text-sm rounded-full shadow-2xs transition-all active:scale-98 cursor-pointer flex items-center justify-center font-poppins select-none"
                >
                    Login Karyawan
                </button>

                {{-- Pesan Cepat Button (Modal on Mobile, Direct to Table Selection on Desktop) --}}
                <button
                    type="button"
                    id="btn-pesan-cepat"
                    @click="if (window.innerWidth < 768) { $wire.openTableModal() } else { $wire.openTableSelection() }"
                    class="w-full h-12 bg-brand-blue hover:bg-[#003da1] text-white font-bold text-sm rounded-full shadow-md shadow-brand-blue/25 transition-all active:scale-98 cursor-pointer flex items-center justify-center font-poppins"
                >
                    Pesan Cepat
                </button>
            </div>
        </div>
    </main>

    {{-- Employee Login Modal Popup (Separated into Partial) --}}
    @include('livewire.partials.Login-modal')

    {{-- Mobile Table Selection Modal Popup (Separated into Partial) --}}
    @include('livewire.partials.TableSelection-modal')

    {{-- Quick Order Name Modal Popup (Separated into Partial) --}}
    @include('livewire.partials.NameInput-modal')

    {{-- Subtle Minimal Footer --}}
    <footer class="w-full text-center py-2 text-[11px] text-neutral-300 font-sora z-30">
        &copy; {{ $currentYear }} NONGKRONG.id
    </footer>
</div>
