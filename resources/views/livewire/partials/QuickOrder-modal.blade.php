{{-- Quick Order Modal Popup (Pesan Cepat) --}}
<div
    x-data="{ open: @entangle('showQuickOrderModal') }"
    x-show="open"
    x-cloak
    @keydown.escape.window="open = false; $wire.closeQuickOrder()"
    class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="quick-order-title"
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
        @click="open = false; $wire.closeQuickOrder()"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"
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
        class="relative w-full max-w-sm sm:max-w-md bg-white rounded-t-[28px] sm:rounded-3xl rounded-b-none sm:rounded-b-3xl p-6 sm:p-8 shadow-2xl border-t sm:border border-slate-100 z-10 transition-all select-auto"
        @click.stop
    >
        {{-- Close Button --}}
        <button
            type="button"
            wire:click="closeQuickOrder"
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

        {{-- Centered Logo: NONGKRONG.id --}}
        <div class="flex items-center justify-center mb-6 sm:mb-7 select-none">
            <img
                src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                alt="NONGKRONG.id"
                class="h-7 sm:h-8 w-auto max-w-[85%] object-contain select-none"
            />
        </div>

        {{-- Form: Input 'Your Name' & Send Button --}}
        <form wire:submit="submitQuickOrder" class="w-full">
            <div class="flex items-stretch gap-3">
                {{-- Input Field 'Your Name' --}}
                <div class="relative flex-1">
                    <x-input
                        type="text"
                        wire:model="customerName"
                        id="input-customer-name"
                        placeholder="Your Name"
                        autocomplete="off"
                        autofocus
                    />
                </div>

                {{-- Blue Submit Button with Paper Airplane Icon --}}
                <x-button
                    size="icon"
                    type="submit"
                    id="btn-submit-quick-order"
                    wire:loading.attr="disabled"
                    title="Kirim"
                >
                    <img
                        src="{{ asset('assets/icn/ci_paper-plane.svg') }}"
                        alt="Kirim"
                        wire:loading.remove
                        wire:target="submitQuickOrder"
                        class="w-5 h-5 sm:w-6 sm:h-6 select-none"
                    />
                    <svg
                        wire:loading
                        wire:target="submitQuickOrder"
                        class="w-5 h-5 text-white animate-spin"
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </x-button>
            </div>

            {{-- Validation Feedback --}}
            @error('customerName')
                <p class="mt-2 text-xs text-red-500 font-poppins text-left pl-1">
                    {{ $message }}
                </p>
            @enderror
        </form>
    </div>
</div>
