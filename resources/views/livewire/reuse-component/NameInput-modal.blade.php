{{-- Quick Order Full Screen Modal (Input Nama Pelanggan) --}}
<div
    x-data="{ open: @entangle('showQuickOrderModal') }"
    x-show="open"
    x-cloak
    @keydown.escape.window="open = false; $wire.closeQuickOrder()"
    class="fixed inset-0 z-50 flex items-center justify-center p-0 sm:p-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="quick-order-title"
>
    {{-- Backdrop Overlay (Only visible on tablet/desktop centered dialog) --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false; $wire.closeQuickOrder()"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity hidden sm:block"
    ></div>

    {{-- Dialog Box: Full Screen on Mobile, Centered Modal on Tablet/Desktop --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
        class="relative w-full h-full sm:h-auto min-h-dvh sm:min-h-0 sm:max-w-md bg-white rounded-none sm:rounded-3xl shadow-none sm:shadow-2xl sm:border border-slate-100 z-10 transition-all select-auto flex flex-col justify-between p-6 sm:p-8 overflow-y-auto overflow-x-hidden"
        @click.stop
    >


        {{-- Top Navigation Bar --}}
        <div class="relative w-full flex items-center justify-between shrink-0 pt-1 sm:pt-0 z-20">
            {{-- Left: Back to Table Selection Button --}}
            <button
                type="button"
                wire:click="backToTableModal"
                class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center active:scale-90 transition-all cursor-pointer focus:outline-none border border-slate-200/80 z-10"
                title="Ganti Meja"
                aria-label="Ganti Meja"
            >
                <img
                    src="{{ asset('assets/icn/black/toleft-black.svg') }}"
                    alt="Kembali"
                    class="w-4 h-auto object-contain select-none"
                />
            </button>

            <div class="absolute left-1/2 -translate-x-1/2 flex items-center justify-center pointer-events-none select-none">
                <img
                    src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                    alt="NONGKRONG.id"
                    class="h-6 sm:h-7 w-auto object-contain select-none"
                />
            </div>


        </div>

        {{-- Center Content: Mascot Box, Table Badge, Greetings & Name Input --}}
        <div class="flex-1 flex flex-col justify-center max-w-sm mx-auto w-full py-6 sm:py-4 relative z-10">


            {{-- Title & Instructions --}}
            <div class="text-center mb-6">
                <h3 id="quick-order-title" class="text-2xl sm:text-3xl font-extrabold font-poppins text-slate-900 tracking-tight">
                    Siapa Nama Kamu?
                </h3>
                <p class="text-xs sm:text-sm text-slate-500 font-poppins mt-2 max-w-xs mx-auto leading-relaxed">
                    Masukkan nama panggilanmu agar barista kami bisa menyapa dan mengantarkan pesanan mejamu dengan tepat!
                </p>
            </div>

            {{-- Form: Input 'Your Name' & Submit Actions --}}
            <form wire:submit="submitQuickOrder" class="w-full flex flex-col gap-3">
                {{-- Input Field Container with User Icon (Airplane Button Removed) --}}
                <div class="relative w-full flex items-center">
                    {{-- User Avatar Icon --}}


                    {{-- Name Input --}}
                    <input
                        type="text"
                        wire:model="customerName"
                        id="input-customer-name"
                        placeholder="Your Name"
                        autocomplete="name"
                        autofocus
                        class="w-full h-13 pl-4 pr-4 rounded-2xl bg-white border-2 border-slate-200 focus:border-brand-blue focus:outline-none text-slate-900 placeholder:text-slate-300 font-poppins text-sm sm:text-base font-semibold transition-all shadow-2xs"
                    />
                </div>

                {{-- Validation Feedback --}}
                @error('customerName')
                    <p class="text-xs text-rose-500 font-poppins font-medium text-left pl-1 -mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror

                {{-- Primary CTA Full-Width Button --}}
                <x-button
                    type="submit"
                    id="btn-submit-quick-order"
                    size="lg"
                    loading="submitQuickOrder"
                    loadingText="Menyiapkan Meja..."
                    class="w-full h-13 font-bold text-sm sm:text-base shadow-lg shadow-brand-blue/20 mt-1"
                >
                    <span>Lanjut Pilih Menu</span>
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </x-button>

                {{-- Trust / Session Indicator --}}
                <div class="flex items-center justify-center gap-1.5 mt-1.5 text-xs text-slate-400 font-poppins select-none">
                    <svg class="w-4 h-4 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <span>Sesi meja langsung aktif &bull; Tanpa perlu registrasi akun</span>
                </div>
            </form>
        </div>

        {{-- Bottom Footer Note (Mobile only) --}}
        <div class="w-full text-center py-2 sm:hidden text-xs text-slate-400 font-poppins select-none shrink-0 relative z-10">
            NONGKRONG.id &bull; Self Order Experience
        </div>
    </div>
</div>
