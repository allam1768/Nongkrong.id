<?php

namespace App\Livewire\Staff;

use App\Livewire\Concerns\HasLogout;
use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Kitchen & POS Kanban Board - NONGKRONG.id')]
class KitchenKanban extends Component
{
    use HasLogout;

    public function boot(): void
    {
        if (! auth()->check() || ! in_array(auth()->user()->role, ['kitchen', 'barista', 'staff', 'admin'], true)) {
            abort(403, 'Akses tidak diizinkan untuk peran ini.');
        }
    }

    public string $activeFilter = 'all'; // all, dine-in, takeaway

    public ?string $toastMessage = null;

    /**
     * Set active filter for orders.
     */
    public function setFilter(string $filter): void
    {
        if (in_array($filter, ['all', 'dine-in', 'takeaway'], true)) {
            $this->activeFilter = $filter;
        }
    }

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
            $tableNum = $order->table?->table_number ? "Meja {$order->table->table_number}" : 'Kasir Takeaway';
            $this->triggerToast("Pesanan {$order->order_code} siap disajikan ke {$tableNum}!");
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

    /**
     * Dismiss the toast notification manually.
     */
    public function dismissToast(): void
    {
        $this->toastMessage = null;
    }

    public function render()
    {
        $query = Order::with(['items.menu.category', 'table'])
            ->where('payment_status', 'paid')
            ->where(function ($q) {
                $q->whereIn('order_status', ['pending', 'processing', 'ready'])
                    ->orWhere(function ($sub) {
                        $sub->where('order_status', 'completed')
                            ->whereDate('updated_at', today());
                    });
            })
            ->latest('updated_at');
        $allOrders = $query->get();

        $filteredOrders = $allOrders;
        if ($this->activeFilter === 'dine-in') {
            $filteredOrders = $allOrders->filter(fn ($o) => !empty($o->table_id));
        } elseif ($this->activeFilter === 'takeaway') {
            $filteredOrders = $allOrders->filter(fn ($o) => empty($o->table_id));
        }

        $pendingOrders = $filteredOrders->where('order_status', 'pending');
        $inProgressOrders = $filteredOrders->where('order_status', 'processing');
        $readyOrders = $filteredOrders->where('order_status', 'ready');
        $completedOrders = $filteredOrders->where('order_status', 'completed')->sortByDesc('updated_at')->values();

        $activeOrders = $allOrders->whereIn('order_status', ['pending', 'processing', 'ready']);
        $totalItemsInQueue = $allOrders->whereIn('order_status', ['pending', 'processing'])
            ->sum(fn ($o) => $o->items->sum('quantity'));

        return view('livewire.staff.KitchenKanban', [
            'pendingOrders' => $pendingOrders,
            'inProgressOrders' => $inProgressOrders,
            'readyOrders' => $readyOrders,
            'completedOrders' => $completedOrders,
            'activeCount' => $activeOrders->count(),
            'totalItemsInQueue' => $totalItemsInQueue,
            'allCount' => $allOrders->whereIn('order_status', ['pending', 'processing', 'ready'])->count(),
            'dineInCount' => $allOrders->whereIn('order_status', ['pending', 'processing', 'ready'])->whereNotNull('table_id')->count(),
            'takeawayCount' => $allOrders->whereIn('order_status', ['pending', 'processing', 'ready'])->whereNull('table_id')->count(),
        ]);
    }
}
