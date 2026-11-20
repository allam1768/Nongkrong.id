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
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Kasir POS Walk-in - NONGKRONG.id')]
class CashierRegister extends Component
{
    use HasLogout;

    public function boot(): void
    {
        if (! auth()->check() || ! in_array(auth()->user()->role, ['cashier', 'staff', 'admin'], true)) {
            abort(403, 'Akses tidak diizinkan untuk peran ini.');
        }
    }

    public string $search = '';

    public int $selectedCategoryId = 0;

    public string $customerName = 'Pelanggan Walk-in';

    public int $selectedTableId = 1;

    #[Url]
    public ?int $table_id = null;

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

    // Customization Modal State for POS Cashier
    public bool $showCustomModal = false;

    // Table Status & Floor Map Modal State
    public bool $showTableModal = false;

    public ?int $customMenuId = null;

    public ?string $customMenuName = null;

    public ?string $customMenuCategory = null;

    public float $customMenuPrice = 0;

    public string $formattedCustomMenuPrice = 'Rp 0';

    public string $formattedCustomSubtotal = 'Rp 0';

    public bool $customIsBeverage = true;

    public int $customQuantity = 1;

    public string $customTemperature = 'cold';

    public string $customSugar = 'normal';

    public string $customServing = 'warm';

    public string $customSauce = 'standard';

    public string $customNotes = '';

    public function mount(?int $table_id = null): void
    {
        $targetTableId = $table_id ?: $this->table_id ?: (int) request()->query('table_id');

        if ($targetTableId && Table::where('id', $targetTableId)->exists()) {
            $this->selectedTableId = (int) $targetTableId;
            $this->table_id = (int) $targetTableId;
        } else {
            $availableTable = Table::where('status', 'available')->first();
            if ($availableTable) {
                $this->selectedTableId = $availableTable->id;
            } else {
                $firstTable = Table::first();
                if ($firstTable) {
                    $this->selectedTableId = $firstTable->id;
                }
            }
        }
    }

    public function openTableModal(): void
    {
        $this->showTableModal = true;
    }

    public function closeTableModal(): void
    {
        $this->showTableModal = false;
    }

    public function selectTable(int $tableId): void
    {
        $table = Table::find($tableId);
        if (! $table) {
            return;
        }

        if (! $table->isAvailable()) {
            $this->triggerToast("Meja {$table->table_number} sedang terisi dan tidak dapat dipilih.");

            return;
        }

        $this->selectedTableId = $table->id;
        $this->triggerToast("Meja {$table->table_number} (Kosong) dipilih.");
    }

    public function selectTableAndClose(int $tableId): void
    {
        $table = Table::find($tableId);
        if (! $table || ! $table->isAvailable()) {
            if ($table) {
                $this->triggerToast("Meja {$table->table_number} sedang terisi dan tidak dapat dipilih.");
            }

            return;
        }

        $this->selectTable($tableId);
        $this->showTableModal = false;
    }

    public function updatedSelectedTableId(mixed $value): void
    {
        $table = Table::find((int) $value);
        if ($table && ! $table->isAvailable()) {
            $available = Table::where('status', 'available')->first();
            $this->selectedTableId = $available ? $available->id : 0;
            $this->triggerToast("Meja {$table->table_number} sedang terisi dan tidak dapat dipilih.");
        }
    }

    public function markTableDirty(int $tableId): void
    {
        $table = Table::find($tableId);
        if ($table) {
            $table->update(['status' => 'dirty']);
            if ((int) $this->selectedTableId === (int) $tableId) {
                $available = Table::where('status', 'available')->first();
                $this->selectedTableId = $available ? $available->id : 0;
            }
            $this->triggerToast("Meja {$table->table_number} ditandai Kotor. Info diteruskan ke Tim Pembersih!");
        }
    }

    public function markTableAvailable(int $tableId): void
    {
        $table = Table::find($tableId);
        if ($table) {
            $table->update(['status' => 'available']);
            $this->triggerToast("Meja {$table->table_number} sekarang Kosong & Siap digunakan.");
        }
    }

    public function markTableOccupied(int $tableId): void
    {
        $table = Table::find($tableId);
        if ($table) {
            $table->update(['status' => 'occupied']);
            $this->triggerToast("Meja {$table->table_number} ditandai Terisi (Occupied).");
        }
    }

