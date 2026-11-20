<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\HasLogout;
use App\Livewire\Concerns\RequiresAdminRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Kelola Akun Karyawan & Peran - Admin NONGKRONG.id')]
class AdminUser extends Component
{
    use HasLogout;
    use RequiresAdminRole;

    public string $search = '';
    public string $roleFilter = 'all'; // all, admin, cashier, kitchen, barista, cleaner

    public bool $showFormModal = false;
    public bool $showDeleteModal = false;

    // Form attributes
    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public string $role = 'cashier';
    public string $password = '';
    public string $password_confirmation = '';

    // Delete target
    public ?int $deleteUserId = null;
    public string $deleteUserName = '';

    public ?string $toastMessage = null;

    public function openCreateModal(): void
    {
        $this->reset(['userId', 'name', 'email', 'password', 'password_confirmation']);
        $this->role = 'cashier';
        $this->resetValidation();
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetValidation();
        $this->showFormModal = true;
    }

    public function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->reset(['userId', 'name', 'email', 'role', 'password', 'password_confirmation']);
        $this->resetValidation();
    }

    public function save(): void
    {
        $isEdit = ! empty($this->userId);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->userId),
            ],
            'role' => ['required', 'in:admin,cashier,kitchen,barista,cleaner,staff'],
        ];

        if ($isEdit) {
            $rules['password'] = ['nullable', 'string', 'min:6'];
        } else {
            $rules['password'] = ['required', 'string', 'min:6'];
        }

        $this->validate($rules, [
            'name.required' => 'Nama karyawan wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar untuk pengguna lain.',
            'role.required' => 'Peran karyawan wajib dipilih.',
            'password.required' => 'Kata sandi wajib diisi untuk akun baru.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        if ($isEdit) {
            $user = User::findOrFail($this->userId);
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ];
            if (! empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }
            $user->update($data);
            $this->triggerToast("Akun karyawan '{$user->name}' berhasil diperbarui.");
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'password' => Hash::make($this->password),
            ]);
            $this->triggerToast("Akun karyawan baru '{$user->name}' berhasil ditambahkan.");
        }

        $this->closeFormModal();
    }

    public function confirmDelete(int $id): void
    {
        $user = User::findOrFail($id);

        // Prevent admin from deleting themselves
        if ((int) Auth::id() === (int) $user->id) {
            $this->triggerToast('Anda tidak dapat menghapus akun Anda sendiri saat sedang masuk.');
            return;
        }

        $this->deleteUserId = $user->id;
        $this->deleteUserName = $user->name;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deleteUserId = null;
        $this->deleteUserName = '';
    }

    public function deleteUser(): void
    {
        if (! $this->deleteUserId) {
            return;
        }

        $user = User::find($this->deleteUserId);
        if ($user) {
            if ((int) Auth::id() === (int) $user->id) {
                $this->triggerToast('Anda tidak dapat menghapus akun Anda sendiri.');
                $this->cancelDelete();
                return;
            }

            $userName = $user->name;
            $user->delete();
            $this->triggerToast("Akun '{$userName}' berhasil dihapus dari sistem.");
        }

        $this->cancelDelete();
    }

    protected function triggerToast(string $message): void
    {
        $this->toastMessage = $message;
        $this->dispatch('toast-notify');
    }

    public function dismissToast(): void
    {
        $this->toastMessage = null;
    }

    public function render()
    {
        $query = User::query();

        if ($this->roleFilter !== 'all') {
            $query->where('role', $this->roleFilter);
        }

        if (trim($this->search) !== '') {
            $search = trim($this->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name', 'asc')->get();

        $roleCounts = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'cashier' => User::where('role', 'cashier')->count(),
            'kitchen' => User::whereIn('role', ['kitchen', 'barista'])->count(),
            'cleaner' => User::where('role', 'cleaner')->count(),
        ];

        return view('livewire.admin.AdminUser', [
            'users' => $users,
            'roleCounts' => $roleCounts,
        ]);
    }
}
