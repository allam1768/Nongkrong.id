@props([
    'type' => 'text',
    'togglePassword' => true,
])

@if ($type === 'password' && $togglePassword)
    <div x-data="{ show: false }" class="relative w-full">
        <input
            :type="show ? 'text' : 'password'"
            type="password"
            {{ $attributes->merge([
                'class' => 'w-full h-12 sm:h-14 bg-[#f0f3fa] hover:bg-[#ebf0f8] focus:bg-white text-slate-800 placeholder-slate-400 font-poppins text-sm sm:text-base font-normal pl-4 sm:pl-5 pr-12 rounded-2xl border-2 border-transparent focus:border-brand-blue focus:outline-none transition-all duration-200'
            ]) }}
        />
        <button
            type="button"
            @click="show = !show"
            class="absolute right-4 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-700 opacity-60 hover:opacity-100 transition-opacity focus:outline-none cursor-pointer"
            tabindex="-1"
            :title="show ? 'Sembunyikan password' : 'Lihat password'"
            :aria-label="show ? 'Sembunyikan password' : 'Lihat password'"
        >
            <img
                x-show="!show"
                src="{{ asset('assets/icn/at-icons_eye-closed.svg') }}"
                alt="Lihat password"
                class="w-5 h-5 select-none"
            />
            <img
                x-show="show"
                x-cloak
                src="{{ asset('assets/icn/at-icons_eye.svg') }}"
                alt="Sembunyikan password"
                class="w-5 h-5 select-none"
            />
        </button>
    </div>
@else
    <input
        type="{{ $type }}"
        {{ $attributes->merge([
            'class' => 'w-full h-12 sm:h-14 bg-[#f0f3fa] hover:bg-[#ebf0f8] focus:bg-white text-slate-800 placeholder-slate-400 font-poppins text-sm sm:text-base font-normal px-4 sm:px-5 rounded-2xl border-2 border-transparent focus:border-brand-blue focus:outline-none transition-all duration-200'
        ]) }}
    />
@endif
