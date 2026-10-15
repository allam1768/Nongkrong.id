<?php

namespace App\Livewire\Concerns;

use Illuminate\Support\Facades\Auth;

trait HasLogout
{
    /**
     * Terminate the authenticated user session and redirect to portal.
     */
    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirectRoute('portal', navigate: true);
    }
}
