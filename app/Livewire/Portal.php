<?php

namespace App\Livewire;

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
     * Controls quick order modal visibility.
     */
    public bool $showQuickOrderModal = false;

    /**
     * Controls employee login modal visibility.
     */
    public bool $showLoginModal = false;

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
            $this->openQuickOrder();
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

            return;
        }

        $this->addError('loginUsername', 'Username atau password yang dimasukkan salah.');
    }

    /**
     * Direct navigation to table selection for quick order.
     */
    public function openTableSelection(): void
    {
        $this->openQuickOrder();
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

        $this->redirectRoute('table.selection', navigate: true);
    }

    /**
     * Render the portal selection view.
     */
    public function render()
    {
        return view('livewire.Portal', [
            'currentYear' => date('Y'),
        ]);
    }
}
