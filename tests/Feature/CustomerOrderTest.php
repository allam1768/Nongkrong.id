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
}
