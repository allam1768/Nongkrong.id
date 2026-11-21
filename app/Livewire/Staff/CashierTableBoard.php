<?php

namespace App\Livewire\Staff;

use App\Livewire\Concerns\HasLogout;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Status Meja Kasir - NONGKRONG.id')]
class CashierTableBoard extends Component
{
    use HasLogout;

    public function boot(): void
    {
        if (! Auth::check() || ! in_array(Auth::user()->role, ['cashier', 'staff', 'admin'], true)) {
            abort(403, 'Akses tidak diizinkan untuk peran ini.');
        }
    }

    public string $activeFilter = 'all'; // all, available, occupied, dirty

    public string $search = '';

    public ?string $toastMessage = null;

    // Mobile Table Cash Ticket Scan & Validation State
    public string $ticketSearch = '';

    public ?int $selectedTicketOrderId = null;

    public bool $showTicketModal = false;

    public bool $showScannerModal = false;

    public float $ticketCashGiven = 0;

    public function setFilter(string $filter): void
    {
        if (in_array($filter, ['all', 'available', 'occupied', 'dirty'], true)) {
            $this->activeFilter = $filter;
        }
    }

    public function triggerToast(string $message): void
    {
        $this->toastMessage = $message;
        $this->dispatch('toast-alert', message: $message);
    }

    public function dismissToast(): void
    {
        $this->toastMessage = null;
    }

    public function updateTableStatus(int $tableId, string $status): void
    {
        if (! in_array($status, ['available', 'occupied', 'dirty'], true)) {
            return;
        }

        $table = Table::find($tableId);
        if (! $table) {
            return;
        }

        $table->update(['status' => $status]);

        $statusLabel = match ($status) {
            'available' => 'KOSONG',
            'occupied' => 'TERISI',
            'dirty' => 'KOTOR / SELESAI',
            default => strtoupper($status),
        };

        $this->triggerToast("Status Meja {$table->table_number} diubah menjadi {$statusLabel}.");
    }

    public function selectAndOpenPos(int $tableId): void
    {
        $this->redirectRoute('staff.cashier-register', ['table_id' => $tableId], navigate: true);
    }

    public function getAvailableCountProperty(): int
    {
        return Table::where('status', 'available')->count();
    }

    public function getOccupiedCountProperty(): int
    {
        return Table::where('status', 'occupied')->count();
    }

    public function getDirtyCountProperty(): int
    {
        return Table::where('status', 'dirty')->count();
    }

    public function getTotalCountProperty(): int
    {
        return Table::count();
    }

    /**
     * @return Collection<int, Table>
     */
    public function getTablesProperty(): Collection
    {
        return Table::query()
            ->when($this->activeFilter !== 'all', function ($query) {
                $query->where('status', $this->activeFilter);
            })
            ->when(filled($this->search), function ($query) {
                $query->where('table_number', 'like', '%' . trim($this->search) . '%');
            })
            ->with(['orders' => function ($q) {
                $q->whereIn('order_status', ['pending', 'processing', 'ready'])->latest();
            }])
            ->orderByRaw('LENGTH(table_number) ASC, table_number ASC')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function openScannerModal(): void
    {
        $this->showScannerModal = true;
    }

    public function closeScannerModal(): void
    {
        $this->showScannerModal = false;
    }

    public function handleScannedTicket(string $scannedCode): void
    {
        $this->showScannerModal = false;
        $this->ticketSearch = $scannedCode;
        $this->scanOrSearchTicket();
    }

    public function setTicketCashGiven(float $amount): void
    {
        $this->ticketCashGiven = $amount;
    }

    public function setExactTicketCash(): void
    {
        if ($this->selectedTicketOrderId) {
            $order = Order::find($this->selectedTicketOrderId);
            if ($order) {
                $this->ticketCashGiven = (float) $order->total_amount;
            }
        }
    }

    public function getTicketChangeProperty(): float
    {
        if (! $this->selectedTicketOrderId || $this->ticketCashGiven <= 0) {
            return 0;
        }

        $order = Order::find($this->selectedTicketOrderId);
        if (! $order) {
            return 0;
        }

        return max(0, $this->ticketCashGiven - (float) $order->total_amount);
    }

    public function getFormattedTicketChangeProperty(): string
    {
        return 'Rp '.number_format($this->ticketChange, 0, ',', '.');
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
                'handled_by' => Auth::id() ?? 1,
            ]);

            $this->triggerToast("Pembayaran Tunai {$order->order_code} Berhasil Diterima! Pesanan dikirim ke Barista.");
        }

        $this->closeTicketModal();
    }

    public function render()
    {
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

        return view('livewire.staff.CashierTableBoard', [
            'tables' => $this->tables,
            'pendingCashOrders' => $pendingCashOrders,
            'selectedTicketOrder' => $selectedTicketOrder,
        ]);
    }
}
