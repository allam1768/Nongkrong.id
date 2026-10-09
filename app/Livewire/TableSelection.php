<?php

namespace App\Livewire;

use App\Models\Table;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Pilih Meja')]
class TableSelection extends Component
{
    /**
     * Customer name passed from Portal or session.
     */
    public string $customerName = '';

    /**
     * 5-digit table code entered by customer.
     */
    public string $tableCode = '';

    /**
     * Array of 5 individual digits for input boxes.
     *
     * @var array<int, string>
     */
    public array $codeDigits = ['', '', '', '', ''];

    /**
     * Selected table ID if valid.
     */
    public ?int $selectedTableId = null;

    /**
     * Selected table number string.
     */
    public ?string $selectedTableNumber = null;

    /**
     * Selected table capacity.
     */
    public ?int $selectedTableCapacity = null;

    /**
     * Selected table status.
     */
    public ?string $selectedTableStatus = null;

    /**
     * Selected table access code.
     */
    public ?string $selectedTableAccessCode = null;

    /**
     * Controls success confirmation modal visibility.
     */
    public bool $showSuccessModal = false;

    /**
     * Controls QR camera scanner modal visibility.
     */
    public bool $showScannerModal = false;

    /**
     * Feedback or informative message.
     */
    public ?string $statusMessage = null;

    /**
     * Initialize component state from session.
     */
    public function mount(): void
    {
        $sessionName = (string) session('customer_name', '');
        $this->customerName = ($sessionName === 'Tamu') ? '' : $sessionName;

        // Check if a table was previously selected in session
        if (session()->has('table_id') && session()->has('table_number')) {
            $this->selectedTableId = (int) session('table_id');
            $this->selectedTableNumber = (string) session('table_number');
            $this->selectedTableAccessCode = session('access_code') ? (string) session('access_code') : null;
        }
    }

    /**
     * Confirm the entered 5-digit table code.
     */
    public function confirmTableCode(): void
    {
        // Assemble code from digits array if tableCode is empty
        $code = trim($this->tableCode);
        if (empty($code)) {
            $code = trim(implode('', $this->codeDigits));
        }

        $this->tableCode = strtoupper($code);

        $this->validate([
            'tableCode' => ['required', 'string', 'min:1', 'max:15'],
        ], [
            'tableCode.required' => 'Masukkan 5 digit kode meja Anda.',
            'tableCode.min' => 'Kode meja minimal 1 karakter.',
            'tableCode.max' => 'Kode meja maksimal 15 karakter.',
        ]);

        $table = $this->resolveTable($this->tableCode);

        if (! $table) {
            $this->addError('tableCode', 'Kode meja tidak ditemukan. Periksa kembali kode pada stiker meja Anda.');

            return;
        }

        if ($table->status === 'occupied') {
            $this->addError('tableCode', "Meja {$table->table_number} saat ini sedang terisi (Occupied). Silakan pilih meja lain.");

            return;
        }

        if ($table->status === 'reserved') {
            $this->addError('tableCode', "Meja {$table->table_number} saat ini telah direservasi. Silakan hubungi kasir atau pilih meja lain.");

            return;
        }

        $this->applySelectedTable($table);
    }

    /**
     * Scan or select a table directly via QR code token or table number.
     */
    public function scanQrTable(string $tokenOrNumber): void
    {
        $table = $this->resolveTable($tokenOrNumber);

        if (! $table) {
            $this->addError('tableCode', 'QR Code meja tidak valid atau tidak terdaftar.');

            return;
        }

        if ($table->status === 'occupied') {
            $this->addError('tableCode', "Meja {$table->table_number} saat ini sedang terisi.");

            return;
        }

        if ($table->status === 'reserved') {
            $this->addError('tableCode', "Meja {$table->table_number} telah direservasi.");

            return;
        }

        $this->showScannerModal = false;
        $this->applySelectedTable($table);
    }

    /**
     * Quick-fill a table number for testing or quick access.
     */
    public function quickFillTable(string $tableNumber): void
    {
        $table = Table::where('table_number', $tableNumber)->first();
        $code = ($table && ! empty($table->access_code)) ? (string) $table->access_code : str_pad($tableNumber, 5, '0', STR_PAD_LEFT);

        $this->tableCode = strtoupper($code);
        $this->codeDigits = str_split(str_pad(substr($this->tableCode, 0, 5), 5, ' '));
        $this->resetValidation();
        $this->confirmTableCode();
    }

