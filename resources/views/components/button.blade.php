@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
    'size' => 'md',
    'loading' => null,
    'loadingText' => null,
    'loadingTarget' => null,
])

@php
    $baseClasses = 'font-poppins font-semibold transition-all duration-200 cursor-pointer text-center active:scale-[0.99] inline-flex items-center justify-center select-none disabled:opacity-75';

    $sizeClasses = [
        'sm'   => 'py-2 px-3 text-xs rounded-lg',
        'md'   => 'py-3 px-4 text-xs sm:text-sm rounded-xl',
        'lg'   => 'py-3.5 px-6 text-sm sm:text-base rounded-2xl',
        'icon' => 'w-12 h-12 sm:w-14 sm:h-14 p-0 rounded-2xl shrink-0',
    ];

    $spinnerSizes = [
        'sm'   => 'w-3.5 h-3.5',
        'md'   => 'w-4 h-4',
        'lg'   => 'w-5 h-5',
        'icon' => 'w-5 h-5',
    ];

    $variants = [
        'primary'   => 'bg-brand-blue hover:bg-[#003da1] text-white shadow-md shadow-brand-blue/30',
        'white'     => 'bg-white hover:bg-slate-50 text-black border border-slate-100 shadow-md shadow-slate-200/80',
        'secondary' => 'bg-white hover:bg-slate-50 text-black border border-slate-100 shadow-md shadow-slate-200/80',
    ];

    $classes = $baseClasses . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' ' . ($variants[$variant] ?? $variants['primary']);
    $spinnerSize = $spinnerSizes[$size] ?? 'w-4 h-4';

    $isCustomSlotLoading = $loading instanceof \Illuminate\View\ComponentSlot && $loading->isNotEmpty();
    $hasLoading = !empty($loading) || !empty($loadingText) || $isCustomSlotLoading;

    $target = $loadingTarget;
    if (! $target && is_string($loading) && ! in_array($loading, ['1', 'true', ''], true)) {
        $target = $loading;
    }
    if (! $target && $attributes->has('wire:target')) {
        $target = $attributes->get('wire:target');
    }

    $buttonAttrs = ['class' => $classes];
    if ($hasLoading) {
        $buttonAttrs['wire:loading.attr'] = 'disabled';
        if ($target) {
            $buttonAttrs['wire:target'] = $target;
        }
    }
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge($buttonAttrs) }}>
        @if ($hasLoading)
            <span wire:loading.remove @if($target) wire:target="{{ $target }}" @endif class="inline-flex items-center justify-center gap-2">
                {{ $slot }}
            </span>
            <span wire:loading @if($target) wire:target="{{ $target }}" @endif class="inline-flex items-center justify-center gap-2">
                @if ($isCustomSlotLoading)
                    {{ $loading }}
                @else
                    <svg class="{{ $spinnerSize }} animate-spin shrink-0" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    @if ($loadingText)
                        <span>{{ $loadingText }}</span>
                    @endif
                @endif
            </span>
        @else
            {{ $slot }}
        @endif
    </button>
@endif
