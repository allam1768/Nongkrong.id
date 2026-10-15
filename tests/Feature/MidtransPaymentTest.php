<?php

namespace Tests\Feature;

use App\Livewire\User\OrderTracking;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MidtransPaymentTest extends TestCase
{
    use RefreshDatabase;

    private Table $table;

    private Menu $menu;

    protected function setUp(): void
    {
        parent::setUp();

        $this->table = Table::create([
            'table_number' => '05',
            'qr_token' => 'qr-table-05',
            'access_code' => 'TBL-05',
            'status' => 'available',
        ]);

        $category = Category::create([
            'name' => 'Signature Coffee',
            'slug' => 'coffee',
        ]);

        $this->menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Kopi Susu Gula Aren',
            'slug' => 'kopi-susu-gula-aren',
            'price' => 18000,
            'is_available' => true,
        ]);
    }

    public function test_midtrans_webhook_updates_order_to_paid_on_settlement(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-TEST-001',
            'table_id' => $this->table->id,
            'customer_name' => 'Allam',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'qris',
            'total_amount' => 18000,
        ]);

        $serverKey = config('services.midtrans.server_key');
        $signature = hash('sha512', $order->order_code.'200'.'18000.00'.$serverKey);

        $payload = [
            'order_id' => $order->order_code,
            'status_code' => '200',
            'gross_amount' => '18000.00',
            'transaction_status' => 'settlement',
            'signature_key' => $signature,
        ];

        $response = $this->postJson(route('midtrans.webhook'), $payload);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('processing', $order->order_status);
    }

    public function test_midtrans_webhook_handles_cancelled_order(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-TEST-002',
            'table_id' => $this->table->id,
            'customer_name' => 'Allam',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'qris',
            'total_amount' => 18000,
        ]);

        $serverKey = config('services.midtrans.server_key');
        $signature = hash('sha512', $order->order_code.'200'.'18000.00'.$serverKey);

        $payload = [
            'order_id' => $order->order_code,
            'status_code' => '200',
            'gross_amount' => '18000.00',
            'transaction_status' => 'cancel',
            'signature_key' => $signature,
        ];

        $response = $this->postJson(route('midtrans.webhook'), $payload);

        $response->assertStatus(200);

        $order->refresh();
        $this->assertEquals('cancelled', $order->order_status);
        $this->assertEquals('unpaid', $order->payment_status);
    }

    public function test_midtrans_webhook_returns_404_for_unknown_order(): void
    {
        $payload = [
            'order_id' => 'NON-EXISTENT-ORDER',
            'status_code' => '200',
            'gross_amount' => '18000.00',
            'transaction_status' => 'settlement',
        ];

        $response = $this->postJson(route('midtrans.webhook'), $payload);

        $response->assertStatus(404);
    }

    public function test_midtrans_webhook_rejects_payload_without_signature_key(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-TEST-NOSIG',
            'table_id' => $this->table->id,
            'customer_name' => 'Attacker',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'qris',
            'total_amount' => 18000,
        ]);

        $payload = [
            'order_id' => $order->order_code,
            'status_code' => '200',
            'gross_amount' => '18000.00',
            'transaction_status' => 'settlement',
            // Omit signature_key intentionally
        ];

        $response = $this->postJson(route('midtrans.webhook'), $payload);

        $response->assertStatus(404);

        $order->refresh();
        $this->assertEquals('unpaid', $order->payment_status);
        $this->assertEquals('pending', $order->order_status);
    }

    public function test_midtrans_webhook_rejects_payload_with_invalid_signature_key(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-TEST-INVALIDSIG',
            'table_id' => $this->table->id,
            'customer_name' => 'Attacker',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'qris',
            'total_amount' => 18000,
        ]);

        $payload = [
            'order_id' => $order->order_code,
            'status_code' => '200',
            'gross_amount' => '18000.00',
            'transaction_status' => 'settlement',
            'signature_key' => 'invalid-hash-string-that-does-not-match',
        ];

        $response = $this->postJson(route('midtrans.webhook'), $payload);

        $response->assertStatus(404);

        $order->refresh();
        $this->assertEquals('unpaid', $order->payment_status);
        $this->assertEquals('pending', $order->order_status);
    }

    public function test_midtrans_webhook_rejects_payload_with_mismatched_gross_amount(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-TEST-MISMATCH',
            'table_id' => $this->table->id,
            'customer_name' => 'Attacker',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'qris',
            'total_amount' => 50000,
        ]);

        $serverKey = config('services.midtrans.server_key');
        // Underpaying with 1000 instead of 50000
        $tamperedGross = '1000.00';
        $signature = hash('sha512', $order->order_code.'200'.$tamperedGross.$serverKey);

        $payload = [
            'order_id' => $order->order_code,
            'status_code' => '200',
            'gross_amount' => $tamperedGross,
            'transaction_status' => 'settlement',
            'signature_key' => $signature,
        ];

        $response = $this->postJson(route('midtrans.webhook'), $payload);

        $response->assertStatus(404);

        $order->refresh();
        $this->assertEquals('unpaid', $order->payment_status);
        $this->assertEquals('pending', $order->order_status);
    }

    public function test_order_tracking_does_not_mark_paid_from_client_callback_alone(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-TRACK-001',
            'table_id' => $this->table->id,
            'customer_name' => 'Allam',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'qris',
            'total_amount' => 18000,
            'snap_token' => 'dummy-snap-token-123',
        ]);

        Livewire::test(OrderTracking::class, ['code' => $order->order_code])
            ->call('payWithMidtrans')
            ->assertDispatched('open-midtrans-snap', token: 'dummy-snap-token-123')
            ->call('handleMidtransSuccess');

        $order->refresh();
        $this->assertEquals('unpaid', $order->payment_status);
        $this->assertEquals('pending', $order->order_status);
    }
}
