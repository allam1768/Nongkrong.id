<div class="min-h-screen lg:h-screen lg:overflow-hidden bg-[#F8FAFC] flex flex-col lg:flex-row font-sans">
    {{-- Toast Notification --}}
    @include('livewire.reuse-component.Toast-notification')

    {{-- Sidebar --}}
    @include('livewire.admin.partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-5 sm:p-8 overflow-y-auto lg:h-screen">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b-2 border-slate-200">
            <div>
                <span class="text-xs font-black text-brand-blue tracking-wide uppercase">Table & QR Management</span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900">Manajemen Meja & Cetak QR</h1>
            </div>

            <button
                type="button"
                wire:click="openCreateModal"
                class="px-4 py-2.5 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs cursor-pointer"
            >
                <x-icon name="plus" class="w-4 h-4 text-white" />
                <span>Tambah Meja Baru</span>
            </button>
        </div>

        {{-- Filters & Search --}}
        <div class="mt-6 flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="relative w-full sm:w-80 flex items-center">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari nomor meja / kode akses..."
                    class="w-full h-10 pl-9 pr-4 bg-white border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-brand-blue"
                />
                <x-icon name="search" class="w-4 h-4 text-slate-400 absolute left-3 pointer-events-none" />
            </div>

            {{-- Status Filter Tabs --}}
            <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-xl border border-slate-200 w-full sm:w-auto overflow-x-auto text-xs font-bold">
                <button
                    type="button"
                    wire:click="$set('statusFilter', 'all')"
                    class="px-3 py-1.5 rounded-lg transition cursor-pointer {{ $statusFilter === 'all' ? 'bg-brand-blue text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    Semua Meja
                </button>
                <button
                    type="button"
                    wire:click="$set('statusFilter', 'available')"
                    class="px-3 py-1.5 rounded-lg transition cursor-pointer flex items-center gap-1.5 {{ $statusFilter === 'available' ? 'bg-brand-blue text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    <span class="w-2 h-2 rounded-full {{ $statusFilter === 'available' ? 'bg-white' : 'bg-emerald-500' }}"></span>
                    <span>Kosong</span>
                </button>
                <button
                    type="button"
                    wire:click="$set('statusFilter', 'occupied')"
                    class="px-3 py-1.5 rounded-lg transition cursor-pointer flex items-center gap-1.5 {{ $statusFilter === 'occupied' ? 'bg-brand-blue text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    <span class="w-2 h-2 rounded-full {{ $statusFilter === 'occupied' ? 'bg-white' : 'bg-rose-500' }}"></span>
                    <span>Terisi</span>
                </button>
                <button
                    type="button"
                    wire:click="$set('statusFilter', 'reserved')"
                    class="px-3 py-1.5 rounded-lg transition cursor-pointer flex items-center gap-1.5 {{ $statusFilter === 'reserved' ? 'bg-brand-blue text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    <span class="w-2 h-2 rounded-full {{ $statusFilter === 'reserved' ? 'bg-white' : 'bg-amber-500' }}"></span>
                    <span>Reservasi</span>
                </button>
            </div>
        </div>

        {{-- Tables Grid Cards --}}
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse ($tables as $tbl)
                @php
                    $isAvail = ($tbl->status === 'available');
                    $isOcc = ($tbl->status === 'occupied');
                    $qrImgUrl = \App\Services\QrCodeService::generateForTable($tbl);
                @endphp
                <div class="bg-white rounded-3xl border-2 border-slate-200 hover:border-slate-300 p-5 shadow-xs flex flex-col justify-between transition hover:shadow-md">
                    <div>
                        {{-- Top Card Header --}}
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div>
                                <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">MEJA KAFE</div>
                                <h3 class="text-2xl font-black text-slate-900 leading-tight">
                                    {{ $tbl->table_number }}
                                </h3>
                                <div class="flex items-center gap-1.5 mt-1 text-xs text-slate-500 font-semibold">
                                    <x-icon name="user" class="w-3.5 h-3.5 text-slate-400" />
                                    <span>Kapasitas: <strong class="text-slate-800">{{ $tbl->capacity }} Kursi</strong></span>
                                </div>
                            </div>

                            {{-- Status Pill --}}
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black inline-flex items-center gap-1.5 {{ $isAvail ? 'bg-slate-100 text-slate-700 border border-slate-200' : ($isOcc ? 'bg-blue-50 text-brand-blue border border-blue-200' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
                                <span class="w-2 h-2 rounded-full {{ $isAvail ? 'bg-emerald-500' : ($isOcc ? 'bg-brand-blue' : 'bg-amber-500') }}"></span>
                                <span>{{ $isAvail ? 'KOSONG' : ($isOcc ? 'TERISI' : 'RESERVASI') }}</span>
                            </span>
                        </div>

                        {{-- QR Preview Box & Token Info --}}
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 flex items-center gap-3.5 mb-4">
                            <div class="w-24 h-24 bg-white rounded-2xl p-2 border border-slate-200 shrink-0 flex items-center justify-center overflow-hidden shadow-2xs">
                                <img src="{{ $qrImgUrl }}" alt="QR Meja {{ $tbl->table_number }}" class="w-full h-full object-contain" />
                            </div>

                            <div class="min-w-0 flex-1 space-y-1">
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Kode Akses Manual:</div>
                                <div class="font-mono text-sm font-black text-slate-900 bg-white px-2 py-0.5 rounded-lg border border-slate-200 inline-block tracking-widest">
                                    {{ $tbl->access_code ?: '-' }}
                                </div>
                                <div class="text-[10px] text-slate-500 font-mono truncate" title="{{ $tbl->qr_code_token }}">
                                    Token: {{ $tbl->qr_code_token }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Action Footer --}}
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
                        {{-- Button Cetak Stiker --}}
                        <button
                            type="button"
                            wire:click="openPrintModal({{ $tbl->id }})"
                            class="flex-1 py-2 px-3 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer shadow-xs active:scale-98"
                        >
                            <x-icon name="qris" class="w-3.5 h-3.5 text-white" />
                            <span>Cetak Stiker QR</span>
                        </button>

                        {{-- Toggle Status Button --}}
                        <button
                            type="button"
                            wire:click="toggleStatus({{ $tbl->id }})"
                            class="py-2 px-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer"
                            title="{{ $isAvail ? 'Tandai terisi' : 'Kosongkan meja' }}"
                        >
                            {{ $isAvail ? 'Isi' : 'Kosongkan' }}
                        </button>

                        {{-- Edit Button --}}
                        <button
                            type="button"
                            wire:click="openEditModal({{ $tbl->id }})"
                            class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition cursor-pointer"
                            title="Edit data meja"
                        >
                            <x-icon name="edit" class="w-3.5 h-3.5 text-slate-700" />
                        </button>

                        {{-- Delete Button --}}
                        <button
                            type="button"
                            wire:confirm="Yakin ingin menghapus Meja {{ $tbl->table_number }}?"
                            wire:click="deleteTable({{ $tbl->id }})"
                            class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition cursor-pointer"
                            title="Hapus meja"
                        >
                            <x-icon name="trash" class="w-3.5 h-3.5 text-rose-600" />
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-12 text-center bg-white rounded-3xl border-2 border-dashed border-slate-200 text-slate-400">
                    <x-icon name="qris" class="w-12 h-12 mx-auto mb-3 text-slate-300" />
                    <p class="font-bold text-slate-600 text-sm">Tidak ada meja kafe yang cocok dengan pencarian.</p>
                    <p class="text-xs text-slate-400 mt-1">Silakan sesuaikan filter atau tambahkan meja baru.</p>
                </div>
            @endforelse
        </div>
    </main>

    {{-- MODAL TAMBAH / EDIT MEJA --}}
    @if ($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl border-2 border-slate-200 max-w-md w-full p-6 shadow-xl relative animate-in fade-in zoom-in duration-150">
                <div class="flex items-center justify-between pb-4 border-b-2 border-slate-100 mb-5">
                    <div>
                        <h2 class="text-lg font-black text-slate-900">
                            {{ $tableId ? 'Edit Meja' : 'Tambah Meja Kafe Baru' }}
                        </h2>
                        <p class="text-xs text-slate-500 font-medium">QR Token & Kode Akses akan digenerate otomatis.</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeFormModal"
                        class="p-1.5 rounded-xl hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition cursor-pointer"
                    >
                        <x-icon name="x" class="w-5 h-5 text-slate-500" />
                    </button>
                </div>

                <form wire:submit.prevent="saveTable" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor / Nama Meja</label>
                        <input
                            type="text"
                            wire:model="tableNumber"
                            placeholder="Contoh: 06, VIP-2, Outdoor-1"
                            class="w-full h-11 px-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-brand-blue"
                        />
                        @error('tableNumber')
                            <span class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kapasitas Kursi</label>
                        <input
                            type="number"
                            min="1"
                            max="50"
                            wire:model="capacity"
                            class="w-full h-11 px-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-brand-blue"
                        />
                        @error('capacity')
                            <span class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Awal</label>
                        <select
                            wire:model="status"
                            class="w-full h-11 px-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-brand-blue"
                        >
                            <option value="available">🟢 Kosong (Tersedia)</option>
                            <option value="occupied">🔴 Terisi (Occupied)</option>
                            <option value="reserved">🟡 Direservasi (Reserved)</option>
                        </select>
                        @error('status')
                            <span class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pt-3 border-t-2 border-slate-100 flex items-center justify-end gap-2">
                        <button
                            type="button"
                            wire:click="closeFormModal"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2.5 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition cursor-pointer shadow-xs"
                        >
                            {{ $tableId ? 'Simpan Perubahan' : 'Buat Meja & Generate QR' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- MODAL CETAK STIKER QR MEJA (PRINT READY) --}}
    @if ($showPrintModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-xs">
            <div class="bg-white rounded-3xl border-2 border-slate-200 max-w-sm w-full p-6 shadow-2xl relative text-center">
                {{-- Header Modal --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Preview Stiker Meja</span>
                    <button
                        type="button"
                        wire:click="closePrintModal"
                        class="p-1 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition cursor-pointer"
                    >
                        <x-icon name="x" class="w-4 h-4 text-slate-500" />
                    </button>
                </div>

                {{-- Printable Sticker Frame --}}
                <div id="printable-sticker-area" class="p-6 bg-white rounded-2xl border-4 border-slate-900 shadow-md flex flex-col items-center">
                    {{-- Logo Kafe --}}
                    <div class="flex items-center justify-center mb-2">
                        <img
                            src="{{ asset('assets/img/NongkrongId-blue.svg') }}"
                            alt="Nongkrong.id"
                            class="h-6 w-auto object-contain select-none"
                        />
                    </div>

                    {{-- Table Title --}}
                    <div class="text-2xl font-black text-slate-900 tracking-tight mb-2">
                        MEJA {{ $printTableNumber }}
                    </div>

                    {{-- QR Code Image --}}
                    <div class="w-56 h-56 p-3 bg-white rounded-2xl border-2 border-slate-900 shadow-inner flex items-center justify-center mb-3">
                        <img
                            id="sticker-preview-img"
                            src="{{ $printQrUrl }}"
                            alt="Stiker QR Meja {{ $printTableNumber }}"
                            class="w-full h-full object-contain"
                            crossorigin="anonymous"
                        />
                    </div>

                    {{-- Instruction --}}
                    <p class="text-xs font-black text-slate-900 leading-tight mb-1">
                        Pindai QR untuk Pesan & Bayar
                    </p>
                    <p class="text-[10px] text-slate-500 font-medium mb-3">
                        Arahkan kamera HP Anda ke barcode di atas
                    </p>

                    {{-- Manual Access Code Badge --}}
                    <div class="w-full py-2 px-3 bg-slate-100 rounded-xl border border-slate-300 text-center">
                        <div class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Atau masukkan 5 digit kode meja:</div>
                        <div class="font-mono text-base font-black text-slate-900 tracking-widest mt-0.5">
                            {{ $printAccessCode }}
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-5 flex items-center gap-2">
                    <button
                        type="button"
                        wire:click="closePrintModal"
                        class="flex-1 py-2.5 px-3 rounded-xl border-2 border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition cursor-pointer"
                    >
                        Tutup
                    </button>
                    <a
                        href="{{ route('admin.tables.download-sticker', $printTableId) }}"
                        download="stiker-meja-{{ $printTableNumber }}.png"
                        class="flex-1 py-2.5 px-3 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer shadow-xs active:scale-98"
                    >
                        <x-icon name="download" class="w-4 h-4 text-white" />
                        <span>Download Gambar</span>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
