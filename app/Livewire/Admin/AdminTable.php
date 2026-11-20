<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\HasLogout;
use App\Livewire\Concerns\RequiresAdminRole;
use App\Models\Table;
use App\Services\QrCodeService;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Manajemen Meja & Cetak QR - Admin NONGKRONG.id')]
class AdminTable extends Component
{
    use HasLogout;
    use RequiresAdminRole;

    public string $search = '';

    public string $statusFilter = 'all'; // all, available, occupied, reserved

    public bool $showFormModal = false;

    public bool $showPrintModal = false;

    // Form attributes
    public ?int $tableId = null;

    public string $tableNumber = '';

    public int $capacity = 4;

    public string $status = 'available';

    // Print Modal attributes
    public ?int $printTableId = null;

    public string $printTableNumber = '';

    public int $printCapacity = 0;

    public string $printAccessCode = '';

    public string $printQrToken = '';

    public string $printQrUrl = '';

    public ?string $toastMessage = null;

    public function openCreateModal(): void
    {
        $this->reset(['tableId', 'tableNumber', 'capacity', 'status']);
        $this->capacity = 4;
        $this->status = 'available';

        // Auto-suggest next table number
        $lastTable = Table::orderBy('id', 'desc')->first();
        if ($lastTable && is_numeric($lastTable->table_number)) {
            $this->tableNumber = sprintf('%02d', ((int) $lastTable->table_number) + 1);
        } else {
            $this->tableNumber = '0'.(Table::count() + 1);
        }

        $this->resetValidation();
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $table = Table::find($id);
        if (! $table) {
            return;
        }

        $this->tableId = $table->id;
        $this->tableNumber = $table->table_number;
        $this->capacity = $table->capacity;
        $this->status = $table->status;

        $this->resetValidation();
        $this->showFormModal = true;
    }

    public function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->resetValidation();
    }

    public function saveTable(): void
    {
        $this->validate([
            'tableNumber' => [
                'required',
                'string',
                'max:20',
                $this->tableId ? 'unique:tables,table_number,'.$this->tableId : 'unique:tables,table_number',
            ],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'status' => ['required', 'in:available,occupied,reserved,dirty'],
        ], [
            'tableNumber.required' => 'Nomor meja wajib diisi.',
            'tableNumber.unique' => 'Nomor meja ini sudah terdaftar.',
            'capacity.required' => 'Kapasitas kursi wajib diisi.',
            'capacity.min' => 'Kapasitas minimal 1 orang.',
            'capacity.max' => 'Kapasitas maksimal 50 orang.',
        ]);

        if ($this->tableId) {
            $table = Table::find($this->tableId);
            if (! $table) {
                return;
            }

            $table->update([
                'table_number' => $this->tableNumber,
                'capacity' => $this->capacity,
                'status' => $this->status,
            ]);

            $this->triggerToast("Meja {$table->table_number} berhasil diperbarui.");
        } else {
            $slug = Str::upper(Str::slug($this->tableNumber, ''));
            $randomToken = strtoupper(bin2hex(random_bytes(2)));
            $qrToken = 'QR-TBL-'.$slug.'-'.$randomToken;
            $accessCode = strtoupper(Str::random(5));

            $table = Table::create([
                'table_number' => $this->tableNumber,
                'capacity' => $this->capacity,
                'status' => $this->status,
                'qr_code_token' => $qrToken,
                'access_code' => $accessCode,
            ]);

            // Pre-generate QR code image
            QrCodeService::generateForTable($table);

            $this->triggerToast("Meja {$table->table_number} berhasil ditambahkan!");
        }

        $this->showFormModal = false;
    }

    public function toggleStatus(int $id): void
    {
        $table = Table::find($id);
        if (! $table) {
            return;
        }

        $newStatus = ($table->status === 'available') ? 'occupied' : 'available';
        $table->update(['status' => $newStatus]);

        $label = ($newStatus === 'available') ? 'Kosong' : 'Terisi';
        $this->triggerToast("Meja {$table->table_number} ditandai: {$label}");
    }

    public function deleteTable(int $id): void
    {
        $table = Table::find($id);
        if (! $table) {
            return;
        }

        $activeOrders = $table->orders()->whereIn('order_status', ['pending', 'processing', 'ready'])->count();
        if ($activeOrders > 0) {
            $this->triggerToast("Meja {$table->table_number} tidak dapat dihapus karena ada pesanan yang masih aktif.");

            return;
        }

        $num = $table->table_number;
        $table->delete();
        $this->triggerToast("Meja {$num} berhasil dihapus.");
    }

    public function openPrintModal(int $id): void
    {
        $table = Table::find($id);
        if (! $table) {
            return;
        }

        // Ensure token & access_code exist if missing
        if (empty($table->qr_code_token)) {
            $table->update([
                'qr_code_token' => 'QR-TBL-'.Str::upper(Str::slug($table->table_number, '')).'-'.strtoupper(Str::random(4)),
            ]);
        }
        if (empty($table->access_code)) {
            $table->update([
                'access_code' => strtoupper(Str::random(5)),
            ]);
        }

        $this->printTableId = $table->id;
        $this->printTableNumber = $table->table_number;
        $this->printCapacity = $table->capacity;
        $this->printAccessCode = (string) $table->access_code;
        $this->printQrToken = (string) $table->qr_code_token;
        $this->printQrUrl = QrCodeService::generateForTable($table);

        $this->showPrintModal = true;
    }

    public function closePrintModal(): void
    {
        $this->showPrintModal = false;
    }

    public function downloadStickerImage()
    {
        if (! $this->printTableId) {
            return null;
        }

        $table = Table::findOrFail($this->printTableId);
        $path = QrCodeService::generateStickerForTable($table);
        $safeName = Str::slug($table->table_number);

        return response()->download($path, "stiker-meja-{$safeName}.png");
    }

    protected function triggerToast(string $message): void
    {
        $this->toastMessage = $message;
        $this->dispatch('toast-notify');
    }

    public function render()
    {
        $query = Table::query();

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (trim($this->search) !== '') {
            $search = trim($this->search);
            $query->where(function ($q) use ($search) {
                $q->where('table_number', 'like', "%{$search}%")
                    ->orWhere('access_code', 'like', "%{$search}%")
                    ->orWhere('qr_code_token', 'like', "%{$search}%");
            });
        }

        $tables = $query->orderBy('table_number', 'asc')->get();

        return view('livewire.admin.AdminTable', [
            'tables' => $tables,
        ]);
    }
}
