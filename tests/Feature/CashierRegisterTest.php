<?php

namespace Tests\Feature;

use App\Livewire\Staff\CashierRegister;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Table;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CashierRegisterTest extends TestCase
{
    use RefreshDatabase;

    protected User $cashier;

    protected Table $table;

    protected Menu $menu;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cashier = User::create([
            'name' => 'Siti Kasir POS',
            'email' => 'kasir@smartcafe.id',
            'password' => bcrypt('password123'),
            'role' => 'staff',
        ]);

        $this->table = Table::create([
            'table_number' => '01',
            'qr_token' => 'qr-01-token',
            'access_code' => 'T01',
            'status' => 'available',
            'capacity' => 2,
        ]);

        $category = Category::create([
            'name' => 'Kopi',
            'slug' => 'kopi',
            'icon_url' => '☕',
            'is_active' => true,
        ]);

        $this->menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Kopi Susu Gula Aren',
            'slug' => 'kopi-susu-gula-aren',
            'price' => 20000,
            'is_available' => true,
        ]);
    }

    public function test_unauthenticated_user_redirected_from_cashier_register(): void
    {
        $response = $this->get(route('staff.cashier-register'));
        $response->assertRedirect(route('portal'));
    }

    public function test_customer_role_forbidden_from_cashier_register(): void
    {
        $customer = User::create([
            'name' => 'Budi Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get(route('staff.cashier-register'));
        $response->assertStatus(403);
    }

    public function test_cashier_register_page_renders_successfully(): void
    {
        $response = $this->actingAs($this->cashier)->get(route('staff.cashier-register'));
        $response->assertStatus(200);
        $response->assertSee('Kasir POS Walk-in');
    }

    public function test_cashier_can_add_item_and_calculate_totals(): void
    {
        Livewire::actingAs($this->cashier)
            ->test(CashierRegister::class)
            ->call('addToCart', $this->menu->id)
            ->assertSet('subtotal', 20000)
            ->assertSet('tax', 2000)
            ->assertSet('total', 22000);
    }

    public function test_cashier_can_process_cash_checkout_and_save_order(): void
    {
        Livewire::actingAs($this->cashier)
            ->test(CashierRegister::class)
            ->set('selectedTableId', $this->table->id)
            ->set('customerName', 'Pak Bambang')
            ->call('addToCart', $this->menu->id)
            ->call('setCashGiven', 50000)
            ->call('processCheckout')
            ->assertSet('showSuccessModal', true);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Pak Bambang',
            'table_id' => $this->table->id,
            'payment_status' => 'paid',
            'order_status' => 'processing',
        ]);
    }

    public function test_cashier_can_process_midtrans_qris_checkout_and_handle_success(): void
    {
        $mock = $this->mock(MidtransService::class);
        $mock->shouldReceive('getOrCreateSnapToken')
            ->once()
            ->andReturn('dummy-snap-token-kasir-123');

        $component = Livewire::actingAs($this->cashier)
            ->test(CashierRegister::class)
            ->set('selectedTableId', $this->table->id)
            ->set('customerName', 'Mbak Citra')
            ->set('paymentMethod', 'qris')
            ->call('addToCart', $this->menu->id)
            ->call('processCheckout')
            ->assertDispatched('open-midtrans-snap', token: 'dummy-snap-token-kasir-123')
            ->assertSet('snapToken', 'dummy-snap-token-kasir-123');

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Mbak Citra',
            'table_id' => $this->table->id,
            'payment_status' => 'unpaid',
            'payment_method' => 'qris',
        ]);

        $component->call('handleMidtransSuccess')
            ->assertSet('showSuccessModal', true);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Mbak Citra',
            'table_id' => $this->table->id,
            'payment_status' => 'paid',
            'order_status' => 'processing',
        ]);
    }

    public function test_cashier_can_process_static_qris_checkout(): void
    {
        Livewire::actingAs($this->cashier)
            ->test(CashierRegister::class)
            ->set('selectedTableId', $this->table->id)
            ->set('customerName', 'Mas Dito')
            ->set('paymentMethod', 'qris_static')
            ->call('addToCart', $this->menu->id)
            ->call('processCheckout')
            ->assertSet('showSuccessModal', true);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Mas Dito',
            'table_id' => $this->table->id,
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'order_status' => 'processing',
        ]);
    }
}
