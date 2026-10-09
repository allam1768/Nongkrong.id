@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
    'size' => 'md',
])

@php
    $baseClasses = 'font-poppins font-semibold transition-all duration-200 cursor-pointer text-center active:scale-[0.99] inline-flex items-center justify-center select-none disabled:opacity-75';

    $sizeClasses = [
        'sm'   => 'py-2 px-3 text-xs rounded-lg',
        'md'   => 'py-3 px-4 text-xs sm:text-sm rounded-xl',
        'lg'   => 'py-3.5 px-6 text-sm sm:text-base rounded-2xl',
        'icon' => 'w-12 h-12 sm:w-14 sm:h-14 p-0 rounded-2xl shrink-0',
    ];

    $variants = [
        'primary'   => 'bg-brand-blue hover:bg-[#003da1] text-white shadow-md shadow-brand-blue/30',
        'white'     => 'bg-white hover:bg-slate-50 text-black border border-slate-100 shadow-md shadow-slate-200/80',
        'secondary' => 'bg-white hover:bg-slate-50 text-black border border-slate-100 shadow-md shadow-slate-200/80',
    ];

    $classes = $baseClasses . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
