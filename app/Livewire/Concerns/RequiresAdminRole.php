<?php

namespace App\Livewire\Concerns;

use Illuminate\Support\Facades\Auth;

trait RequiresAdminRole
{
    /**
     * Enforce strict admin authentication and role on component lifecycle.
     */
    public function bootRequiresAdminRole(): void
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            session()->flash('warning', 'Akses khusus administrator. Silakan login terlebih dahulu.');
            $this->redirectRoute('portal', navigate: true);
        }
    }
}
