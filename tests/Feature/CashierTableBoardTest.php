<?php

namespace Tests\Feature;

use App\Livewire\Staff\CashierRegister;
use App\Livewire\Staff\CashierTableBoard;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CashierTableBoardTest extends TestCase
{
    use RefreshDatabase;

    protected User $cashier;
    protected User $cleaner;
    protected Table $table1;
    protected Table $table2;
    protected Table $table3;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cashier = User::factory()->create([
            'name' => 'Siti Kasir',
            'email' => 'siti@nongkrong.id',
            'role' => 'cashier',
        ]);

        $this->cleaner = User::factory()->create([
            'name' => 'Joko Cleaner',
            'email' => 'joko@nongkrong.id',
            'role' => 'cleaner',
        ]);

        $this->table1 = Table::create(['table_number' => 1, 'capacity' => 4, 'status' => 'available']);
        $this->table2 = Table::create(['table_number' => 2, 'capacity' => 2, 'status' => 'occupied']);
        $this->table3 = Table::create(['table_number' => 3, 'capacity' => 6, 'status' => 'dirty']);
    }

    public function test_cashier_can_access_table_board_and_see_all_table_statuses(): void
    {
        \App\Models\Order::create([
            'order_code' => 'ORD-TEST-BOARD',
            'table_id' => $this->table2->id,
            'customer_name' => 'Budi Santoso',
            'order_status' => 'processing',
            'payment_status' => 'paid',
            'payment_method' => 'cash',
            'total_amount' => 25000,
        ]);

        $response = $this->actingAs($this->cashier)->get(route('staff.cashier-tables'));
        $response->assertStatus(200);
        $response->assertSee('Status Meja Kasir');
        $response->assertSee('Budi Santoso');
    }

    public function test_cashier_can_update_table_status_directly_from_table_board(): void
    {
        Livewire::actingAs($this->cashier)
            ->test(CashierTableBoard::class)
            ->call('updateTableStatus', $this->table1->id, 'dirty')
            ->assertSet('toastMessage', 'Status Meja 1 diubah menjadi KOTOR / SELESAI.');

        $this->assertEquals('dirty', $this->table1->fresh()->status);
    }

    public function test_cashier_can_filter_tables_on_table_board(): void
    {
        Livewire::actingAs($this->cashier)
            ->test(CashierTableBoard::class)
            ->call('setFilter', 'dirty')
            ->assertSet('activeFilter', 'dirty');
    }

    public function test_cashier_select_and_open_pos_redirects_with_table_id(): void
    {
        Livewire::actingAs($this->cashier)
            ->test(CashierTableBoard::class)
            ->call('selectAndOpenPos', $this->table2->id)
            ->assertRedirect(route('staff.cashier-register', ['table_id' => $this->table2->id]));
    }

    public function test_cashier_register_auto_selects_table_from_query_parameter(): void
    {
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'icon' => 'coffee']);
        Menu::create([
            'category_id' => $category->id,
            'name' => 'Kopi Susu Gula Aren',
            'slug' => 'kopi-susu-gula-aren',
            'price' => 18000,
            'is_available' => true,
        ]);

        Livewire::actingAs($this->cashier)
            ->withQueryParams(['table_id' => $this->table2->id])
            ->test(CashierRegister::class)
            ->assertSet('selectedTableId', $this->table2->id);
    }

    public function test_cleaner_cannot_access_cashier_table_board(): void
    {
        $response = $this->actingAs($this->cleaner)->get(route('staff.cashier-tables'));
        $response->assertStatus(403);
    }

    public function test_cashier_can_scan_or_search_customer_cash_ticket_on_table_board(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-TICKET-777',
            'table_id' => $this->table1->id,
            'customer_name' => 'Pelanggan Meja 1',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'cash',
            'total_amount' => 30000,
        ]);

        Livewire::actingAs($this->cashier)
            ->test(CashierTableBoard::class)
            ->call('handleScannedTicket', 'ORD-TICKET-777')
            ->assertSet('showTicketModal', true)
            ->assertSet('selectedTicketOrderId', $order->id)
            ->assertSet('ticketCashGiven', 30000);
    }

    public function test_cashier_can_confirm_customer_cash_ticket_payment_on_table_board(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-TICKET-888',
            'table_id' => $this->table2->id,
            'customer_name' => 'Pelanggan Meja 2',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'cash',
            'total_amount' => 45000,
        ]);

        Livewire::actingAs($this->cashier)
            ->test(CashierTableBoard::class)
            ->call('openTicketValidation', $order->id)
            ->call('setTicketCashGiven', 50000)
            ->call('confirmTicketPayment')
            ->assertSet('showTicketModal', false);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
            'order_status' => 'processing',
        ]);
    }
}
