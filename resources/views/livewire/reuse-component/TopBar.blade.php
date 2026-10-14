{{-- Reusable Responsive Top Bar / Navigation Component --}}
@php
    $subtitle = $subtitle ?? 'NONGKRONG.ID';
    $title = $title ?? 'Sistem Manajemen Kafe';
    $backRoute = $backRoute ?? null;
    $backTitle = $backTitle ?? 'Kembali';
@endphp

<header class="sticky top-0 z-40 bg-white border-b-2 border-slate-200 px-4 sm:px-8 py-3.5 shadow-xs">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            @if ($backRoute)
                <a
                    href="{{ $backRoute }}"
                    wire:navigate
                    class="w-9 h-9 rounded-xl border-2 border-slate-200 bg-white flex items-center justify-center text-slate-700 hover:border-brand-blue hover:text-brand-blue transition font-black text-sm shrink-0"
                    title="{{ $backTitle }}"
                >
                    ←
                </a>
            @endif
            <div>
                <span class="text-xs font-black text-brand-blue tracking-wide uppercase block">{{ $subtitle }}</span>
                <h1 class="text-base sm:text-lg font-black text-slate-900 leading-tight">{{ $title }}</h1>
            </div>
        </div>

        @if (isset($slotActions))
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar shrink-0">
                {!! $slotActions !!}
            </div>
        @endif
    </div>
</header>
