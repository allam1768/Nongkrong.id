<?php

namespace App\Livewire\Staff;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Kitchen & POS Kanban Board - NONGKRONG.id')]
class KitchenKanban extends Component
{
    public string $activeFilter = 'all'; // all, dine-in, takeaway

    public ?string $toastMessage = null;

    /**
     * Mark pending order as in_progress (sent to kitchen/barista).
     */
    public function sendToKitchen(int $orderId): void
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->update([
                'order_status' => 'processing',
            ]);
            $this->triggerToast("Pesanan {$order->order_code} diteruskan ke Barista!");
        }
    }

    /**
     * Confirm cash payment received by cashier.
     */
    public function confirmPayment(int $orderId): void
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'processing',
            ]);
            $this->triggerToast("Pembayaran {$order->order_code} lunas! Diteruskan ke Barista.");
        }
    }

    /**
     * Barista marks order ready for pickup/delivery.
     */
    public function markReady(int $orderId): void
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->update([
                'order_status' => 'ready',
            ]);
            $this->triggerToast("Pesanan {$order->order_code} siap disajikan ke Meja {$order->table?->table_number}!");
        }
    }

    /**
     * Waiter/Staff marks order completed (served to table).
     */
    public function markCompleted(int $orderId): void
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->update([
                'order_status' => 'completed',
            ]);
            $this->triggerToast("Pesanan {$order->order_code} selesai & diantarkan!");
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

    public function render()
    {
        $query = Order::with(['items.menu', 'table'])->latest();

        $allOrders = $query->get();

        $pendingOrders = $allOrders->where('order_status', 'pending');
        $inProgressOrders = $allOrders->where('order_status', 'processing');
        $readyOrders = $allOrders->where('order_status', 'ready');
        $completedOrders = $allOrders->where('order_status', 'completed')->take(10);

        return view('livewire.staff.KitchenKanban', [
            'pendingOrders' => $pendingOrders,
            'inProgressOrders' => $inProgressOrders,
            'readyOrders' => $readyOrders,
            'completedOrders' => $completedOrders,
        ]);
    }
}
