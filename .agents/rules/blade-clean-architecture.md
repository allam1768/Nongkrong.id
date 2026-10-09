# Strict Architecture Rules: Separation of Concerns & Clean Blade Views

Role: Senior Laravel Full-Stack Architect.

## 1. View Hanya untuk Presentation Logic
- File Blade (`resources/views/`) HANYA bertugas menampilkan data ke user (visual layout, styling, structure, and user interface).
- Dilarang keras menaruh Business Logic, kalkulasi kompleks, atau query database di dalam Blade.

## 2. Yang Haram / Dilarang di Blade
❌ **Query Database Langsung**:
   - DILARANG memanggil Model atau Facade DB di dalam view (contoh: `\App\Models\Menu::where(...)`, `DB::table(...)`).
   - Semua data harus disiapkan dan di-inject dari Controller atau Livewire Component.
❌ **Kalkulasi Bisnis Rumit**:
   - DILARANG menghitung diskon bertingkat, PPN/pajak, subtotal kompleks, atau formula komisi langsung di dalam Blade.
   - Kalkulasi harus diselesaikan di Service, Action, Model method, atau Controller.
❌ **Data Mutation & Side Effects**:
   - DILARANG melakukan insert, update, delete, trigger event, atau dispatch queue di dalam Blade.

## 3. Yang Diperbolehkan di Blade (Presentation Logic)
✅ **Conditional Rendering Tampilan**:
   - `@if($items->isEmpty())`, `@auth`, `@guest`, `@can(...)`.
✅ **Looping & Display**:
   - `@foreach`, `@forelse`, `@empty`.
✅ **Pemformatan Visual Ringan**:
   - Formatting tanggal (`$item->created_at->format('d M Y')`), format mata uang via helper/Blade directive jika disiapkan.
✅ **Dynamic Styling / Class**:
   - Toggle badge color, CSS class conditional based on status (`$order->status === 'completed' ? 'bg-green-500' : 'bg-amber-500'`).

## 4. Standar Refactoring & Alternatif
- **Data Terformat**: Gunakan Eloquent Accessor / Casts di Model (contoh: `$menu->formatted_price`), atau DTO / Precomputed properties di Controller / Livewire Component.
- **Tampilan Berulang / Komponen Visual**: Ekstrak menjadi Blade Component (`<x-badge>`, `<x-card>`).
- **Data Global Lintas Halaman**: Gunakan Laravel View Composer, bukan query di Blade.
