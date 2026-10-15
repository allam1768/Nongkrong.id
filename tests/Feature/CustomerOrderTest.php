<?php

namespace Tests\Feature;

use App\Livewire\User\CustomerOrder;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $table = Table::where('status', 'available')->first();
        session([
            'table_id' => $table->id,
            'table_number' => $table->table_number,
            'access_code' => $table->access_code,
            'customer_name' => 'Budi Santoso',
        ]);
    }

    /**
     * Test accessing order page without table session redirects to portal with flash warning.
     */
    public function test_customer_order_redirects_to_portal_without_table_session(): void
    {
        session()->forget('table_id');

        $response = $this->get('/order');
        $response->assertRedirect(route('portal'));
        $response->assertSessionHas('warning');
    }

    /**
     * Test customer order page renders correctly with table and customer session.
     */
    public function test_customer_order_page_loads_with_required_elements(): void
    {
        $table = Table::where('table_number', '01')->first();

        $response = $this->withSession([
            'customer_name' => 'Budi Santoso',
            'table_id' => $table->id,
            'table_number' => $table->table_number,
            'access_code' => $table->access_code,
        ])->get('/order');

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Meja 01');
        $response->assertSee('Digital Menu');
        $response->assertSee('Pilih Menu Favoritmu');
        $response->assertSee('Semua Menu');
        $response->assertSee('Signature Coffee');
        $response->assertSee('Kopi Susu Gula Aren Senja');
    }

    /**
     * Test filter by category works.
     */
    public function test_customer_order_filters_by_category(): void
    {
        Livewire::test(CustomerOrder::class)
            ->call('selectCategory', 'signature-coffee')
            ->assertSet('selectedCategory', 'signature-coffee')
            ->assertSee('Kopi Susu Gula Aren Senja')
            ->call('selectCategory', 'meals-snacks')
            ->assertSet('selectedCategory', 'meals-snacks')
            ->assertSee('Truffle Parmesan Fries');
    }

    /**
     * Test searching by keyword works.
     */
    public function test_customer_order_searches_by_keyword(): void
    {
        Livewire::test(CustomerOrder::class)
            ->set('searchQuery', 'Americano')
            ->assertSee('Caffe Americano Double Shot')
            ->assertDontSee('Kopi Susu Gula Aren Senja');
    }

    /**
     * Test taste profile filtering works.
     */
    public function test_customer_order_filters_by_taste(): void
    {
        Livewire::test(CustomerOrder::class)
            ->call('selectTaste', 'bold')
            ->assertSet('selectedTaste', 'bold')
            ->assertSee('Americano');
    }

    /**
     * Test quick add item to cart.
     */
    public function test_customer_can_quick_add_to_cart(): void
    {
        $menu = Menu::first();

        Livewire::test(CustomerOrder::class)
            ->call('quickAddToCart', $menu->id)
            ->assertSet('cartCount', 1)
            ->assertSessionHas('customer_cart');
    }

    /**
     * Test item customization modal and adding to cart.
     */
    public function test_customer_can_customize_and_add_to_cart(): void
    {
        $menu = Menu::first();

        Livewire::test(CustomerOrder::class)
            ->call('openDetail', $menu->id)
            ->assertSet('showDetailModal', true)
            ->assertSet('detailMenuName', $menu->name)
            ->set('detailQuantity', 2)
            ->set('detailTemperature', 'hot')
            ->set('detailSugar', 'less')
            ->set('detailNotes', 'Es sedikit ya kak')
            ->call('addCustomizedToCart')
            ->assertSet('showDetailModal', false)
            ->assertSet('cartCount', 2);
    }

    /**
     * Test cart quantity adjustments and item removal.
     */
    public function test_customer_can_adjust_cart_quantities(): void
    {
        $menu = Menu::first();

        $test = Livewire::test(CustomerOrder::class)
            ->call('quickAddToCart', $menu->id);

        $cart = $test->get('cart');
        $itemKey = array_key_first($cart);

        $test->call('increaseItem', $itemKey)
            ->assertSet('cartCount', 2)
            ->call('decreaseItem', $itemKey)
            ->assertSet('cartCount', 1)
            ->call('removeItem', $itemKey)
            ->assertSet('cartCount', 0);
    }

    /**
     * Test checkout creates Order and OrderItems in database.
     */
    public function test_customer_can_checkout_and_place_order(): void
    {
        $table = Table::where('table_number', '01')->first();
        session([
            'customer_name' => 'Budi Santoso',
            'table_id' => $table->id,
            'table_number' => $table->table_number,
        ]);

        $menu = Menu::first();

        $component = Livewire::test(CustomerOrder::class)
            ->call('quickAddToCart', $menu->id)
            ->set('orderNotes', 'Mohon jangan pakai sedotan plastik')
            ->set('paymentMethod', 'qris')
            ->call('checkoutOrder')
            ->assertSet('cartCount', 0)
            ->assertSet('showOrderSuccessModal', true);

        $orderCode = $component->get('placedOrderCode');
        $this->assertNotNull($orderCode);

        $this->assertDatabaseHas('orders', [
            'order_code' => $orderCode,
            'table_id' => $table->id,
            'customer_name' => 'Budi Santoso',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'qris',
            'notes' => 'Mohon jangan pakai sedotan plastik',
        ]);

        $order = Order::where('order_code', $orderCode)->first();
        $this->assertNotNull($order);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'menu_id' => $menu->id,
            'quantity' => 1,
        ]);
    }

    /**
     * Test customer can select cash payment method and checkout successfully.
     */
    public function test_customer_can_select_cash_payment_method_and_checkout(): void
    {
        $table = Table::where('table_number', '01')->first();
        session([
            'customer_name' => 'Siti Rahma',
            'table_id' => $table->id,
            'table_number' => $table->table_number,
        ]);

        $menu = Menu::first();

        $component = Livewire::test(CustomerOrder::class)
            ->call('quickAddToCart', $menu->id)
            ->call('setPaymentMethod', 'cash')
            ->assertSet('paymentMethod', 'cash')
            ->call('checkoutOrder')
            ->assertSet('cartCount', 0)
            ->assertSet('showOrderSuccessModal', true)
            ->assertSet('placedPaymentMethod', 'cash');

        $orderCode = $component->get('placedOrderCode');
        $this->assertNotNull($orderCode);

        $this->assertDatabaseHas('orders', [
            'order_code' => $orderCode,
            'table_id' => $table->id,
            'customer_name' => 'Siti Rahma',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'cash',
        ]);
    }

    /**
     * Test checking payment status manually and toggling auto-check feature.
     */
    public function test_customer_can_check_payment_status_and_toggle_auto_check(): void
    {
        $table = Table::where('table_number', '01')->first();
        session([
            'customer_name' => 'Rian Hidayat',
            'table_id' => $table->id,
            'table_number' => $table->table_number,
        ]);

        $menu = Menu::first();

        $component = Livewire::test(CustomerOrder::class)
            ->call('quickAddToCart', $menu->id)
            ->set('paymentMethod', 'qris')
            ->call('checkoutOrder')
            ->assertSet('showOrderSuccessModal', true)
            ->assertSet('autoCheckPayment', true)
            ->assertSee('Cek Status Transaksi')
            ->assertSee('Auto Cek Transaksi')
            ->assertSee('● Aktif');

        $orderCode = $component->get('placedOrderCode');
        $this->assertNotNull($orderCode);

        // Toggle auto-check off then on
        $component->call('toggleAutoCheck')
            ->assertSet('autoCheckPayment', false)
            ->assertSee('Nonaktif');

        $component->call('toggleAutoCheck')
            ->assertSet('autoCheckPayment', true)
            ->assertSee('● Aktif');

        // Check payment manually while unpaid
        $component->call('checkPaymentStatus', true)
            ->assertSet('placedOrderPaid', false)
            ->assertDispatched('toast-notify');

        // Simulate database status changed to paid
        Order::where('order_code', $orderCode)->update(['payment_status' => 'paid']);

        // Check payment status should now detect paid state
        $component->call('checkPaymentStatus')
            ->assertSet('placedOrderPaid', true)
            ->assertSee('Pesanan Berhasil Dikirim!')
            ->assertSee('Pembayaran QRIS Terverifikasi (Lunas)');
    }

    public function test_customer_order_simulation_aborts_403_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        Livewire::test(CustomerOrder::class)
            ->call('simulatePaymentSuccess')
            ->assertStatus(403);
    }

    public function test_checkout_recalculates_price_and_tax_from_server_database_ignoring_client_tampering(): void
    {
        $table = Table::where('table_number', '01')->first();
        session([
            'customer_name' => 'Budi Santoso',
            'table_id' => $table->id,
            'table_number' => $table->table_number,
        ]);

        $menu = Menu::first();
        $realPrice = (float) $menu->price;
        $expectedSubtotal = $realPrice * 2;
        $expectedTax = round($expectedSubtotal * 0.10, 2);
        $expectedTotal = $expectedSubtotal + $expectedTax;

        // Attacker tampered cart price to 100 and cartTotal to 110 in client payload
        $tamperedCart = [
            'item_1' => [
                'menu_id' => $menu->id,
                'name' => $menu->name,
                'price' => 100,
                'quantity' => 2,
                'subtotal' => 200,
                'temperature' => 'ice',
                'sugar' => 'normal',
                'notes' => '',
            ],
        ];

        $component = Livewire::test(CustomerOrder::class)
            ->set('cart', $tamperedCart)
            ->set('cartTotal', 220)
            ->call('checkoutOrder');

        $orderCode = $component->get('placedOrderCode');
        $order = Order::where('order_code', $orderCode)->first();

        $this->assertEquals($expectedTotal, (float) $order->total_amount);
        $orderItem = $order->items->first();
        $this->assertEquals($realPrice, (float) $orderItem->unit_price);
        $this->assertEquals($expectedSubtotal, (float) $orderItem->subtotal);
    }

    public function test_customer_order_handle_midtrans_success_does_not_mark_paid_without_server_confirmation(): void
    {
        $table = Table::where('table_number', '01')->first();
        session([
            'customer_name' => 'Budi Santoso',
            'table_id' => $table->id,
            'table_number' => $table->table_number,
        ]);

        $menu = Menu::first();

        $component = Livewire::test(CustomerOrder::class)
            ->call('quickAddToCart', $menu->id)
            ->set('paymentMethod', 'qris')
            ->call('checkoutOrder')
            ->call('handleMidtransSuccess');

        $orderCode = $component->get('placedOrderCode');
        $order = Order::where('order_code', $orderCode)->first();
        $this->assertEquals('unpaid', $order->payment_status);
        $this->assertFalse($component->get('placedOrderPaid'));
    }
}
