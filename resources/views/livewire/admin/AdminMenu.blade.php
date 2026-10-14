<div class="min-h-screen bg-[#F8FAFC] flex flex-col lg:flex-row font-sans">
    {{-- Toast Notification --}}
    @include('livewire.reuse-component.Toast-notification')

    {{-- Sidebar --}}
    @include('livewire.admin.partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-5 sm:p-8 overflow-y-auto">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b-2 border-slate-200">
            <div>
                <span class="text-xs font-black text-brand-blue tracking-wide uppercase">Menu Inventory</span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900">Kelola Menu & Profil Rasa</h1>
            </div>

            <button
                type="button"
                wire:click="openCreateModal"
                class="px-4 py-2.5 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs cursor-pointer"
            >
                <span>+ Tambah Menu Baru</span>
            </button>
        </div>

        {{-- Filters & Search --}}
        <div class="mt-6 flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="relative w-full sm:w-80">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari nama menu..."
                    class="w-full h-10 pl-9 pr-4 bg-white border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-brand-blue"
                />
                <span class="absolute left-3 top-2.5 text-slate-400 text-xs">🔍</span>
            </div>

            <div class="w-full sm:w-auto">
                @include('livewire.reuse-component.CategoryTabs')
            </div>
        </div>

        {{-- Menu Table --}}
        <div class="bg-white rounded-3xl border-2 border-slate-200 shadow-xs mt-4 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b-2 border-slate-200 text-slate-500 font-bold">
                            <th class="py-3 px-4">Menu</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Harga</th>
                            <th class="py-3 px-4">Karakter Rasa (AI Tags)</th>
                            <th class="py-3 px-4 text-center">Status Stok</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @forelse ($menus as $m)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                                            @if ($m->image_url)
                                                <img src="{{ $m->image_url }}" alt="{{ $m->name }}" class="w-full h-full object-cover"/>
                                            @else
                                                <span class="text-base">☕</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-black text-slate-900">{{ $m->name }}</div>
                                            <div class="text-[10px] text-slate-400 line-clamp-1">{{ $m->description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-600">
                                    {{ $m->category?->name ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 font-mono font-black text-brand-blue">
                                    Rp {{ number_format((float) $m->price, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach (explode(',', (string) $m->taste_profile) as $tag)
                                            @if (trim($tag) !== '')
                                                <span class="px-2 py-0.5 bg-blue-50 text-brand-blue rounded-md border border-blue-200 text-[10px] font-bold">
                                                    {{ trim($tag) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <button
                                        type="button"
                                        wire:click="toggleAvailability({{ $m->id }})"
                                        class="px-3 py-1 rounded-full text-[11px] font-bold transition cursor-pointer {{ $m->is_available ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}"
                                    >
                                        {{ $m->is_available ? '✓ Tersedia' : '✕ Habis' }}
                                    </button>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <button
                                        type="button"
                                        wire:click="deleteMenu({{ $m->id }})"
                                        wire:confirm="Yakin ingin menghapus menu {{ $m->name }}?"
                                        class="text-red-500 hover:text-red-700 font-bold text-xs cursor-pointer p-1"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada menu yang sesuai filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200">
                {{ $menus->links() }}
            </div>
        </div>
    </main>

    {{-- Modal Tambah Menu --}}
    @include('livewire.reuse-component.AdminMenuForm-modal')
</div>
