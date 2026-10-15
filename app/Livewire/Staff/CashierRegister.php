<?php

namespace App\Livewire\Staff;

use App\Livewire\Concerns\HasLogout;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Kasir POS Walk-in - NONGKRONG.id')]
class CashierRegister extends Component
{
    use HasLogout;

    public string $search = '';

    public int $selectedCategoryId = 0;

    public string $customerName = 'Pelanggan Walk-in';

    public int $selectedTableId = 1;

    public string $orderType = 'dine_in'; // dine_in, takeaway

    public string $paymentMethod = 'cash'; // cash, qris, qris_static

    public float $cashGiven = 0;

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $cart = [];

    public ?string $orderNotes = '';

    public ?string $lastOrderCode = null;

    public ?string $snapToken = null;

    public bool $showSuccessModal = false;

    public ?string $toastMessage = null;

    // Mobile Table Cash Ticket Scan & Validation State
    public string $ticketSearch = '';

    public ?int $selectedTicketOrderId = null;

    public bool $showTicketModal = false;

    public float $ticketCashGiven = 0;

    public function mount(): void
    {
        $firstTable = Table::first();
        if ($firstTable) {
            $this->selectedTableId = $firstTable->id;
        }
    }

    public function addToCart(int $menuId): void
    {
        $menu = Menu::find($menuId);
        if (! $menu) {
            return;
        }

        if (isset($this->cart[$menuId])) {
            $this->cart[$menuId]['quantity']++;
            $this->cart[$menuId]['subtotal'] = $this->cart[$menuId]['quantity'] * $this->cart[$menuId]['price'];
            $this->cart[$menuId]['formatted_subtotal'] = 'Rp '.number_format($this->cart[$menuId]['subtotal'], 0, ',', '.');
        } else {
            $price = (float) $menu->price;
            $this->cart[$menuId] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $price,
                'formatted_price' => 'Rp '.number_format($price, 0, ',', '.'),
                'quantity' => 1,
                'subtotal' => $price,
                'formatted_subtotal' => 'Rp '.number_format($price, 0, ',', '.'),
            ];
        }

