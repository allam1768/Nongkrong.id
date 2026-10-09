<?php

namespace Tests\Feature;

use App\Livewire\Portal;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test portal page loads successfully with required role options and branding.
     */
    public function test_portal_page_loads_with_required_role_options(): void
    {
        $response = $this->get('/portal');

        $response->assertStatus(200);
        $response->assertSee('Pesan Cepat');
        $response->assertSee('Login Karyawan');
        $response->assertSee('Fall in Love with');
        $response->assertSee('NONGKRONG.id');
        $response->assertSee('barista1.png');
        $response->assertSee('barista2.png');
        $response->assertSee('assets/img/NongkrongId-blue.svg');
        $response->assertSee('assets/img/icont_noks-blue.svg');
        $this->assertFileExists(public_path('assets/img/NongkrongId-blue.svg'));
        $this->assertFileExists(public_path('assets/img/icont_noks-blue.svg'));
    }

    /**
     * Test quick order redirects directly to table selection.
     */
    public function test_portal_quick_order_redirects_to_table_selection(): void
    {
        Livewire::test(Portal::class)
            ->call('openTableSelection')
            ->assertRedirect(route('table.selection'));

        Livewire::test(Portal::class)
            ->call('openQuickOrder')
            ->assertRedirect(route('table.selection'));

        Livewire::test(Portal::class)
            ->call('setRole', 'customer')
            ->assertRedirect(route('table.selection'));
    }

    /**
     * Test Livewire Portal employee login modal flow.
     */
    public function test_portal_employee_login_modal_flow(): void
    {
        Livewire::test(Portal::class)
            ->assertSet('showLoginModal', false)
            ->call('openLogin')
            ->assertSet('showLoginModal', true)
            ->assertSet('selectedRole', 'employee')
            ->call('closeLogin')
            ->assertSet('showLoginModal', false);
    }

    /**
     * Test employee login validation and failure.
     */
    public function test_portal_employee_login_validates_input(): void
    {
        Livewire::test(Portal::class)
            ->call('openLogin')
            ->set('loginUsername', '')
            ->set('loginPassword', '')
            ->call('submitLogin')
            ->assertHasErrors(['loginUsername', 'loginPassword']);

        Livewire::test(Portal::class)
            ->call('openLogin')
            ->set('loginUsername', 'nonexistent')
            ->set('loginPassword', 'wrongpass')
            ->call('submitLogin')
            ->assertHasErrors(['loginUsername']);
    }

    /**
     * Test employee login succeeds with valid credentials.
     */
    public function test_portal_employee_login_success(): void
    {
        $employee = User::factory()->create([
            'name' => 'Barista Anto',
            'email' => 'anto@nongkrong.id',
            'role' => 'staff',
            'password' => bcrypt('password123'),
        ]);

        Livewire::test(Portal::class)
            ->call('openLogin')
            ->set('loginUsername', 'anto@nongkrong.id')
            ->set('loginPassword', 'password123')
            ->call('submitLogin')
            ->assertHasNoErrors()
            ->assertSet('showLoginModal', false);

        $this->assertAuthenticatedAs($employee);
    }
}