    public function toggleTableStatus(int $tableId): void
    {
        $table = Table::find($tableId);
        if (! $table) {
            return;
        }

        // Toggle status between available and occupied, or reset to available
        $newStatus = match ($table->status) {
            'available' => 'occupied',
            'occupied' => 'available',
            default => 'available',
        };
        $table->update(['status' => $newStatus]);

        $label = match ($newStatus) {
            'available' => 'Kosong (Tersedia)',
            'occupied' => 'Terisi (Occupied)',
            'dirty' => 'Kotor (Perlu Dibersihkan)',
            default => $newStatus,
        };
        $this->triggerToast("Meja {$table->table_number} sekarang ditandai: {$label}");
    }

    public function getAvailableTablesCountProperty(): int
    {
        return Table::where('status', 'available')->count();
    }

    public function getDirtyTablesCountProperty(): int
    {
        return Table::where('status', 'dirty')->count();
    }

    public function getOccupiedTablesCountProperty(): int
    {
        return Table::where('status', 'occupied')->count();
    }

    public function getTotalTablesCountProperty(): int
    {
        return Table::count();
    }

    public function addToCart(int $menuId): void
    {
        $menu = Menu::with('category')->find($menuId);
        if (! $menu) {
            return;
        }

        $key = (string) $menuId;
        $price = (float) $menu->price;

        if (isset($this->cart[$key])) {
            $this->cart[$key]['quantity']++;
            $this->cart[$key]['subtotal'] = $this->cart[$key]['quantity'] * $this->cart[$key]['price'];
            $this->cart[$key]['formatted_subtotal'] = 'Rp '.number_format($this->cart[$key]['subtotal'], 0, ',', '.');
        } else {
            $this->cart[$key] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $price,
                'formatted_price' => 'Rp '.number_format($price, 0, ',', '.'),
                'quantity' => 1,
                'subtotal' => $price,
                'formatted_subtotal' => 'Rp '.number_format($price, 0, ',', '.'),
                'is_beverage' => $menu->isBeverage(),
                'notes' => null,
                'options_summary' => null,
            ];
        }

