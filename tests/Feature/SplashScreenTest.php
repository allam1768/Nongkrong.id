<?php

namespace Tests\Feature;

use App\Livewire\SplashScreen;
use Livewire\Livewire;
use Tests\TestCase;

class SplashScreenTest extends TestCase
{
    /**
     * Test splash screen page loads successfully.
     */
    public function test_splash_screen_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('assets/img/NongkrongId.svg');
        $response->assertSee('#004AAD');
        $response->assertSee('navigateNext()');
    }

    /**
     * Test Livewire SplashScreen component renders.
     */
    public function test_splash_screen_livewire_component_renders(): void
    {
        Livewire::test(SplashScreen::class)
            ->assertStatus(200)
            ->assertSeeHtml('assets/img/NongkrongId.svg');
    }
}
