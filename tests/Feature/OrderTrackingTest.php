<?php

namespace Tests\Feature;

use App\Livewire\User\OrderTracking;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OrderTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected Table $table;

    protected Menu $menu;

    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->table = Table::create([
            'table_number' => '04',
            'qr_token' => 'table-04-token',
            'access_code' => 'T04',
            'status' => 'occupied',
            'capacity' => 4,
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

        $this->order = Order::create([
            'order_code' => 'ORD-TEST-001',
            'table_id' => $this->table->id,
            'customer_name' => 'Allam',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'qris',
            'total_amount' => 22000,
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'menu_id' => $this->menu->id,
            'quantity' => 1,
            'unit_price' => 20000,
            'subtotal' => 20000,
        ]);
    }

    public function test_tracking_page_loads_with_valid_order_code(): void
    {
        $response = $this->get(route('order.tracking', ['code' => 'ORD-TEST-001']));
        $response->assertStatus(200);
        $response->assertSee('ORD-TEST-001');
        $response->assertSee('Allam');
        $response->assertSee('Kopi Susu Gula Aren');
    }

    public function test_tracking_page_404_for_invalid_code(): void
    {
        $response = $this->get(route('order.tracking', ['code' => 'ORD-NONEXISTENT']));
        $response->assertStatus(404);
    }

    public function test_qris_simulation_marks_order_as_paid(): void
    {
        Livewire::test(OrderTracking::class, ['code' => 'ORD-TEST-001'])
            ->call('simulatePayQris')
            ->assertSet('paymentStatus', 'paid');

        $this->assertDatabaseHas('orders', [
            'order_code' => 'ORD-TEST-001',
            'payment_status' => 'paid',
            'order_status' => 'processing',
        ]);
    }
}
