<div class="min-h-screen bg-[#F8FAFC] text-slate-800 font-sans pb-16" wire:poll.10s>
    {{-- Notification Toast (Top Right) --}}
    @if ($toastMessage)
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => { show = false; $wire.dismissToast(); }, 3500)"
            x-show="show"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="fixed top-4 right-4 z-50 flex items-center gap-2.5 px-4 py-3 bg-slate-900 text-white text-xs font-bold rounded-2xl shadow-xl border border-slate-800"
        >
            <span class="w-2 h-2 rounded-full bg-brand-blue shrink-0"></span>
            <span>{{ $toastMessage }}</span>
            <button type="button" wire:click="dismissToast" class="ml-2 text-slate-400 hover:text-white cursor-pointer font-black" aria-label="Tutup notifikasi">✕</button>
        </div>
    @endif

    {{-- Top Navbar --}}
    <header class="bg-white border-b-2 border-slate-200 sticky top-0 z-30 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            {{-- Brand Logo & Module Indicator --}}
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('portal') }}" wire:navigate class="shrink-0 focus:outline-none">
                    <img
                        src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                        alt="Nongkrong.id"
                        class="h-7 sm:h-8 w-auto object-contain select-none"
                    />
                </a>
                <span class="hidden sm:inline-block w-px h-5 bg-slate-200"></span>
                <div class="hidden sm:flex items-center gap-2 min-w-0">
                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider bg-blue-50 text-brand-blue border border-blue-200 shrink-0">
                        Tim Kebersihan
                    </span>
                    <span class="text-xs text-slate-500 font-medium truncate">Pusat Kendali Kebersihan Meja</span>
                </div>
            </div>

            {{-- Right: User Info & Logout --}}
            <div class="flex items-center gap-3 shrink-0">
                <div class="hidden md:flex flex-col text-right">
                    <span class="text-xs font-bold text-slate-900 leading-tight">{{ auth()->user()->name ?? 'Petugas Kebersihan' }}</span>
                    <span class="text-[10px] text-slate-500 font-medium flex items-center justify-end gap-1 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Petugas Bertugas
                    </span>
                </div>

                {{-- Logout Button --}}
                <button
                    type="button"
                    wire:click="confirmLogout"
                    class="h-10 px-3.5 bg-white hover:bg-slate-50 text-slate-700 border-2 border-slate-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-2xs active:scale-95"
                    title="Keluar dari sesi"
                >
                    <x-icon name="logout" class="w-3.5 h-3.5 text-slate-500" />
                    <span>Keluar</span>
                </button>
            </div>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6">
        {{-- Priority Alert Banner --}}
        @if ($this->dirty_count > 0)
            <div class="p-4 sm:p-5 rounded-3xl bg-white border-2 border-slate-900 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start sm:items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                        <x-icon name="brush" class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-900 text-white">
                                Tindakan Diperlukan
                            </span>
                            <span class="text-sm font-black text-slate-900">{{ $this->dirty_count }} Meja Perlu Segera Dibersihkan</span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium mt-1">
                            Piring & gelas kotor harus segera diangkat dan meja dilap agar tamu baru dapat langsung duduk.
                        </p>
                    </div>
                </div>

                @if ($this->dirty_count > 1)
                    <button
                        type="button"
                        wire:click="markAllCleaned"
                        wire:confirm="Tandai semua {{ $this->dirty_count }} meja kotor sebagai bersih dan siap ditempati?"
                        class="px-4 py-2.5 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl transition cursor-pointer shrink-0 flex items-center justify-center gap-2 active:scale-98"
                    >
                        <x-icon name="check" class="w-4 h-4 text-white" />
                        <span>Selesaikan Semua ({{ $this->dirty_count }})</span>
                    </button>
                @endif
            </div>
        @else
            <div class="p-4 sm:p-5 rounded-3xl bg-white border-2 border-slate-200 shadow-2xs flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center shrink-0">
                    <x-icon name="check" class="w-5 h-5 text-slate-800" />
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">Semua Meja Bersih & Higienis</h4>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                        Tidak ada antrean meja kotor saat ini. Seluruh meja kafe siap melayani tamu.
                    </p>
                </div>
            </div>
        @endif

        {{-- 4 Stat Metric Cards (Clean, Solid, No Gradients) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            {{-- Card 1: Perlu Dibersihkan --}}
            <div class="p-4 sm:p-5 rounded-3xl bg-white border-2 {{ $this->dirty_count > 0 ? 'border-slate-900 shadow-xs' : 'border-slate-200 shadow-2xs' }} flex flex-col justify-between transition">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Perlu Dibersihkan</span>
                    <div class="w-8 h-8 rounded-xl {{ $this->dirty_count > 0 ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 border border-slate-200' }} flex items-center justify-center">
                        <x-icon name="brush" class="w-4 h-4 text-current" />
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-slate-900 font-mono leading-none">{{ $this->dirty_count }}</span>
                    <span class="text-xs font-bold text-slate-400">Meja</span>
                </div>
                <div class="mt-2 text-[11px] font-bold {{ $this->dirty_count > 0 ? 'text-slate-900' : 'text-slate-500' }}">
                    {{ $this->dirty_count > 0 ? 'Perlu tindakan segera' : 'Semua meja bersih' }}
                </div>
            </div>

            {{-- Card 2: Bersih & Kosong --}}
            <div class="p-4 sm:p-5 rounded-3xl bg-white border-2 border-slate-200 shadow-2xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Bersih & Kosong</span>
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center">
                        <x-icon name="check" class="w-4 h-4 text-slate-700" />
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-slate-900 font-mono leading-none">{{ $this->available_count }}</span>
                    <span class="text-xs font-bold text-slate-400">Meja</span>
                </div>
                <div class="mt-2 text-[11px] text-slate-500 font-medium">
                    Siap digunakan tamu baru
                </div>
            </div>

            {{-- Card 3: Sedang Terisi --}}
            <div class="p-4 sm:p-5 rounded-3xl bg-white border-2 border-slate-200 shadow-2xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Sedang Terisi</span>
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center">
                        <x-icon name="user" class="w-4 h-4 text-slate-700" />
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-slate-900 font-mono leading-none">{{ $this->occupied_count }}</span>
                    <span class="text-xs font-bold text-slate-400">Meja</span>
                </div>
                <div class="mt-2 text-[11px] text-slate-500 font-medium">
                    Tamu sedang dine-in
                </div>
            </div>

            {{-- Card 4: Total Meja --}}
            <div class="p-4 sm:p-5 rounded-3xl bg-white border-2 border-slate-200 shadow-2xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total Meja Kafe</span>
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center">
                        <x-icon name="coffee" class="w-4 h-4 text-slate-700" />
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-slate-900 font-mono leading-none">{{ $this->total_count }}</span>
                    <span class="text-xs font-bold text-slate-400">Meja</span>
                </div>
                <div class="mt-2 text-[11px] text-slate-500 font-medium">
                    Kapasitas fisik kafe
                </div>
            </div>
        </div>

        {{-- Filter Tabs & Search Toolbar --}}
        <div class="bg-white p-3.5 sm:p-4 rounded-3xl border-2 border-slate-200 shadow-2xs flex flex-col md:flex-row items-center justify-between gap-3">
            {{-- Filter Pills --}}
            <div class="flex flex-wrap items-center gap-1.5 w-full md:w-auto">
                <button
                    type="button"
                    wire:click="setFilter('all')"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition cursor-pointer {{ $activeFilter === 'all' ? 'bg-brand-blue text-white shadow-xs' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200' }}"
                >
                    Semua ({{ $this->total_count }})
                </button>
                <button
                    type="button"
                    wire:click="setFilter('dirty')"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer {{ $activeFilter === 'dirty' ? 'bg-brand-blue text-white shadow-xs' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200' }}"
                >
                    <span class="w-2 h-2 rounded-full {{ $activeFilter === 'dirty' ? 'bg-white' : 'bg-slate-900' }}"></span>
                    <span>Perlu Dibersihkan ({{ $this->dirty_count }})</span>
                </button>
                <button
                    type="button"
                    wire:click="setFilter('available')"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer {{ $activeFilter === 'available' ? 'bg-brand-blue text-white shadow-xs' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200' }}"
                >
                    <span class="w-2 h-2 rounded-full {{ $activeFilter === 'available' ? 'bg-white' : 'bg-slate-400' }}"></span>
                    <span>Bersih & Kosong ({{ $this->available_count }})</span>
                </button>
                <button
                    type="button"
                    wire:click="setFilter('occupied')"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer {{ $activeFilter === 'occupied' ? 'bg-brand-blue text-white shadow-xs' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200' }}"
                >
                    <span class="w-2 h-2 rounded-full {{ $activeFilter === 'occupied' ? 'bg-white' : 'bg-brand-blue' }}"></span>
                    <span>Sedang Terisi ({{ $this->occupied_count }})</span>
                </button>
            </div>

            {{-- Quick Search --}}
            <div class="w-full md:w-72 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <x-icon name="search" class="w-4 h-4 text-slate-400" />
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.250ms="search"
                    placeholder="Cari nomor meja (01, VIP-1)..."
                    class="w-full h-11 pl-10 pr-4 bg-white border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-brand-blue transition shadow-2xs"
                />
            </div>
        </div>

        {{-- Table Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4.5">
            @forelse ($tables as $tbl)
                @php
                    $isDirty = ($tbl->status === 'dirty');
                    $isOccupied = ($tbl->status === 'occupied');
                    $isAvail = ($tbl->status === 'available');
                    $latestOrder = $tbl->orders->first();
                @endphp

                <div class="p-5 sm:p-6 rounded-3xl bg-white border-2 {{ $isDirty ? 'border-slate-900 shadow-sm' : 'border-slate-200 hover:border-slate-300 shadow-2xs' }} transition relative flex flex-col justify-between">
                    <div>
                        {{-- Top Header Card: Table Number & Status Pill --}}
                        <div class="flex items-start justify-between gap-3 mb-4 pb-4 border-b border-slate-100">
                            <div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Meja Kafe</span>
                                <h3 class="text-3xl font-black text-slate-900 font-mono tracking-tight leading-none">
                                    MEJA {{ $tbl->table_number }}
                                </h3>
                                <div class="flex items-center gap-1.5 mt-1.5 text-xs text-slate-500 font-semibold">
                                    <x-icon name="user" class="w-3.5 h-3.5 text-slate-400 shrink-0" />
                                    <span>Kapasitas: <strong class="text-slate-800">{{ $tbl->capacity }} Kursi</strong></span>
                                </div>
                            </div>

                            {{-- High Contrast Solid Status Badge --}}
                            @if ($isDirty)
                                <span class="px-3 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-slate-900 text-white inline-flex items-center gap-1.5 shrink-0 shadow-2xs">
                                    <x-icon name="brush" class="w-3.5 h-3.5 text-white shrink-0" />
                                    <span>KOTOR</span>
                                </span>
                            @elseif ($isOccupied)
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-50 text-brand-blue border border-blue-200 inline-flex items-center gap-1.5 shrink-0">
                                    <span class="w-2 h-2 rounded-full bg-brand-blue"></span>
                                    <span>TERISI</span>
                                </span>
                            @elseif ($isAvail)
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200 inline-flex items-center gap-1.5 shrink-0">
                                    <x-icon name="check" class="w-3.5 h-3.5 text-slate-500 shrink-0" />
                                    <span>BERSIH</span>
                                </span>
                            @else
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200 inline-flex items-center gap-1.5 shrink-0">
                                    <span>RESERVASI</span>
                                </span>
                            @endif
                        </div>

                        {{-- Metadata Context Box --}}
                        <div class="mb-4">
                            @if ($isDirty)
                                <div class="p-3.5 bg-slate-100 border border-slate-300 rounded-2xl text-xs text-slate-800 space-y-1">
                                    <div class="font-bold text-xs text-slate-900 flex items-center gap-1.5">
                                        <x-icon name="brush" class="w-3.5 h-3.5 text-slate-700" />
                                        <span>Perlu Segera Dibersihkan</span>
                                    </div>
                                    <p class="text-[11px] text-slate-600 font-medium leading-relaxed">
                                        Piring & gelas kotor perlu diangkat. Lap meja dan rapikan posisi kursi agar siap dipakai.
                                    </p>
                                </div>
                            @elseif ($isOccupied)
                                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-700 space-y-1">
                                    <div class="font-bold text-xs text-slate-900 flex items-center gap-1.5">
                                        <x-icon name="user" class="w-3.5 h-3.5 text-slate-500" />
                                        <span>Tamu Sedang Menempati Meja</span>
                                    </div>
                                    <div class="text-[11px] text-slate-600 font-medium">
                                        @if ($latestOrder)
                                            Tamu: <strong class="text-slate-800">{{ $latestOrder->customer_name }}</strong> &bull; <span class="font-mono text-slate-500">{{ $latestOrder->order_code }}</span>
                                        @else
                                            Pelanggan sedang di meja
                                        @endif
                                    </div>
                                </div>
                            @elseif ($isAvail)
                                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-600 flex items-center gap-2.5">
                                    <x-icon name="check" class="w-4 h-4 text-slate-500 shrink-0" />
                                    <span class="text-[11px] font-medium">Meja higienis dan siap ditempati tamu baru kapan saja.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Card Action Footer --}}
                    <div class="pt-3 border-t border-slate-100">
                        @if ($isDirty)
                            {{-- Primary Action: Clean This Table --}}
                            <button
                                type="button"
                                wire:click="markCleaned({{ $tbl->id }})"
                                class="w-full h-12 px-4 rounded-2xl bg-brand-blue hover:bg-[#003da1] text-white font-black text-xs transition shadow-xs flex items-center justify-center gap-2 cursor-pointer active:scale-98"
                            >
                                <x-icon name="check" class="w-4 h-4 text-white" />
                                <span>TANDAI SELESAI DIBERSIHKAN</span>
                            </button>
                        @elseif ($isOccupied)
                            {{-- Occupied: Guest Left -> Mark Dirty or Clear --}}
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    wire:click="markDirty({{ $tbl->id }})"
                                    class="flex-1 h-11 px-3 rounded-2xl bg-slate-900 hover:bg-black text-white font-bold text-xs transition shadow-2xs flex items-center justify-center gap-1.5 cursor-pointer active:scale-98"
                                    title="Tamu telah pergi, tandai meja kotor"
                                >
                                    <x-icon name="brush" class="w-3.5 h-3.5 text-white" />
                                    <span>Tamu Selesai (Kotor)</span>
                                </button>
                                <button
                                    type="button"
                                    wire:click="markCleaned({{ $tbl->id }})"
                                    class="h-11 px-4 rounded-2xl bg-white hover:bg-slate-50 border-2 border-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer active:scale-98"
                                    title="Langsung kosongkan jika meja sudah bersih"
                                >
                                    Kosongkan
                                </button>
                            </div>
                        @else
                            {{-- Available: Mark Dirty only if accidental spill or re-cleaning needed --}}
                            <button
                                type="button"
                                wire:click="markDirty({{ $tbl->id }})"
                                class="w-full h-10 px-3 rounded-2xl bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 font-bold text-xs transition flex items-center justify-center gap-1.5 cursor-pointer active:scale-98"
                                title="Tandai kotor jika ada tumpahan atau kotoran baru"
                            >
                                <x-icon name="brush" class="w-3.5 h-3.5 text-slate-400" />
                                <span>Tandai Kotor (Ada Tumpahan)</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full p-12 text-center bg-white rounded-3xl border-2 border-slate-200 space-y-2">
                    <x-icon name="coffee" class="w-8 h-8 text-slate-400 mx-auto" />
                    <h3 class="text-base font-black text-slate-800">Tidak ada meja yang cocok</h3>
                    <p class="text-xs text-slate-500">Coba sesuaikan filter status atau kata kunci pencarian nomor meja.</p>
                </div>
            @endforelse
        </div>
    </main>

    {{-- Logout Confirmation Modal --}}
    @if ($showLogoutModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-150">
            <div class="bg-white rounded-3xl border-2 border-slate-200 max-w-sm w-full p-6 shadow-2xl space-y-4 animate-in zoom-in-95 duration-150">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center mx-auto">
                    <x-icon name="alert-circle" class="w-6 h-6 text-slate-800" />
                </div>
                <div class="text-center space-y-1">
                    <h3 class="text-base font-black text-slate-900">Konfirmasi Keluar</h3>
                    <p class="text-xs text-slate-500">Apakah Anda yakin ingin keluar dari portal Tim Kebersihan?</p>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <button
                        type="button"
                        wire:click="cancelLogout"
                        class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        wire:click="logout"
                        class="flex-1 py-2.5 rounded-xl bg-brand-blue hover:bg-[#003da1] text-white font-black text-xs transition shadow-xs cursor-pointer"
                    >
                        Ya, Keluar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
