{{-- Reusable Toast Notification Component --}}
{{-- Listens to Livewire `toast-notify` window event with auto-dismiss --}}
<div
    x-data="{
        show: false,
        message: '{{ $toastMessage ?? '' }}',
        timeout: null,
        trigger(msg) {
            this.message = msg || this.message;
            this.show = true;
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => this.show = false, 3000);
        }
    }"
    x-on:toast-notify.window="trigger($event.detail ? (typeof $event.detail === 'string' ? $event.detail : ($event.detail.message || '{{ $toastMessage ?? '' }}')) : '{{ $toastMessage ?? '' }}')"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
    class="fixed top-4 sm:top-5 left-1/2 -translate-x-1/2 z-50 max-w-[90vw] sm:max-w-md w-auto bg-[#0F172A] text-white text-xs sm:text-[13px] font-bold px-4 sm:px-5 py-2.5 sm:py-3 rounded-full shadow-xl border border-slate-700 flex items-center gap-2.5 pointer-events-none select-none"
    style="display: none;"
    role="status"
    aria-live="polite"
>
    <span class="w-2 sm:w-2.5 h-2 sm:h-2.5 rounded-full bg-emerald-400 shrink-0 animate-pulse"></span>
    <span class="truncate" x-text="message || '{{ $toastMessage ?? '' }}'">{{ $toastMessage ?? '' }}</span>
</div>
