{{-- Reusable Admin Menu Create Modal Form --}}
@if ($showCreateModal)
    <div
        x-data
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
        role="dialog"
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div
            wire:click="closeCreateModal"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
        ></div>

        {{-- Dialog Box (Mobile Bottom-Sheet, Desktop Centered Modal) --}}
        <div
            class="relative w-full max-w-none sm:max-w-lg bg-white rounded-t-[28px] sm:rounded-3xl p-5 sm:p-7 shadow-2xl border-t-2 sm:border-2 border-slate-200 z-10 space-y-4 max-h-[90vh] overflow-y-auto"
            @click.stop
        >
            {{-- Mobile Drag Handle --}}
            <div class="sm:hidden w-12 h-1.5 bg-slate-200 rounded-full mx-auto -mt-1 mb-2 shrink-0"></div>

            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <span class="text-[11px] font-black text-brand-blue uppercase tracking-wider">Katalog Item</span>
                    <h3 class="text-base sm:text-lg font-black text-slate-900">Tambah Menu Baru</h3>
                </div>
                <button
                    type="button"
                    wire:click="closeCreateModal"
                    class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-colors cursor-pointer text-sm font-bold"
                    title="Tutup"
                >
                    <x-icon name="close" class="w-4 h-4" />
                </button>
            </div>

            <div class="space-y-3.5 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Menu</label>
                    <input
                        type="text"
                        wire:model="name"
                        placeholder="Contoh: Kopi Susu Creamy Aren"
                        class="w-full h-11 px-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-brand-blue focus:bg-white transition-all"
                    />
                    @error('name') <span class="text-red-500 font-bold text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                        <select
                            wire:model="categoryId"
                            class="w-full h-11 px-3 bg-slate-50 border-2 border-slate-200 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-brand-blue focus:bg-white transition-all"
                        >
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Harga (Rp)</label>
                        <input
                            type="number"
                            wire:model="price"
                            placeholder="25000"
                            class="w-full h-11 px-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl font-mono font-bold text-slate-800 focus:outline-none focus:border-brand-blue focus:bg-white transition-all"
                        />
                        @error('price') <span class="text-red-500 font-bold text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea
                        wire:model="description"
                        rows="2"
                        placeholder="Racikan kopi robusta pilihan dengan sentuhan aren lembut..."
                        class="w-full p-3 bg-slate-50 border-2 border-slate-200 rounded-xl font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-brand-blue focus:bg-white transition-all"
                    ></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Karakteristik Rasa (Pisahkan dengan koma)</label>
                    <input
                        type="text"
                        wire:model="tasteProfile"
                        placeholder="Sweet, Nutty, Creamy, Bold"
                        class="w-full h-11 px-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-brand-blue focus:bg-white transition-all"
                    />
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex flex-col-reverse sm:flex-row gap-2.5">
                <button
                    type="button"
                    wire:click="closeCreateModal"
                    class="w-full sm:flex-1 h-11 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="button"
                    wire:click="saveMenu"
                    class="w-full sm:flex-1 h-11 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition cursor-pointer shadow-xs"
                >
                    Simpan Menu
                </button>
            </div>
        </div>
    </div>
@endif
