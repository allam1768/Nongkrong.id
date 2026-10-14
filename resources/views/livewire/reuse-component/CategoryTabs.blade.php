{{-- Reusable Category Filter Tabs / Pills Component --}}
@php
    $currentSelected = $selectedId ?? ($selectedCategoryId ?? 0);
    $allLabel = $allLabel ?? 'Semua';
    $wireTarget = $wireTarget ?? 'selectedCategoryId';
@endphp

<div class="flex gap-2 overflow-x-auto w-full no-scrollbar pb-1 select-none text-xs font-bold items-center">
    <button
        type="button"
        wire:click="$set('{{ $wireTarget }}', 0)"
        class="px-3.5 py-2 rounded-xl sm:rounded-2xl border-2 transition whitespace-nowrap cursor-pointer shrink-0 {{ (int)$currentSelected === 0 ? 'bg-brand-blue text-white border-brand-blue shadow-xs' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}"
    >
        {{ $allLabel }}
    </button>
    @foreach ($categories as $cat)
        <button
            type="button"
            wire:click="$set('{{ $wireTarget }}', {{ $cat->id }})"
            class="px-3.5 py-2 rounded-xl sm:rounded-2xl border-2 transition whitespace-nowrap cursor-pointer shrink-0 {{ (int)$currentSelected === (int)$cat->id ? 'bg-brand-blue text-white border-brand-blue shadow-xs' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}"
        >
            {{ $cat->name }}
        </button>
    @endforeach
</div>
