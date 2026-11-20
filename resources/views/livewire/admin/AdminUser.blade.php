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
                <span class="text-xs font-black text-brand-blue tracking-wide uppercase">Employee & Access Management</span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900">Kelola Akun Karyawan & Peran</h1>
                <p class="text-xs text-slate-500 mt-1">Daftar hak akses seluruh peran operasional: Kasir, Dapur, Tim Pembersih, dan Administrator.</p>
            </div>

            <button
                type="button"
                wire:click="openCreateModal"
                class="px-4 py-2.5 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs cursor-pointer self-start sm:self-auto"
            >
                <x-icon name="plus" class="w-4 h-4 text-white" />
                <span>Tambah Karyawan Baru</span>
            </button>
        </div>

        {{-- Role Summary Pills --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
            <div class="p-3.5 bg-white rounded-2xl border-2 border-slate-200 shadow-2xs">
                <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total Akun</div>
                <div class="text-2xl font-black text-slate-900 mt-1">{{ $roleCounts['total'] }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5">Semua peran terdaftar</div>
            </div>
            <div class="p-3.5 bg-white rounded-2xl border-2 border-slate-200 shadow-2xs">
                <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Kasir (POS)</div>
                <div class="text-2xl font-black text-brand-blue mt-1">{{ $roleCounts['cashier'] }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5">Front-of-house kasir</div>
            </div>
            <div class="p-3.5 bg-white rounded-2xl border-2 border-slate-200 shadow-2xs">
                <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Dapur & Barista</div>
                <div class="text-2xl font-black text-slate-900 mt-1">{{ $roleCounts['kitchen'] }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5">Proses pesanan masuk</div>
            </div>
            <div class="p-3.5 bg-white rounded-2xl border-2 border-slate-200 shadow-2xs">
                <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Tim Pembersih</div>
                <div class="text-2xl font-black text-slate-900 mt-1">{{ $roleCounts['cleaner'] }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5">Pantau & bersihkan meja</div>
            </div>
        </div>

        {{-- Filters & Search --}}
        <div class="mt-6 flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="relative w-full sm:w-80 flex items-center">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari nama atau email..."
                    class="w-full pl-10 pr-4 py-2 bg-white border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-brand-blue transition shadow-2xs"
                />
                <div class="absolute left-3 text-slate-400 pointer-events-none">
                    <x-icon name="search" class="w-4 h-4" />
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-1.5 w-full sm:w-auto">
                <button
                    type="button"
                    wire:click="$set('roleFilter', 'all')"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $roleFilter === 'all' ? 'bg-brand-blue text-white shadow-xs' : 'bg-white hover:bg-slate-100 text-slate-600 border border-slate-200' }}"
                >
                    Semua
                </button>
                <button
                    type="button"
                    wire:click="$set('roleFilter', 'cashier')"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $roleFilter === 'cashier' ? 'bg-brand-blue text-white shadow-xs' : 'bg-white hover:bg-slate-100 text-slate-600 border border-slate-200' }}"
                >
                    Kasir
                </button>
                <button
                    type="button"
                    wire:click="$set('roleFilter', 'kitchen')"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $roleFilter === 'kitchen' ? 'bg-brand-blue text-white shadow-xs' : 'bg-white hover:bg-slate-100 text-slate-600 border border-slate-200' }}"
                >
                    Dapur
                </button>
                <button
                    type="button"
                    wire:click="$set('roleFilter', 'cleaner')"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $roleFilter === 'cleaner' ? 'bg-brand-blue text-white shadow-xs' : 'bg-white hover:bg-slate-100 text-slate-600 border border-slate-200' }}"
                >
                    Pembersih
                </button>
                <button
                    type="button"
                    wire:click="$set('roleFilter', 'admin')"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $roleFilter === 'admin' ? 'bg-brand-blue text-white shadow-xs' : 'bg-white hover:bg-slate-100 text-slate-600 border border-slate-200' }}"
                >
                    Admin
                </button>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="mt-6 bg-white rounded-3xl border-2 border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-slate-100 bg-slate-50/75 text-[11px] font-black text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-5">Nama Karyawan</th>
                            <th class="py-3.5 px-5">Email Akses</th>
                            <th class="py-3.5 px-5">Peran / Role</th>
                            <th class="py-3.5 px-5">Ruang Lingkup Akses</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse ($users as $u)
                            @php
                                $roleBadge = match ($u->role) {
                                    'admin' => ['label' => 'Administrator', 'color' => 'bg-blue-50 text-brand-blue border-blue-200', 'desc' => 'Akses Penuh Semua Modul & Pengaturan'],
                                    'cashier' => ['label' => 'Kasir (POS)', 'color' => 'bg-blue-50 text-brand-blue border-blue-200', 'desc' => 'Menu POS Walk-in, Cek & Alokasi Meja'],
                                    'kitchen' => ['label' => 'Tim Dapur', 'color' => 'bg-slate-100 text-slate-700 border-slate-200', 'desc' => 'Kitchen Kanban Board (Proses Pesanan Masuk)'],
                                    'barista' => ['label' => 'Barista', 'color' => 'bg-slate-100 text-slate-700 border-slate-200', 'desc' => 'Kitchen Kanban Board (Racik Minuman)'],
                                    'cleaner' => ['label' => 'Tim Pembersih', 'color' => 'bg-slate-100 text-slate-700 border-slate-200', 'desc' => 'Dashboard Meja Kotor & Update Status Bersih'],
                                    default => ['label' => 'Staf Umum', 'color' => 'bg-slate-100 text-slate-700 border-slate-200', 'desc' => 'Akses Operasional Dasar'],
                                };
                                $isSelf = (int) auth()->id() === (int) $u->id;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition group">
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-black text-slate-700 uppercase">
                                            {{ substr($u->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-black text-slate-900 flex items-center gap-1.5">
                                                <span>{{ $u->name }}</span>
                                                @if ($isSelf)
                                                    <span class="px-1.5 py-0.2 rounded-md bg-blue-100 text-brand-blue text-[10px] font-black border border-blue-200">Anda</span>
                                                @endif
                                            </div>
                                            <div class="text-[11px] text-slate-400 font-mono">ID: #{{ str_pad($u->id, 3, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-5 font-mono text-slate-700">
                                    {{ $u->email }}
                                </td>
                                <td class="py-4 px-5">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black inline-flex items-center gap-1.5 border {{ $roleBadge['color'] }}">
                                        {{ $roleBadge['label'] }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-slate-500 font-medium text-[11px]">
                                    {{ $roleBadge['desc'] }}
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button
                                            type="button"
                                            wire:click="openEditModal({{ $u->id }})"
                                            class="p-2 rounded-xl bg-slate-100 hover:bg-brand-blue hover:text-white text-slate-600 transition cursor-pointer"
                                            title="Edit akun"
                                        >
                                            <x-icon name="edit" class="w-3.5 h-3.5" />
                                        </button>
                                        @if (!$isSelf)
                                            <button
                                                type="button"
                                                wire:click="confirmDelete({{ $u->id }})"
                                                class="p-2 rounded-xl bg-slate-100 hover:bg-rose-600 hover:text-white text-slate-600 transition cursor-pointer"
                                                title="Hapus akun"
                                            >
                                                <x-icon name="trash" class="w-3.5 h-3.5" />
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <div class="text-3xl mb-2">👥</div>
                                    <div class="font-bold text-sm text-slate-700">Tidak ada akun ditemukan</div>
                                    <div class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter peran.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- Form Modal (Create / Edit) --}}
    @if ($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-150">
            <div class="bg-white rounded-3xl border-2 border-slate-200 max-w-md w-full p-6 shadow-2xl space-y-5 animate-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-3 border-b-2 border-slate-100">
                    <h3 class="text-base font-black text-slate-900">
                        {{ $userId ? 'Edit Akun Karyawan' : 'Tambah Karyawan Baru' }}
                    </h3>
                    <button
                        type="button"
                        wire:click="closeFormModal"
                        class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-black cursor-pointer text-sm"
                    >
                        ✕
                    </button>
                </div>

                <form wire:submit="save" class="space-y-4 text-xs font-bold">
                    {{-- Nama Karyawan --}}
                    <div>
                        <label class="block text-slate-700 mb-1">Nama Lengkap</label>
                        <input
                            type="text"
                            wire:model="name"
                            placeholder="Contoh: Rina Kasir / Joko Dapur"
                            class="w-full px-3.5 py-2.5 rounded-xl border-2 border-slate-200 focus:outline-none focus:border-brand-blue text-xs font-bold text-slate-800"
                        />
                        @error('name') <span class="text-rose-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-slate-700 mb-1">Alamat Email (Untuk Masuk)</label>
                        <input
                            type="email"
                            wire:model="email"
                            placeholder="nama@smartcafe.id"
                            class="w-full px-3.5 py-2.5 rounded-xl border-2 border-slate-200 focus:outline-none focus:border-brand-blue text-xs font-bold text-slate-800"
                        />
                        @error('email') <span class="text-rose-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Peran / Role --}}
                    <div>
                        <label class="block text-slate-700 mb-1">Peran / Hak Akses</label>
                        <select
                            wire:model="role"
                            class="w-full px-3.5 py-2.5 rounded-xl border-2 border-slate-200 focus:outline-none focus:border-brand-blue text-xs font-bold text-slate-800 bg-white"
                        >
                            <option value="cashier">Kasir (Front-of-House / POS Walk-in)</option>
                            <option value="kitchen">Tim Dapur (Kitchen Display Board)</option>
                            <option value="barista">Barista (Kitchen Display Board)</option>
                            <option value="cleaner">Tim Pembersih (Dashboard Status Meja)</option>
                            <option value="admin">Administrator (Akses Penuh Seluruh Modul)</option>
                        </select>
                        @error('role') <span class="text-rose-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-slate-700 mb-1">
                            Kata Sandi
                            @if ($userId)
                                <span class="text-slate-400 font-normal">(Kosongkan jika tidak ingin mengubah)</span>
                            @endif
                        </label>
                        <input
                            type="password"
                            wire:model="password"
                            placeholder="Minimal 6 karakter"
                            class="w-full px-3.5 py-2.5 rounded-xl border-2 border-slate-200 focus:outline-none focus:border-brand-blue text-xs font-bold text-slate-800"
                        />
                        @error('password') <span class="text-rose-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex items-center justify-end gap-2 pt-3 border-t-2 border-slate-100">
                        <button
                            type="button"
                            wire:click="closeFormModal"
                            class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-xl bg-brand-blue hover:bg-[#003da1] text-white font-black text-xs transition shadow-xs cursor-pointer"
                        >
                            {{ $userId ? 'Simpan Perubahan' : 'Tambah Karyawan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-150">
            <div class="bg-white rounded-3xl border-2 border-slate-200 max-w-sm w-full p-6 shadow-2xl space-y-4 animate-in zoom-in-95 duration-150">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center font-black mx-auto text-xl">
                    ⚠️
                </div>
                <div class="text-center space-y-1">
                    <h3 class="text-base font-black text-slate-900">Hapus Akun Karyawan?</h3>
                    <p class="text-xs text-slate-500">
                        Akun <strong>{{ $deleteUserName }}</strong> akan dihapus permanen dari sistem dan tidak dapat lagi masuk ke kafe.
                    </p>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <button
                        type="button"
                        wire:click="cancelDelete"
                        class="flex-1 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        wire:click="deleteUser"
                        class="flex-1 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-black text-xs transition shadow-xs cursor-pointer"
                    >
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
