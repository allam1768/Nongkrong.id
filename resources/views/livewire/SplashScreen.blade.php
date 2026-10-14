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
        <x-icon
                name="nongkrong-id"
                size="h-8 sm:h-12 md:h-16 w-auto"
                class="text-white select-none"
            />
    </div>
</div>

