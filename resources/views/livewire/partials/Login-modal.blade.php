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
    {{-- Backdrop Overlay --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false; $wire.closeLogin()"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"
    ></div>

    {{-- Modal Dialog Box (Matching Reference Design) --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-sm sm:max-w-md bg-white rounded-t-[28px] sm:rounded-3xl rounded-b-none sm:rounded-b-3xl pt-9 pb-8 px-6 sm:px-8 shadow-2xl border-t sm:border border-slate-100 z-10 transition-all select-auto"
        @click.stop
    >
        {{-- Close Button --}}
        <button
            type="button"
            wire:click="closeLogin"
            @click="open = false"
            class="absolute top-4 right-4 sm:top-5 sm:right-5 text-slate-300 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-100 active:scale-90 transition-all cursor-pointer focus:outline-none"
            title="Tutup"
            aria-label="Tutup"
        >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        {{-- Heading: Login here --}}
        <h2 id="login-title" class="text-2xl sm:text-[30px] font-bold font-poppins text-brand-blue tracking-tight text-center">
            Login here
        </h2>

        {{-- Subtitle: Welcome back you've been missed! --}}
        <p class="mt-4 sm:mt-5 text-lg sm:text-xl font-bold font-poppins text-slate-900 text-center leading-snug">
            Welcome back you’ve<br />been missed!
        </p>

        {{-- Login Form --}}
        <form wire:submit="submitLogin" class="mt-8 sm:mt-9 space-y-4 sm:space-y-5">
            {{-- Username Field with Blue Border --}}
            <div>
                <x-input
                    type="text"
                    wire:model="loginUsername"
                    id="input-login-username"
                    placeholder="Username"
                    autocomplete="username"
                    autofocus
                />
                @error('loginUsername')
                    <p class="mt-1.5 text-xs text-red-500 font-poppins text-left pl-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Password Field --}}
            <div>
                <x-input
                    type="password"
                    wire:model="loginPassword"
                    id="input-login-password"
                    placeholder="Password"
                    autocomplete="current-password"
                />
                @error('loginPassword')
                    <p class="mt-1.5 text-xs text-red-500 font-poppins text-left pl-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Forgot Password Link --}}
            <div class="flex justify-end pt-1">
                <a
                    href="#"
                    wire:click.prevent
                    class="text-xs sm:text-sm font-semibold font-poppins text-brand-blue hover:underline transition-colors"
                >
                    Forgot your password?
                </a>
            </div>

            {{-- Sign in Button --}}
            <div class="pt-3 sm:pt-4">
                <x-button
                    type="submit"
                    id="btn-submit-login"
                    wire:loading.attr="disabled"
                    variant="primary"
                    class="w-full py-3.5! sm:py-4! text-base! sm:text-lg! rounded-2xl! font-bold shadow-xl shadow-brand-blue/30"
                >
                    <span wire:loading.remove wire:target="submitLogin">Sign in</span>
                    <span wire:loading wire:target="submitLogin" class="inline-flex items-center gap-2">
                        <svg class="w-5 h-5 text-white animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Memproses...</span>
                    </span>
                </x-button>
            </div>
        </form>
    </div>
</div>
