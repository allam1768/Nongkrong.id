<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Nongkrong.id')]
class SplashScreen extends Component
{
    /**
     * Render the splash screen component.
     */
    public function render()
    {
        return view('livewire.SplashScreen');
    }
}
