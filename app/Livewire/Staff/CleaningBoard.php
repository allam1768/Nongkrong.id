<?php

namespace App\Livewire\Staff;

use App\Livewire\Concerns\HasLogout;
use App\Models\Table;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Tim Kebersihan & Manajemen Meja - NONGKRONG.id')]
class CleaningBoard extends Component
{
    use HasLogout;

    public function boot(): void
    {
        if (! auth()->check() || ! in_array(auth()->user()->role, ['cleaner', 'staff', 'admin'], true)) {
            abort(403, 'Akses tidak diizinkan untuk peran ini.');
        }
    }

    public string $activeFilter = 'all'; // all, dirty, occupied, available
    public string $search = '';
    public ?string $toastMessage = null;

    public function setFilter(string $filter): void
    {
        if (in_array($filter, ['all', 'dirty', 'occupied', 'available'], true)) {
            $this->activeFilter = $filter;
        }
    }

    public function triggerToast(string $message): void
    {
        $this->toastMessage = $message;
        $this->dispatch('toast-notify', message: $message);
        $this->dispatch('toast-alert', message: $message);
    }

    public function dismissToast(): void
    {
        $this->toastMessage = null;
    }

    public function markCleaned(int $tableId): void
    {
        $table = Table::find($tableId);
        if ($table) {
            $table->update(['status' => 'available']);
            $this->triggerToast("Meja {$table->table_number} selesai dibersihkan! Status kini KOSONG & siap ditempati.");
        }
    }

    public function markAllCleaned(): void
    {
        $count = Table::where('status', 'dirty')->count();
        if ($count > 0) {
            Table::where('status', 'dirty')->update(['status' => 'available']);
            $this->triggerToast("Semua {$count} meja kotor berhasil ditandai bersih!");
        }
    }

    public function markDirty(int $tableId): void
    {
        $table = Table::find($tableId);
        if ($table) {
            $table->update(['status' => 'dirty']);
            $this->triggerToast("Meja {$table->table_number} ditandai KOTOR. Perlu segera dibersihkan.");
        }
    }

    public function getDirtyCountProperty(): int
    {
        return Table::where('status', 'dirty')->count();
    }

    public function getAvailableCountProperty(): int
    {
        return Table::where('status', 'available')->count();
    }

    public function getOccupiedCountProperty(): int
    {
        return Table::where('status', 'occupied')->count();
    }

    public function getTotalCountProperty(): int
    {
        return Table::count();
    }

    public function render()
    {
        $query = Table::with(['orders' => function ($q) {
            $q->latest()->limit(1);
        }]);

        if ($this->activeFilter === 'dirty') {
            $query->where('status', 'dirty');
        } elseif ($this->activeFilter === 'occupied') {
            $query->where('status', 'occupied');
        } elseif ($this->activeFilter === 'available') {
            $query->where('status', 'available');
        }

        if (! empty(trim($this->search))) {
            $s = trim($this->search);
            $query->where('table_number', 'like', "%{$s}%");
        }

        // Urutkan meja kotor paling pertama agar prioritas pembersihan jelas
        $tables = $query->orderByRaw("CASE WHEN status = 'dirty' THEN 1 WHEN status = 'occupied' THEN 2 ELSE 3 END")
            ->orderBy('table_number')
            ->get();

        return view('livewire.staff.CleaningBoard', [
            'tables' => $tables,
        ]);
    }
}
