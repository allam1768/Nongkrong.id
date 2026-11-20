<?php

namespace Tests\Feature;

use App\Livewire\Staff\CashierRegister;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
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

    public function test_cashier_can_customize_menu_and_add_to_cart(): void
    {
        Livewire::actingAs($this->cashier)
            ->test(CashierRegister::class)
            ->call('openCustomModal', $this->menu->id)
            ->assertSet('showCustomModal', true)
            ->assertSet('customMenuName', $this->menu->name)
            ->set('customTemperature', 'hot')
            ->set('customSugar', 'less')
            ->set('customNotes', 'Pisah sedotan')
            ->set('customQuantity', 2)
            ->call('addCustomizedToCart')
            ->assertSet('showCustomModal', false)
            ->set('selectedTableId', $this->table->id)
            ->set('customerName', 'Budi Custom')
            ->call('setCashGiven', 100000)
            ->call('processCheckout')
            ->assertSet('showSuccessModal', true);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Budi Custom',
            'table_id' => $this->table->id,
        ]);

        $this->assertDatabaseHas('order_items', [
            'menu_id' => $this->menu->id,
            'quantity' => 2,
            'item_notes' => 'Panas, Less Sugar, Pisah sedotan',
        ]);
    }

    public function test_cashier_can_view_table_status_modal_and_select_table(): void
    {
        $secondTable = Table::create([
            'table_number' => '99',
            'qr_token' => 'qr-99-token',
            'access_code' => 'T99',
            'status' => 'available',
            'capacity' => 4,
        ]);

        Livewire::actingAs($this->cashier)
            ->test(CashierRegister::class)
            ->call('openTableModal')
            ->assertSet('showTableModal', true)
            ->call('selectTableAndClose', $secondTable->id)
            ->assertSet('selectedTableId', $secondTable->id)
            ->assertSet('showTableModal', false);
    }

    public function test_cashier_can_toggle_table_status(): void
    {
        $this->table->update(['status' => 'available']);

        Livewire::actingAs($this->cashier)
            ->test(CashierRegister::class)
            ->call('toggleTableStatus', $this->table->id);

        $this->assertEquals('occupied', $this->table->fresh()->status);

        Livewire::actingAs($this->cashier)
            ->test(CashierRegister::class)
            ->call('toggleTableStatus', $this->table->id);

        $this->assertEquals('available', $this->table->fresh()->status);
    }

    public function test_cashier_checkout_marks_available_table_as_occupied(): void
    {
        $this->table->update(['status' => 'available']);

        Livewire::actingAs($this->cashier)
            ->test(CashierRegister::class)
            ->set('selectedTableId', $this->table->id)
            ->set('customerName', 'Budi Meja')
            ->call('addToCart', $this->menu->id)
            ->call('setCashGiven', 50000)
            ->call('processCheckout')
            ->assertSet('showSuccessModal', true);

        $this->assertEquals('occupied', $this->table->fresh()->status);
    }

    public function test_cashier_cannot_select_occupied_or_reserved_table(): void
    {
        $occupiedTable = Table::create([
            'table_number' => '88',
            'qr_token' => 'qr-88-token',
            'access_code' => 'T88',
            'status' => 'occupied',
            'capacity' => 4,
        ]);

        Livewire::actingAs($this->cashier)
            ->test(CashierRegister::class)
            ->set('selectedTableId', $this->table->id)
            ->call('selectTable', $occupiedTable->id)
            ->assertSet('selectedTableId', $this->table->id)
            ->call('selectTableAndClose', $occupiedTable->id)
            ->assertSet('selectedTableId', $this->table->id);
    }
}
