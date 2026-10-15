<?php

namespace App\Livewire\Login;

use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Pilih Akses')]
class Portal extends Component
{
    /**
     * Active role selection hover or state.
     */
    public ?string $selectedRole = null;

    /**
     * Controls mobile table selection modal visibility.
     */
    public bool $showTableModal = false;

    /**
     * Controls quick order modal visibility.
     */
    public bool $showQuickOrderModal = false;

    /**
     * Controls employee login modal visibility.
     */
    public bool $showLoginModal = false;

    /**
     * 5-digit table code entered by customer in mobile modal.
     */
    public string $tableCode = '';

    /**
     * Selected table ID if valid.
     */
    public ?int $selectedTableId = null;

    /**
     * Selected table number string.
     */
    public ?string $selectedTableNumber = null;

    /**
     * Selected table access code.
     */
    public ?string $selectedTableAccessCode = null;

    /**
     * Controls QR camera scanner modal visibility in mobile modal.
     */
    public bool $showScannerModal = false;

    /**
     * Customer name input value.
     */
    public string $customerName = '';

    /**
     * Employee login username or email.
     */
    public string $loginUsername = '';

    /**
     * Employee login password.
     */
    public string $loginPassword = '';

    /**
     * Customer name recorded after submission.
     */
    public ?string $submittedName = null;

    /**
     * Select a role and trigger appropriate action.
     */
    public function setRole(string $role): void
    {
        $this->selectedRole = $role;

        if ($role === 'customer') {
            $this->openTableModal();
        } elseif ($role === 'employee') {
            $this->openLogin();
        }
    }

    /**
     * Open employee login modal dialog.
     */
    public function openLogin(): void
    {
        $this->selectedRole = 'employee';
        $this->showLoginModal = true;
        $this->showQuickOrderModal = false;
        $this->resetValidation();
    }

    /**
     * Close employee login modal dialog.
     */
    public function closeLogin(): void
    {
        $this->showLoginModal = false;
        $this->resetValidation();
    }

