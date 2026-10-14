<?php

namespace Tests\Feature;

use App\Livewire\Staff\KitchenKanban;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class KitchenKanbanTest extends TestCase
{
    use RefreshDatabase;

    protected Table $table;

    protected Menu $menu;

    protected User $barista;

    protected function setUp(): void
    {
        parent::setUp();

        $this->barista = User::create([
            'name' => 'Dimas Barista',
            'email' => 'barista@smartcafe.id',
            'password' => bcrypt('password123'),
            'role' => 'staff',
        ]);

        $this->table = Table::create([
            'table_number' => '02',
            'qr_token' => 'qr-02-token',
            'access_code' => 'T02',
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
            'name' => 'Espresso Single',
            'slug' => 'espresso-single',
            'price' => 15000,
            'is_available' => true,
        ]);
    }

    public function test_kitchen_kanban_page_renders_successfully(): void
    {
        $response = $this->actingAs($this->barista)->get(route('staff.kitchen-board'));
        $response->assertStatus(200);
        $response->assertSee('Kanban Pesanan');
    }

    public function test_send_to_kitchen_moves_pending_to_processing(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-TEST-KB-1',
            'table_id' => $this->table->id,
            'customer_name' => 'Budi',
            'order_status' => 'pending',
            'payment_status' => 'paid',
            'payment_method' => 'qris',
            'total_amount' => 16500,
        ]);

        Livewire::actingAs($this->barista)
            ->test(KitchenKanban::class)
            ->call('sendToKitchen', $order->id);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'processing',
        ]);
    }

    public function test_confirm_cash_payment_marks_paid_and_processing(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-TEST-KB-2',
            'table_id' => $this->table->id,
            'customer_name' => 'Rian',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'cash',
            'total_amount' => 33000,
        ]);

        Livewire::actingAs($this->barista)
            ->test(KitchenKanban::class)
            ->call('confirmPayment', $order->id);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
            'order_status' => 'processing',
        ]);
    }

    public function test_mark_ready_moves_processing_to_ready(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-TEST-KB-3',
            'table_id' => $this->table->id,
            'customer_name' => 'Citra',
            'order_status' => 'processing',
            'payment_status' => 'paid',
            'payment_method' => 'qris',
            'total_amount' => 16500,
        ]);

        Livewire::actingAs($this->barista)
            ->test(KitchenKanban::class)
            ->call('markReady', $order->id);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'ready',
        ]);
    }

    public function test_mark_completed_moves_ready_to_completed(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-TEST-KB-4',
            'table_id' => $this->table->id,
            'customer_name' => 'Doni',
            'order_status' => 'ready',
            'payment_status' => 'paid',
            'payment_method' => 'cash',
            'total_amount' => 16500,
        ]);

        Livewire::actingAs($this->barista)
            ->test(KitchenKanban::class)
            ->call('markCompleted', $order->id);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'completed',
        ]);
    }
}
