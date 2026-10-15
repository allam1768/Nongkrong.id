<?php

namespace Tests\Feature;

use App\Livewire\Login\Portal;
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
        $response->assertSee('Fall in Love');
        $response->assertSee('with Coffee');
        $response->assertSee('NONGKRONG.id');
        $response->assertSee('assets/img/portal.svg');
    }

    /**
     * Test quick order opens table selection modal.
     */
    public function test_portal_quick_order_opens_table_modal(): void
    {
        Livewire::test(Portal::class)
            ->call('openTableSelection')
            ->assertSet('showTableModal', true);

        Livewire::test(Portal::class)
            ->call('setRole', 'customer')
            ->assertSet('showTableModal', true);
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

    /**
     * Test mobile modal flow: open table selection modal, connect table, transition to quick order name modal, and redirect to order.
     */
    public function test_portal_mobile_table_selection_modal_flow(): void
    {
        Livewire::test(Portal::class)
            ->assertSet('showTableModal', false)
            ->assertSet('showQuickOrderModal', false)
            ->call('openTableModal')
            ->assertSet('showTableModal', true)
            ->assertSet('showQuickOrderModal', false)
            ->set('tableCode', '00001')
            ->call('confirmTableCode')
            ->assertHasNoErrors()
            ->assertSet('showTableModal', false)
            ->assertSet('showQuickOrderModal', true)
            ->assertSet('selectedTableNumber', '01')
            ->assertSessionHas('table_number', '01')
            ->set('customerName', 'Rian Pratama')
            ->call('submitQuickOrder')
            ->assertHasNoErrors()
            ->assertSet('showQuickOrderModal', false)
            ->assertSessionHas('customer_name', 'Rian Pratama')
            ->assertRedirect(route('customer.order'));
    }

    /**
     * Test mobile table selection modal validates invalid table code.
     */
    public function test_portal_mobile_table_selection_rejects_invalid_table(): void
    {
        Livewire::test(Portal::class)
            ->call('openTableModal')
            ->set('tableCode', '99999')
            ->call('confirmTableCode')
            ->assertHasErrors(['tableCode'])
            ->assertSet('showTableModal', true)
            ->assertSet('showQuickOrderModal', false);
    }

    /**
     * Test scanQrTable directly resolves table and transitions to quick order modal.
     */
    public function test_portal_scan_qr_table_flow(): void
    {
        Livewire::test(Portal::class)
            ->call('openTableModal')
            ->call('scanQrTable', '01')
            ->assertHasNoErrors()
            ->assertSet('showTableModal', false)
            ->assertSet('showQuickOrderModal', true)
            ->assertSet('selectedTableNumber', '01')
            ->assertSessionHas('table_number', '01');
    }

    /**
     * Test returning from quick order back to table modal on mobile.
     */
    public function test_portal_back_to_table_modal_navigation(): void
    {
        Livewire::test(Portal::class)
            ->call('openTableModal')
            ->call('scanQrTable', '01')
            ->assertSet('showTableModal', false)
            ->assertSet('showQuickOrderModal', true)
            ->call('backToTableModal')
            ->assertSet('showTableModal', true)
            ->assertSet('showQuickOrderModal', false);
    }
}