    /**
     * Open camera scanner modal dialog.
     */
    public function openScanner(): void
    {
        $this->showScannerModal = true;
        $this->resetValidation();
    }

    /**
     * Close camera scanner modal dialog.
     */
    public function closeScanner(): void
    {
        $this->showScannerModal = false;
    }

    /**
     * Validate customer name, commit to session, and proceed to menu ordering.
     */
    public function proceedToOrder(): void
    {
        $this->validate([
            'customerName' => ['required', 'string', 'min:2', 'max:50'],
        ], [
            'customerName.required' => 'Silakan masukkan nama Anda sebelum lanjut ke menu pemesanan.',
            'customerName.min' => 'Nama minimal 2 karakter.',
            'customerName.max' => 'Nama maksimal 50 karakter.',
        ]);

        $sanitizedName = trim($this->customerName);
        session([
            'customer_name' => $sanitizedName,
            'role' => 'customer',
        ]);

        $this->showSuccessModal = false;

        $this->redirectRoute('customer.order', navigate: true);
    }

    /**
     * Close success confirmation modal dialog and proceed to menu ordering.
     */
    public function closeSuccessModal(): void
    {
        $this->proceedToOrder();
    }

    /**
     * Change customer name and return to portal.
     */
    public function changeName(): void
    {
        session()->forget(['table_id', 'table_number', 'access_code']);

        $this->redirectRoute('portal', navigate: true);
    }

    /**
     * Reset table selection to pick another table.
     */
    public function resetTable(): void
    {
        session()->forget(['table_id', 'table_number', 'access_code']);
        $this->selectedTableId = null;
        $this->selectedTableNumber = null;
        $this->selectedTableStatus = null;
        $this->selectedTableCapacity = null;
        $this->selectedTableAccessCode = null;
        $this->tableCode = '';
        $this->codeDigits = ['', '', '', '', ''];
        $this->showSuccessModal = false;
        $this->resetValidation();
    }

    /**
     * Resolve Table model from various code patterns.
     */
    protected function resolveTable(string $input): ?Table
    {
        $input = trim($input);
        if (empty($input)) {
            return null;
        }

        $upper = strtoupper($input);

        // 1. Direct match on access_code
        $table = Table::whereRaw('UPPER(access_code) = ?', [$upper])->first();
        if ($table) {
            return $table;
        }

        // 2. Direct match on table_number
        $table = Table::where('table_number', $input)->first();
        if ($table) {
            return $table;
        }

        // 3. Exact match on qr_code_token
        $table = Table::where('qr_code_token', $input)->first();
        if ($table) {
            return $table;
        }

        // 4. Numeric variations (e.g., '00001' -> '01' or '1')
        if (is_numeric($input)) {
            $num = (int) $input;
            $twoDigits = sprintf('%02d', $num);
            $table = Table::where('table_number', $twoDigits)
                ->orWhere('table_number', (string) $num)
                ->first();

            if ($table) {
                return $table;
            }
        }

        // 5. Partial token match (e.g., 'A9X1' in 'QR-TBL-01-A9X1')
        $table = Table::where('qr_code_token', 'like', "%{$input}%")->first();
        if ($table) {
            return $table;
        }

        // 6. Case-insensitive table_number search
        return Table::whereRaw('LOWER(table_number) = ?', [strtolower($input)])->first();
    }

    /**
     * Commit table selection to session and component state.
     */
    protected function applySelectedTable(Table $table): void
    {
        session([
            'table_id' => $table->id,
            'table_number' => $table->table_number,
            'access_code' => $table->access_code,
        ]);

        $this->selectedTableId = $table->id;
        $this->selectedTableNumber = $table->table_number;
        $this->selectedTableStatus = $table->status;
        $this->selectedTableCapacity = $table->capacity;
        $this->selectedTableAccessCode = $table->access_code;
        $this->showSuccessModal = true;
        $this->resetValidation();
    }

    /**
     * Render the Livewire view.
     */
    public function render()
    {
        $availableTables = Table::select('id', 'table_number', 'capacity', 'status', 'qr_code_token', 'access_code')
            ->where('status', 'available')
            ->orderBy('table_number')
            ->get();

        return view('livewire.TableSelection', [
            'availableTables' => $availableTables,
            'tables' => $availableTables,
            'currentYear' => date('Y'),
        ]);
    }
}
