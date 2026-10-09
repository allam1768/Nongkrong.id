<div
    x-data="{
        redirected: false,
        init() {
            setTimeout(() => {
                this.navigateNext();
            }, 2000);
        },
        navigateNext() {
            if (this.redirected) return;
            this.redirected = true;
            if (window.Livewire && typeof window.Livewire.navigate === 'function') {
                window.Livewire.navigate('{{ route('portal') }}');
            } else {
                window.location.href = '{{ route('portal') }}';
            }
        }
    }"
    class="fixed inset-0 z-50 flex items-center justify-center bg-brand-blue select-none cursor-pointer overflow-hidden p-6"
>
    {{-- Centered Nongkrong.id Logo --}}
    <div
        class="flex flex-col items-center justify-center transition-transform duration-700 ease-out transform"
        x-data="{ visible: false }"
        x-init="setTimeout(() => visible = true, 50)"
        :class="visible ? 'opacity-100 scale-100' : 'opacity-0 scale-95'"
    >
        <img
            src="{{ asset('assets/img/NongkrongId.svg') }}"
            alt="Nongkrong.id"
            class="w-64 sm:w-80 md:w-96 lg:w-105 max-w-[85vw] h-auto drop-shadow-sm pointer-events-none"
            loading="eager"
            fetchpriority="high"
        />
    </div>
</div>

