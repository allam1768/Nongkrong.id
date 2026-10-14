<?php

namespace App\Livewire\User;

use App\Models\Order;
use App\Models\Table;
use App\Services\MidtransService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Pantau Status Pesanan')]
class OrderTracking extends Component
{
    public string $orderCode;

    public ?int $orderId = null;

    public string $customerName = '';

    public string $tableNumber = '';

    public string $orderStatus = 'pending';

    public string $paymentStatus = 'unpaid';

    public string $paymentMethod = 'qris';

    public float $totalAmount = 0;

    public string $formattedTotalAmount = 'Rp 0';

    public ?string $orderNotes = null;

    public string $orderTime = '';

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $orderItems = [];

    public bool $showQrisModal = false;

    public ?string $toastMessage = null;

    public function mount(string $code): void
    {
        $this->orderCode = $code;
        $this->loadOrder();
    }

    /**
     * Load order details from database.
     */
    public function loadOrder(): void
    {
        $order = Order::with(['items.menu', 'table'])
            ->where('order_code', $this->orderCode)
            ->first();

        if (! $order) {
            abort(404, 'Pesanan tidak ditemukan.');
        }

        $this->orderId = $order->id;
        $this->customerName = $order->customer_name;
        $this->tableNumber = $order->table instanceof Table ? (string) $order->table->table_number : '01';
        $this->orderStatus = $order->order_status;
        $this->paymentStatus = $order->payment_status;
        $this->paymentMethod = $order->payment_method;
        $this->totalAmount = (float) $order->total_amount;
        $this->formattedTotalAmount = 'Rp '.number_format($this->totalAmount, 0, ',', '.');
        $this->orderNotes = $order->notes;
        $this->orderTime = $order->created_at ? $order->created_at->format('H:i') : now()->format('H:i');

        $this->orderItems = $order->items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->menu ? $item->menu->name : 'Menu',
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'formatted_price' => 'Rp '.number_format((float) $item->unit_price, 0, ',', '.'),
                'subtotal' => (float) $item->subtotal,
                'formatted_subtotal' => 'Rp '.number_format((float) $item->subtotal, 0, ',', '.'),
                'notes' => $item->item_notes,
            ];
        })->toArray();
    }

    /**
     * Polled every 3 seconds to get live status from kitchen / cashier.
     */
    public function refreshStatus(): void
    {
        $order = Order::where('order_code', $this->orderCode)->first();
        if ($order) {
            $this->orderStatus = $order->order_status;
            $this->paymentStatus = $order->payment_status;
        }
    }

    /**
     * Trigger Midtrans Snap payment popup from tracking screen.
     */
    public function payWithMidtrans(): void
    {
        $order = Order::where('order_code', $this->orderCode)->first();
        if (! $order) {
            return;
        }

        $midtransService = app(MidtransService::class);
        $snapToken = $midtransService->getOrCreateSnapToken($order);

        if ($snapToken) {
            $this->dispatch('open-midtrans-snap', token: $snapToken);
        }
    }

    /**
     * Handle payment success callback from Midtrans Snap popup.
     */
    public function handleMidtransSuccess(): void
    {
        $order = Order::where('order_code', $this->orderCode)->first();
        if ($order) {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => $order->order_status === 'pending' ? 'processing' : $order->order_status,
            ]);
            $this->paymentStatus = 'paid';
            $this->orderStatus = $order->order_status;
            $this->showQrisModal = false;
            $this->triggerToast('Pembayaran Midtrans Berhasil Diverifikasi!');
        }
    }

    /**
     * Simulate paying QRIS online right from tracking screen.
     */
    public function simulatePayQris(): void
    {
        $order = Order::where('order_code', $this->orderCode)->first();
        if ($order) {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => $order->order_status === 'pending' ? 'processing' : $order->order_status,
            ]);
            $this->paymentStatus = 'paid';
            $this->orderStatus = $order->order_status;
            $this->showQrisModal = false;
            $this->triggerToast('Pembayaran QRIS Berhasil Diverifikasi!');
        }
    }

    /**
     * Trigger temporary toast notification.
     */
    protected function triggerToast(string $message): void
    {
        $this->toastMessage = $message;
        $this->dispatch('toast-notify');
    }

    /**
     * Get numeric step (1 to 4) according to order status.
     */
    public function getCurrentStepProperty(): int
    {
        return match ($this->orderStatus) {
            'pending' => 1,
            'processing' => 2,
            'ready' => 3,
            'completed' => 4,
            default => 1,
        };
    }

    public function render()
    {
        return view('livewire.user.OrderTracking');
    }
}
