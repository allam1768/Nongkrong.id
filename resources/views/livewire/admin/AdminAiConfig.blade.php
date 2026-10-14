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
                <span class="text-xs font-black text-brand-blue tracking-wide uppercase">AI Intelligence Engine</span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900">Gemini AI Barista Tuner</h1>
            </div>

            <div class="flex items-center gap-2">
                @include('livewire.reuse-component.StatusBadge', [
                    'variant' => 'live',
                    'label' => 'API Connected',
                    'pulse' => true,
                ])
            </div>
        </div>

        {{-- Dual Column Tuning Workspace --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6 items-start">
            {{-- Left Column: Config & Persona (6 cols) --}}
            <div class="lg:col-span-6 bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-xs space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h2 class="text-sm font-black text-slate-900">Parameter Model & Persona</h2>
                    <span class="text-xs font-mono font-bold text-slate-400">v1.5-flash</span>
                </div>

                {{-- Model & Tone --}}
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <label class="block font-bold text-slate-500 mb-1">Model Engine</label>
                        <select
                            wire:model.live="model"
                            class="w-full h-10 px-2.5 bg-slate-50 border border-slate-300 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-brand-blue"
                        >
                            <option value="gemini-1.5-flash">Gemini 1.5 Flash (Ultra Fast)</option>
                            <option value="gemini-1.5-pro">Gemini 1.5 Pro (Deep Reasoning)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-500 mb-1">Gaya Bicara</label>
                        <select
                            wire:model.live="tonePersona"
                            class="w-full h-10 px-2.5 bg-slate-50 border border-slate-300 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-brand-blue"
                        >
                            <option value="friendly">Ramah & Santai (Sahabat)</option>
                            <option value="efficient">Cepat & Ringkas</option>
                            <option value="trendy">Gen-Z Trendy & Hangat</option>
                        </select>
                    </div>
                </div>

                {{-- Temperature Slider --}}
                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between font-bold">
                        <span class="text-slate-500">Creativity (Temperature)</span>
                        <span class="text-brand-blue font-mono font-black">{{ $temperature }}</span>
                    </div>
                    <input
                        type="range"
                        min="0.2"
                        max="1.0"
                        step="0.1"
                        wire:model.live="temperature"
                        class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-brand-blue"
                    />
                    <div class="flex justify-between text-[10px] text-slate-400">
                        <span>Lebih Presisi</span>
                        <span>Lebih Kreatif</span>
                    </div>
                </div>

                {{-- Upsell Strategy --}}
                <div class="space-y-1.5 text-xs">
                    <label class="block font-bold text-slate-500">Strategi Rekomendasi Menu (Upselling)</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button
                            type="button"
                            wire:click="$set('upsellStrategy', 'soft')"
                            class="py-2 rounded-xl border-2 text-[11px] font-bold transition cursor-pointer {{ $upsellStrategy === 'soft' ? 'bg-brand-blue text-white border-brand-blue' : 'bg-slate-50 text-slate-600 border-slate-200' }}"
                        >
                            Halus (Soft)
                        </button>
                        <button
                            type="button"
                            wire:click="$set('upsellStrategy', 'balanced')"
                            class="py-2 rounded-xl border-2 text-[11px] font-bold transition cursor-pointer {{ $upsellStrategy === 'balanced' ? 'bg-brand-blue text-white border-brand-blue' : 'bg-slate-50 text-slate-600 border-slate-200' }}"
                        >
                            Seimbang
                        </button>
                        <button
                            type="button"
                            wire:click="$set('upsellStrategy', 'proactive')"
                            class="py-2 rounded-xl border-2 text-[11px] font-bold transition cursor-pointer {{ $upsellStrategy === 'proactive' ? 'bg-brand-blue text-white border-brand-blue' : 'bg-slate-50 text-slate-600 border-slate-200' }}"
                        >
                            Proaktif
                        </button>
                    </div>
                </div>

                {{-- System Prompt Textarea --}}
                <div class="space-y-1.5 text-xs">
                    <label class="block font-bold text-slate-500">System Instruction Prompt</label>
                    <textarea
                        wire:model="systemPrompt"
                        rows="5"
                        class="w-full p-3 bg-slate-50 border border-slate-300 rounded-2xl font-mono text-xs text-slate-800 leading-relaxed focus:outline-none focus:border-brand-blue"
                    ></textarea>
                </div>

                <button
                    type="button"
                    wire:click="saveConfig"
                    class="w-full h-11 bg-brand-blue hover:bg-[#003da1] text-white rounded-2xl font-black text-xs transition cursor-pointer shadow-xs flex items-center justify-center gap-2"
                >
                    💾 Simpan Perubahan Prompt
                </button>
            </div>

            {{-- Right Column: Interactive Chat Simulator (6 cols) --}}
            <div class="lg:col-span-6 bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-xs flex flex-col h-[580px]">
                {{-- Simulator Header --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center text-sm">🤖</span>
                        <div>
                            <h2 class="text-xs font-black text-slate-900">Live Simulator Persona AI</h2>
                            <span class="text-[10px] text-emerald-600 font-bold">● Interactive Preview</span>
                        </div>
                    </div>

                    <button
                        type="button"
                        wire:click="resetChat"
                        class="text-xs font-bold text-slate-400 hover:text-slate-700 cursor-pointer"
                    >
                        Reset Percakapan
                    </button>
                </div>

                {{-- Chat Messages Area --}}
                <div class="flex-1 overflow-y-auto space-y-3 pr-1 text-xs">
                    @foreach ($chatMessages as $msg)
                        @if ($msg['role'] === 'user')
                            <div class="flex justify-end">
                                <div class="max-w-[80%] bg-brand-blue text-white p-3 rounded-2xl rounded-tr-xs font-medium shadow-xs">
                                    {{ $msg['message'] }}
                                </div>
                            </div>
                        @else
                            <div class="flex justify-start gap-2 items-start">
                                <span class="w-7 h-7 rounded-xl bg-slate-100 border border-slate-200 text-xs flex items-center justify-center shrink-0">
                                    ☕
                                </span>
                                <div class="max-w-[85%] bg-slate-100 text-slate-800 p-3 rounded-2xl rounded-tl-xs font-medium border border-slate-200/80 leading-relaxed">
                                    {!! nl2br(e($msg['message'])) !!}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Quick Prompts Chips --}}
                <div class="pt-3 flex gap-1.5 overflow-x-auto no-scrollbar text-[11px] font-bold">
                    <button
                        type="button"
                        wire:click="$set('testInput', 'Lagi ngantuk berat, butuh kopi yang strong!')"
                        class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg whitespace-nowrap cursor-pointer"
                    >
                        😴 Butuh Kopi Strong
                    </button>
                    <button
                        type="button"
                        wire:click="$set('testInput', 'Pengen kopi yang manis dan creamy banget')"
                        class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg whitespace-nowrap cursor-pointer"
                    >
                        🍨 Manis & Creamy
                    </button>
                    <button
                        type="button"
                        wire:click="$set('testInput', 'Ada rekomendasi minuman non-kopi yang segar?')"
                        class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg whitespace-nowrap cursor-pointer"
                    >
                        🍵 Non-Kopi Segar
                    </button>
                </div>

                {{-- Test Input Form --}}
                <form wire:submit.prevent="sendTestPrompt" class="mt-3 flex gap-2">
                    <input
                        type="text"
                        wire:model="testInput"
                        placeholder="Ketik pertanyaan untuk Barista..."
                        class="flex-1 h-10 px-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-brand-blue"
                    />
                    <button
                        type="submit"
                        class="px-4 h-10 bg-brand-blue hover:bg-[#003da1] text-white rounded-xl text-xs font-bold transition cursor-pointer shadow-xs"
                    >
                        Kirim ➔
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>