    /**
     * Handle employee login submission.
     */
    public function submitLogin(): void
    {
        $this->validate([
            'loginUsername' => ['required', 'string'],
            'loginPassword' => ['required', 'string'],
        ], [
            'loginUsername.required' => 'Username wajib diisi.',
            'loginPassword.required' => 'Password wajib diisi.',
        ]);

        $credentials = [
            'password' => $this->loginPassword,
        ];

        // Attempt login via email or name
        if (filter_var($this->loginUsername, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $this->loginUsername;
        } else {
            $credentials['name'] = $this->loginUsername;
        }

        if (Auth::attempt($credentials)) {
            session()->regenerate();
            $this->showLoginModal = false;
            $user = Auth::user();

            if ($user) {
                if ($user->role === 'admin') {
                    $this->redirectRoute('admin.dashboard', navigate: true);

                    return;
                }

                if ($user->role === 'cashier') {
                    $this->redirectRoute('staff.cashier-register', navigate: true);

                    return;
                }

                if (in_array($user->role, ['staff', 'barista'], true)) {
                    $this->redirectRoute('staff.kitchen-board', navigate: true);

                    return;
                }
            }

            return;
        }

        $this->addError('loginUsername', 'Username atau password yang dimasukkan salah.');
    }

    /**
     * Direct navigation to table selection for quick order (or mobile modal).
     */
    public function openTableSelection(bool $isMobile = false): void
    {
        $this->openTableModal();
    }

    /**
     * Open mobile table selection modal dialog.
     */
    public function openTableModal(): void
    {
        $this->selectedRole = 'customer';
        session(['role' => 'customer']);
        $this->showTableModal = true;
        $this->showQuickOrderModal = false;
        $this->showLoginModal = false;
        $this->resetValidation();
    }

    /**
     * Close mobile table selection modal dialog.
     */
    public function closeTableModal(): void
    {
        $this->showTableModal = false;
        $this->resetValidation();
    }

    /**
     * Confirm 5-digit table code from mobile modal.
     */
    public function confirmTableCode(): void
    {
        $this->tableCode = strtoupper(trim($this->tableCode));

        $this->validate([
            'tableCode' => ['required', 'string', 'min:1', 'max:15'],
        ], [
            'tableCode.required' => 'Masukkan 5 digit kode meja Anda.',
            'tableCode.min' => 'Kode meja minimal 1 karakter.',
            'tableCode.max' => 'Kode meja maksimal 15 karakter.',
        ]);

        $table = $this->resolveTable($this->tableCode);

        if (! $table) {
            $this->addError('tableCode', 'Kode meja tidak ditemukan. Periksa kembali stiker meja Anda.');

            return;
        }

        if ($table->status === 'occupied') {
            $this->addError('tableCode', "Meja {$table->table_number} saat ini sedang terisi (Occupied).");

            return;
        }

        if ($table->status === 'reserved') {
            $this->addError('tableCode', "Meja {$table->table_number} saat ini telah direservasi.");

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
     * Quick-fill a table number shortcut.
     */
    public function quickFillTable(string $tableNumber): void
    {
        $table = Table::where('table_number', $tableNumber)->first();
        $code = ($table && ! empty($table->access_code)) ? (string) $table->access_code : str_pad($tableNumber, 5, '0', STR_PAD_LEFT);

        $this->tableCode = strtoupper($code);
        $this->resetValidation();
        $this->confirmTableCode();
    }

    /**
     * Open camera scanner modal.
     */
    public function openScanner(): void
    {
        $this->showScannerModal = true;
        $this->resetValidation();
    }

    /**
     * Close camera scanner modal.
     */
    public function closeScanner(): void
    {
        $this->showScannerModal = false;
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

        // 5. Partial token match
        $table = Table::where('qr_code_token', 'like', "%{$input}%")->first();
        if ($table) {
            return $table;
        }

        // 6. Case-insensitive table_number search
        return Table::whereRaw('LOWER(table_number) = ?', [strtolower($input)])->first();
    }

    /**
     * Commit table selection to session and transition to Quick Order name modal.
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
        $this->selectedTableAccessCode = $table->access_code;
        $this->showTableModal = false;
        $this->showScannerModal = false;
        $this->showQuickOrderModal = true;
        $this->resetValidation();
    }

    /**
     * Direct navigation to table selection for quick order.
     */
    public function openQuickOrder(): void
    {
        $this->selectedRole = 'customer';
        session(['role' => 'customer']);

        $this->redirectRoute('table.selection', navigate: true);
    }

    /**
     * Close the quick order modal dialog.
     */
    public function closeQuickOrder(): void
    {
        $this->showQuickOrderModal = false;
        $this->resetValidation();
    }

    /**
     * Return back to table selection modal from quick order on mobile.
     */
    public function backToTableModal(): void
    {
        $this->showQuickOrderModal = false;
        $this->showTableModal = true;
        $this->resetValidation();
    }

    /**
     * Submit customer quick order name.
     */
    public function submitQuickOrder(): void
    {
        $this->validate([
            'customerName' => ['required', 'string', 'min:2', 'max:50'],
        ], [
            'customerName.required' => 'Nama pemesan wajib diisi.',
            'customerName.min' => 'Nama minimal 2 karakter.',
            'customerName.max' => 'Nama maksimal 50 karakter.',
        ]);

        $sanitizedName = trim($this->customerName);
        session([
            'customer_name' => $sanitizedName,
            'role' => 'customer',
        ]);

        $this->submittedName = $sanitizedName;
        $this->showQuickOrderModal = false;

        if (session()->has('table_id') || $this->selectedTableId) {
            $this->redirectRoute('customer.order', navigate: true);
        } else {
            $this->redirectRoute('table.selection', navigate: true);
        }
    }

    /**
     * Render the portal selection view.
     */
    public function render()
    {
        $availableTables = Table::select('id', 'table_number', 'capacity', 'status', 'qr_code_token', 'access_code')
            ->where('status', 'available')
            ->orderBy('table_number')
            ->get();

        return view('livewire.login.Portal', [
            'currentYear' => date('Y'),
            'availableTables' => $availableTables,
        ]);
    }
}
