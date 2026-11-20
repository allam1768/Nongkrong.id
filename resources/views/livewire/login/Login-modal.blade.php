{{-- Employee Login Modal Popup --}}
<div
    x-data="{ open: @entangle('showLoginModal') }"
    x-show="open"
    x-cloak
    @keydown.escape.window="open = false; $wire.closeLogin()"
    class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="login-title"
>
    {{-- Backdrop Overlay (Diperbagus: lebih gelap & blur lebih smooth untuk fokus maksimal) --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false; $wire.closeLogin()"
        class="fixed inset-0 bg-slate-900/75 backdrop-blur-md transition-opacity cursor-pointer"
    ></div>

    {{-- Modal Dialog Box --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-none sm:max-w-md bg-white rounded-t-3xl sm:rounded-3xl rounded-b-none sm:rounded-b-3xl pt-7 pb-8 px-6 sm:px-8 shadow-[0_0_40px_-10px_rgba(0,0,0,0.2)] border-t sm:border border-slate-100 z-10 transition-all select-auto overflow-hidden"
        @click.stop
    >
        {{-- Close Button --}}
        <button
            type="button"
            wire:click="closeLogin"
            @click="open = false"
            class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 p-2 rounded-full hover:bg-slate-100 active:scale-90 transition-all cursor-pointer focus:outline-none"
            title="Tutup"
            aria-label="Tutup"
        >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        {{-- Brand Badge & Header --}}
        <div class="flex flex-col items-center text-center select-none pt-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50/80 border border-blue-100 text-[11px] font-semibold text-brand-blue mb-3 font-poppins">
                <span class="w-1.5 h-1.5 rounded-full bg-brand-blue animate-pulse"></span>
                <span>Portal Operasional Karyawan</span>
            </div>

            <x-icon name="logo" class="text-brand-blue h-6 sm:h-7 w-auto max-w-44 object-contain" />

            <h2 id="login-title" class="text-xl sm:text-2xl font-bold font-poppins text-slate-900 tracking-tight mt-3">
                Masuk ke Akun Anda
            </h2>

            <p class="text-xs text-slate-500 font-poppins mt-1 max-w-xs">
                Akses kasir, barista, dapur & kelola pesanan kafe
            </p>
        </div>

        {{-- Login Form --}}
        <form wire:submit="submitLogin" class="mt-6 space-y-4">
            {{-- Username Field --}}
            <div>
                <label for="input-login-username" class="block text-xs font-semibold text-slate-700 font-poppins mb-1.5">
                    Username / ID Karyawan
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        wire:model="loginUsername"
                        id="input-login-username"
                        placeholder="Contoh: kasir / admin"
                        autocomplete="username"
                        autofocus
                        class="w-full h-12 bg-[#FAFAFC] hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder:text-slate-400 text-sm font-poppins pl-11 pr-4 rounded-xl border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 focus:outline-none transition-all"
                    />
                </div>
                @error('loginUsername')
                    <p class="mt-1.5 text-xs text-red-500 font-poppins text-left pl-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Password Field --}}
            <div x-data="{ show: false }">
                <div class="flex items-center justify-between mb-1.5">
                    <label for="input-login-password" class="block text-xs font-semibold text-slate-700 font-poppins">
                        Kata Sandi
                    </label>
                    {{-- Lupa Sandi: Ditambahkan class sm:hidden agar hilang di desktop tapi tetap ada di mobile --}}
                    <a
                        href="#"
                        wire:click.prevent
                        class="sm:hidden text-[11px] font-medium font-poppins text-brand-blue hover:underline transition-colors"
                    >
                        Lupa sandi?
                    </a>
                </div>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </span>
                    <input
                        :type="show ? 'text' : 'password'"
                        type="password"
                        wire:model="loginPassword"
                        id="input-login-password"
                        placeholder="Masukkan kata sandi"
                        autocomplete="current-password"
                        class="w-full h-12 bg-[#FAFAFC] hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder:text-slate-400 text-sm font-poppins pl-11 pr-11 rounded-xl border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 focus:outline-none transition-all"
                    />
                    <button
                        type="button"
                        @click="show = !show"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-700 transition-colors focus:outline-none cursor-pointer"
                        tabindex="-1"
                        :title="show ? 'Sembunyikan sandi' : 'Lihat sandi'"
                        :aria-label="show ? 'Sembunyikan sandi' : 'Lihat sandi'"
                    >
                        <span x-show="!show" class="block">
                            <x-icon name="eye-closed" class="w-4 h-4" />
                        </span>
                        <span x-show="show" x-cloak class="block">
                            <x-icon name="eye" class="w-4 h-4" />
                        </span>
                    </button>
                </div>
                @error('loginPassword')
                    <p class="mt-1.5 text-xs text-red-500 font-poppins text-left pl-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Sign In Button --}}
            <div class="pt-2">
                <button
                    type="submit"
                    id="btn-submit-login"
                    wire:loading.attr="disabled"
                    class="w-full h-12 bg-brand-blue hover:bg-[#003da1] text-white font-bold text-sm rounded-xl shadow-lg shadow-brand-blue/25 transition-all active:scale-98 cursor-pointer flex items-center justify-center font-poppins focus:outline-none focus:ring-2 focus:ring-brand-blue/40"
                >
                    <span wire:loading.remove wire:target="submitLogin" class="inline-flex items-center gap-2">
                        <span>Masuk ke Dashboard</span>
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/>
                            <path d="m12 5 7 7-7 7"/>
                        </svg>
                    </span>
                    <span wire:loading.inline-flex wire:target="submitLogin" class="inline-flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4 text-white animate-spin shrink-0 inline-block" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Memverifikasi Akun...</span>
                    </span>
                </button>
            </div>
        </form>

        {{-- Security Info Footer --}}
        <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-center gap-1.5 text-[11px] text-slate-400 font-poppins select-none">
            <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <span>Koneksi terenkripsi & sesi aman staf</span>
        </div>
    </div>
</div>