        $this->triggerToast("Ditambahkan: {$menu->name}");
    }

    public function updateQuantity(int $menuId, int $delta): void
    {
        if (! isset($this->cart[$menuId])) {
            return;
        }

        $this->cart[$menuId]['quantity'] += $delta;

        if ($this->cart[$menuId]['quantity'] <= 0) {
            unset($this->cart[$menuId]);
        } else {
            $this->cart[$menuId]['subtotal'] = $this->cart[$menuId]['quantity'] * $this->cart[$menuId]['price'];
            $this->cart[$menuId]['formatted_subtotal'] = 'Rp '.number_format($this->cart[$menuId]['subtotal'], 0, ',', '.');
        }
    }

    public function removeFromCart(int $menuId): void
    {
        unset($this->cart[$menuId]);
    }

    public function clearCart(): void
    {
        $this->cart = [];
    }

    public function getSubtotalProperty(): float
    {
        return array_sum(array_column($this->cart, 'subtotal'));
    }

    public function getFormattedSubtotalProperty(): string
    {
        return 'Rp '.number_format($this->subtotal, 0, ',', '.');
    }

    public function getTaxProperty(): float
    {
        return $this->subtotal * 0.10;
    }

    public function getFormattedTaxProperty(): string
    {
        return 'Rp '.number_format($this->tax, 0, ',', '.');
    }

    public function getTotalProperty(): float
    {
        return $this->subtotal + $this->tax;
    }

    public function getFormattedTotalProperty(): string
    {
        return 'Rp '.number_format($this->total, 0, ',', '.');
    }

    public function getChangeProperty(): float
    {
        if ($this->cashGiven <= 0) {
            return 0;
        }

        return max(0, $this->cashGiven - $this->total);
    }

    public function getFormattedChangeProperty(): string
    {
        return 'Rp '.number_format($this->change, 0, ',', '.');
    }

    public function setCashGiven(float $amount): void
    {
        $this->cashGiven = $amount;
    }

    public function setExactCash(): void
    {
        $this->cashGiven = $this->total;
    }

    public function processCheckout(): void
    {
        if (empty($this->cart)) {
            $this->triggerToast('Keranjang masih kosong!');

            return;
        }

        $orderCode = 'ORD-'.now()->format('ymd').'-'.strtoupper(bin2hex(random_bytes(2)));
        $isMidtrans = ($this->paymentMethod === 'qris');

        DB::transaction(function () use ($orderCode, $isMidtrans) {
            $order = Order::create([
                'order_code' => $orderCode,
                'table_id' => $this->selectedTableId,
                'customer_name' => $this->customerName ?: 'Pelanggan Walk-in',
                'handled_by' => auth()->id() ?? 1,
                'order_status' => $isMidtrans ? 'pending' : 'processing',
                'payment_status' => $isMidtrans ? 'unpaid' : 'paid',
                'payment_method' => str_starts_with($this->paymentMethod, 'qris') ? 'qris' : 'cash',
                'total_amount' => $this->total,
                'notes' => $this->orderNotes,
            ]);

            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            $this->lastOrderCode = $orderCode;

            if ($isMidtrans) {
                $midtransService = app(MidtransService::class);
                $this->snapToken = $midtransService->getOrCreateSnapToken($order);
            }
        });

        if ($isMidtrans) {
            if ($this->snapToken) {
                $this->dispatch('open-midtrans-snap', token: $this->snapToken);
                $this->triggerToast("Memunculkan QRIS Midtrans untuk {$orderCode}...");
            } else {
                $this->triggerToast('Gagal memuat token QRIS Midtrans.');
            }
        } else {
            $this->cart = [];
            $this->showSuccessModal = true;
            $this->triggerToast("Transaksi {$orderCode} Berhasil!");
        }
    }

    /**
     * Handle payment success callback from Midtrans Snap popup.
     */
    public function handleMidtransSuccess(): void
    {
        if (! $this->lastOrderCode) {
            return;
        }

        $order = Order::where('order_code', $this->lastOrderCode)->first();
        if ($order) {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => $order->order_status === 'pending' ? 'processing' : $order->order_status,
            ]);
        }

        $this->cart = [];
        $this->showSuccessModal = true;
        $this->triggerToast("Transaksi {$this->lastOrderCode} Lunas via QRIS Midtrans!");
    }

    /**
     * Re-trigger Midtrans Snap popup for the last generated order if needed.
     */
    public function reOpenMidtrans(): void
    {
        if ($this->snapToken) {
            $this->dispatch('open-midtrans-snap', token: $this->snapToken);
        }
    }

    /**
     * Fallback manual confirmation if customer paid but popup was closed or webhook delayed.
     */
    public function markOrderPaidManually(): void
    {
        if (! $this->lastOrderCode) {
            return;
        }

        $order = Order::where('order_code', $this->lastOrderCode)->first();
        if ($order) {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'processing',
            ]);
        }

        $this->cart = [];
        $this->showSuccessModal = true;
        $this->triggerToast("Transaksi {$this->lastOrderCode} Ditandai Lunas!");
    }

    public function closeSuccessModal(): void
    {
        $this->showSuccessModal = false;
        $this->lastOrderCode = null;
        $this->snapToken = null;
        $this->cashGiven = 0;
    }

    /**
     * Open validation modal for a pending cash table order.
     */
    public function openTicketValidation(int $orderId): void
    {
        $order = Order::find($orderId);
        if (! $order) {
            $this->triggerToast('Pesanan tidak ditemukan.');

            return;
        }

        $this->selectedTicketOrderId = $orderId;
        $this->ticketCashGiven = (float) $order->total_amount;
        $this->showTicketModal = true;
    }

    /**
     * Close the cash ticket validation modal.
     */
    public function closeTicketModal(): void
    {
        $this->showTicketModal = false;
        $this->selectedTicketOrderId = null;
        $this->ticketCashGiven = 0;
    }

    /**
     * Scan or search an incoming table order via barcode or short code.
     */
    public function scanOrSearchTicket(): void
    {
        $code = trim($this->ticketSearch);
        if (empty($code)) {
            $this->triggerToast('Masukkan atau scan kode tiket pelanggan.');

            return;
        }

        $cleanCode = ltrim($code, '#');

        $order = Order::where(function ($q) use ($cleanCode) {
            $q->where('order_code', $cleanCode)
                ->orWhere('order_code', 'like', "%{$cleanCode}");
        })
            ->where('payment_status', '!=', 'paid')
            ->latest()
            ->first();

        if (! $order) {
            $this->triggerToast("Tiket '{$code}' tidak ditemukan atau sudah lunas.");

            return;
        }

        $this->ticketSearch = '';
        $this->openTicketValidation($order->id);
    }

    /**
     * Confirm cash payment received from the customer at cashier counter.
     */
    public function confirmTicketPayment(): void
    {
        if (! $this->selectedTicketOrderId) {
            return;
        }

        $order = Order::find($this->selectedTicketOrderId);
        if ($order) {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => $order->order_status === 'pending' ? 'processing' : $order->order_status,
            ]);

            $this->triggerToast("Pembayaran Tunai {$order->order_code} Berhasil Diterima! Pesanan dikirim ke Barista.");
        }

        $this->closeTicketModal();
    }

    public function triggerToast(string $message): void
    {
        $this->toastMessage = $message;
        $this->dispatch('toast-notify');
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();
        $tables = Table::all();

        $menusQuery = Menu::where('is_available', true);

        if ($this->selectedCategoryId > 0) {
            $menusQuery->where('category_id', $this->selectedCategoryId);
        }

        if (trim($this->search) !== '') {
            $menusQuery->where('name', 'like', '%'.trim($this->search).'%');
        }

        $menus = $menusQuery->get();

        $pendingCashOrders = Order::with(['table', 'orderItems.menu'])
            ->where('payment_method', 'cash')
            ->where('payment_status', 'unpaid')
            ->where('order_status', '!=', 'cancelled')
            ->latest()
            ->take(6)
            ->get();

        $selectedTicketOrder = $this->selectedTicketOrderId
            ? Order::with(['table', 'orderItems.menu'])->find($this->selectedTicketOrderId)
            : null;

        return view('livewire.staff.CashierRegister', [
            'categories' => $categories,
            'tables' => $tables,
            'menus' => $menus,
            'pendingCashOrders' => $pendingCashOrders,
            'selectedTicketOrder' => $selectedTicketOrder,
        ]);
    }
}