        $this->triggerToast("Ditambahkan: {$menu->name}");
    }

    public function openCustomModal(int $menuId): void
    {
        $menu = Menu::with('category')->find($menuId);
        if (! $menu) {
            return;
        }

        $this->customMenuId = $menu->id;
        $this->customMenuName = $menu->name;
        $this->customMenuCategory = $menu->category?->name;
        $this->customMenuPrice = (float) $menu->price;
        $this->customIsBeverage = $menu->isBeverage();

        $this->customQuantity = 1;
        $this->customTemperature = 'cold';
        $this->customSugar = 'normal';
        $this->customServing = 'warm';
        $this->customSauce = 'standard';
        $this->customNotes = '';

        $this->updateCustomFormattedPrices();
        $this->showCustomModal = true;
    }

    public function closeCustomModal(): void
    {
        $this->showCustomModal = false;
        $this->customMenuId = null;
    }

    public function incrementCustomQty(): void
    {
        if ($this->customQuantity < 50) {
            $this->customQuantity++;
            $this->updateCustomFormattedPrices();
        }
    }

    public function decrementCustomQty(): void
    {
        if ($this->customQuantity > 1) {
            $this->customQuantity--;
            $this->updateCustomFormattedPrices();
        }
    }

    protected function updateCustomFormattedPrices(): void
    {
        $this->formattedCustomMenuPrice = 'Rp '.number_format($this->customMenuPrice, 0, ',', '.');
        $this->formattedCustomSubtotal = 'Rp '.number_format($this->customQuantity * $this->customMenuPrice, 0, ',', '.');
    }

    public function addCustomizedToCart(): void
    {
        if (! $this->customMenuId) {
            return;
        }

        $options = [];
        if ($this->customIsBeverage) {
            $options[] = $this->customTemperature === 'cold' ? 'Dingin' : 'Panas';
            if ($this->customSugar !== 'normal') {
                $options[] = match ($this->customSugar) {
                    'less' => 'Less Sugar',
                    'zero' => 'No Sugar',
                    default => ucfirst($this->customSugar).' Sugar',
                };
            }
        } else {
            $options[] = $this->customServing === 'warm' ? 'Dihangatkan' : 'Suhu Ruang';
            if ($this->customSauce !== 'standard') {
                $options[] = match ($this->customSauce) {
                    'extra_sauce' => 'Extra Saus',
                    'no_sauce' => 'Tanpa Saus',
                    default => $this->customSauce,
                };
            }
        }

        $cleanNotes = trim($this->customNotes);
        if (! empty($cleanNotes)) {
            $options[] = $cleanNotes;
        }

        $summary = implode(', ', $options);
        $optKey = $this->customIsBeverage
            ? "{$this->customTemperature}_{$this->customSugar}"
            : "{$this->customServing}_{$this->customSauce}";
        $itemKey = md5("{$this->customMenuId}_{$optKey}_{$cleanNotes}");

        $price = $this->customMenuPrice;
        $qty = $this->customQuantity;
        $subtotal = $qty * $price;

        if (isset($this->cart[$itemKey])) {
            $this->cart[$itemKey]['quantity'] += $qty;
            $this->cart[$itemKey]['subtotal'] = $this->cart[$itemKey]['quantity'] * $price;
            $this->cart[$itemKey]['formatted_subtotal'] = 'Rp '.number_format($this->cart[$itemKey]['subtotal'], 0, ',', '.');
        } else {
            $this->cart[$itemKey] = [
                'id' => $this->customMenuId,
                'name' => $this->customMenuName,
                'price' => $price,
                'formatted_price' => 'Rp '.number_format($price, 0, ',', '.'),
                'quantity' => $qty,
                'subtotal' => $subtotal,
                'formatted_subtotal' => 'Rp '.number_format($subtotal, 0, ',', '.'),
                'is_beverage' => $this->customIsBeverage,
                'notes' => $summary,
                'options_summary' => $summary,
            ];
        }

        $addedName = $this->customMenuName;
        $this->closeCustomModal();
        $this->triggerToast("Ditambahkan: {$addedName} ({$summary})");
    }

    public function updateQuantity(string|int $menuId, int $delta): void
    {
        $key = (string) $menuId;
        if (! isset($this->cart[$key])) {
            return;
        }

        $this->cart[$key]['quantity'] += $delta;

        if ($this->cart[$key]['quantity'] <= 0) {
            unset($this->cart[$key]);
        } else {
            $this->cart[$key]['subtotal'] = $this->cart[$key]['quantity'] * $this->cart[$key]['price'];
            $this->cart[$key]['formatted_subtotal'] = 'Rp '.number_format($this->cart[$key]['subtotal'], 0, ',', '.');
        }
    }

    public function removeFromCart(string|int $menuId): void
    {
        unset($this->cart[(string) $menuId]);
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

        if ($this->orderType === 'dine_in' && $this->selectedTableId) {
            $table = Table::find($this->selectedTableId);
            if (! $table || ! $table->isAvailable()) {
                $this->triggerToast('Meja yang dipilih sedang tidak tersedia atau sudah terisi.');

                return;
            }
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
                    'item_notes' => $item['notes'] ?? $item['options_summary'] ?? null,
                ]);
            }

            if ($this->selectedTableId) {
                Table::where('id', $this->selectedTableId)->where('status', 'available')->update(['status' => 'occupied']);
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

        $this->redirectRoute('staff.cashier-tables', navigate: true);
    }

    public function triggerToast(string $message): void
    {
        $this->toastMessage = $message;
        $this->dispatch('toast-notify');
    }

    public function dismissToast(): void
    {
        $this->toastMessage = null;
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();
        $tables = Table::with(['orders' => function ($q) {
            $q->whereNotIn('order_status', ['completed', 'cancelled'])->latest();
        }])->get();

        $menusQuery = Menu::where('is_available', true);

        if ($this->selectedCategoryId > 0) {
            $menusQuery->where('category_id', $this->selectedCategoryId);
        }

        if (trim($this->search) !== '') {
            $menusQuery->where('name', 'like', '%'.trim($this->search).'%');
        }

        $menus = $menusQuery->get();

        return view('livewire.staff.CashierRegister', [
            'categories' => $categories,
            'tables' => $tables,
            'menus' => $menus,
        ]);
    }
}
