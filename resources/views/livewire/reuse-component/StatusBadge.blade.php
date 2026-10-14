{{-- Reusable Status & Live Indicator Badge Component --}}
@php
    $variant = $variant ?? 'live';
    $label = $label ?? 'Live Sync';
    $pulse = $pulse ?? true;

    $configs = [
        'live' => [
            'bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            'dot' => 'bg-emerald-500',
        ],
        'process' => [
            'bg' => 'bg-blue-50 text-brand-blue border-blue-200',
            'dot' => 'bg-brand-blue',
        ],
        'warning' => [
            'bg' => 'bg-amber-50 text-amber-800 border-amber-200',
            'dot' => 'bg-amber-500',
        ],
        'danger' => [
            'bg' => 'bg-rose-50 text-rose-800 border-rose-200',
            'dot' => 'bg-rose-500',
        ],
    ];

    $cfg = $configs[$variant] ?? $configs['live'];
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full border text-[11px] sm:text-xs font-bold {{ $cfg['bg'] }} shrink-0 select-none">
    <span class="w-2 h-2 rounded-full {{ $cfg['dot'] }} {{ $pulse ? 'animate-pulse' : '' }}"></span>
    <span>{{ $label }}</span>
</span>
