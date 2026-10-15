<?php

namespace App\Livewire\User;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Services\MidtransService;
use App\Services\QrCodeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Midtrans\Transaction;

#[Layout('layouts.app')]
#[Title('Pesan Menu Digital')]
class CustomerOrder extends Component
{
    public string $customerName = '';

    public ?int $tableId = null;

    public ?string $tableNumber = null;

    public ?string $tableAccessCode = null;

    #[Url(as: 'kategori')]
    public string $selectedCategory = 'all';

    #[Url(as: 'q')]
    public string $searchQuery = '';

    public string $selectedTaste = 'all';

    /**
     * Shopping cart items array keyed by unique item hash.
     *
     * @var array<string, array<string, mixed>>
     */
    public array $cart = [];

    public int $cartCount = 0;

    public float $cartSubtotal = 0;

    public float $cartTax = 0;

    public float $cartTotal = 0;

    public string $formattedCartSubtotal = 'Rp 0';

    public string $formattedCartTax = 'Rp 0';

    public string $formattedCartTotal = 'Rp 0';

    // Customization Modal State
    public bool $showDetailModal = false;

    public ?int $detailMenuId = null;

    public ?string $detailMenuName = null;

    public ?string $detailMenuDesc = null;

    public float $detailMenuPrice = 0;

    public string $formattedDetailMenuPrice = 'Rp 0';

    public string $formattedDetailSubtotal = 'Rp 0';

    public array $detailMenuTaste = [];

    public ?string $detailMenuCategory = null;

    public int $detailQuantity = 1;

    public string $detailTemperature = 'cold';

    public string $detailSugar = 'normal';

    public string $detailNotes = '';

    // Cart Slide-over Drawer State
    public bool $showCartDrawer = false;

    public string $orderNotes = '';

    public string $paymentMethod = 'qris';

    // Order Placed Success Modal
    public bool $showOrderSuccessModal = false;

    public ?string $placedOrderCode = null;

    public ?string $placedOrderTime = null;

    public float $placedOrderTotal = 0;

    public string $formattedPlacedOrderTotal = 'Rp 0';

    public array $placedOrderItems = [];

    public ?string $placedPaymentMethod = null;

    public bool $placedOrderPaid = false;

    public ?string $placedSnapToken = null;

    public ?string $placedQrisUrl = null;

    public ?string $placedQrString = null;

    public ?string $placedCashQrUrl = null;

    public bool $autoCheckPayment = true;

    // Barista AI Modal State
    public bool $showAiModal = false;

    public string $aiMoodInput = '';

    public ?string $aiRecommendationResponse = null;

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $aiRecommendedMenus = [];

    /**
     * Quick mood selection chips for AI Barista.
     *
     * @var array<string, string>
     */
    public array $moodChips = [
        'Sweet & Creamy' => '🍯 Manis & Creamy',
        'Bold & Strong' => '☕ Bold & Kuat',
        'Fresh & Fruity' => '🧊 Segar Dingin',
        'Pastry & Snack' => '🥐 Cemilan Gurih',
        'Rich Chocolate' => '🍫 Cokelat Mewah',
    ];

    // Toast notification
    public ?string $toastMessage = null;

    /**
     * Mount lifecycle: initialize session data and restored cart.
     */
    public function mount(): void
    {
        if (! session()->has('table_id')) {
            session()->flash('warning', 'Silakan pilih meja terlebih dahulu sebelum memesan.');
            $this->redirectRoute('portal', navigate: true);

            return;
        }

        $this->customerName = (string) session('customer_name', 'Tamu');
        $this->tableId = (int) session('table_id');
        $this->tableNumber = (string) session('table_number');
        $this->tableAccessCode = (string) session('access_code', '');

        // Restore cart from session
        $savedCart = session('customer_cart', []);
        if (is_array($savedCart)) {
            foreach ($savedCart as $key => $item) {
                $this->cart[$key] = $this->formatCartItem((array) $item);
            }
        }

        $this->calculateCartTotals();
    }

    /**
     * Filter by category slug.
     */
    public function selectCategory(string $slug): void
    {
        $this->selectedCategory = $slug;
    }

    /**
     * Filter by taste profile tag.
     */
    public function selectTaste(string $taste): void
    {
        $this->selectedTaste = $taste;
    }

    /**
     * Quick-add 1 unit of a menu item directly to cart with default options.
     */
    public function quickAddToCart(int $menuId): void
    {
        $menu = Menu::find($menuId);
        if (! $menu || ! $menu->is_available) {
            $this->triggerToast('Menu tidak tersedia saat ini.');

            return;
        }

        $itemKey = $this->generateCartKey($menu->id, 'cold', 'normal', '');

        if (isset($this->cart[$itemKey])) {
            $this->cart[$itemKey]['quantity']++;
            $this->cart[$itemKey]['subtotal'] = $this->cart[$itemKey]['quantity'] * $this->cart[$itemKey]['price'];
        } else {
            $this->cart[$itemKey] = [
                'menu_id' => $menu->id,
                'name' => $menu->name,
                'price' => (float) $menu->price,
                'quantity' => 1,
                'subtotal' => (float) $menu->price,
                'temperature' => 'cold',
                'sugar' => 'normal',
                'notes' => '',
            ];
        }

        $this->saveCart();
        $this->triggerToast("{$menu->name} berhasil ditambahkan ke pesanan!");
    }

    /**
     * Open menu item customization modal.
     */
    public function openDetail(int $menuId): void
    {
        $menu = Menu::with('category')->find($menuId);
        if (! $menu) {
            return;
        }

        $this->detailMenuId = $menu->id;
        $this->detailMenuName = $menu->name;
        $this->detailMenuDesc = $menu->description;
        $this->detailMenuPrice = (float) $menu->price;
        $this->detailMenuTaste = (array) ($menu->taste_profile ?? []);
        $this->detailMenuCategory = $menu->category?->name;

        // Reset customization inputs
        $this->detailQuantity = 1;
        $this->detailTemperature = 'cold';
        $this->detailSugar = 'normal';
        $this->detailNotes = '';

        $this->updateDetailFormattedPrices();
        $this->showDetailModal = true;
    }

    /**
     * Close menu item customization modal.
     */
    public function closeDetail(): void
    {
        $this->showDetailModal = false;
        $this->detailMenuId = null;
    }

    /**
     * Increment detail quantity in modal.
     */
    public function incrementDetailQty(): void
    {
        if ($this->detailQuantity < 20) {
            $this->detailQuantity++;
            $this->updateDetailFormattedPrices();
        }
    }

    /**
     * Decrement detail quantity in modal.
     */
    public function decrementDetailQty(): void
    {
        if ($this->detailQuantity > 1) {
            $this->detailQuantity--;
            $this->updateDetailFormattedPrices();
        }
    }

    /**
     * Update formatted prices for detail modal.
     */
    protected function updateDetailFormattedPrices(): void
    {
        $this->formattedDetailMenuPrice = 'Rp '.number_format($this->detailMenuPrice, 0, ',', '.');
        $this->formattedDetailSubtotal = 'Rp '.number_format($this->detailQuantity * $this->detailMenuPrice, 0, ',', '.');
    }

    /**
     * Add customized item to cart from modal.
     */
    public function addCustomizedToCart(): void
    {
        if (! $this->detailMenuId) {
            return;
        }

        $itemKey = $this->generateCartKey(
            $this->detailMenuId,
            $this->detailTemperature,
            $this->detailSugar,
            $this->detailNotes
        );

        $subtotal = $this->detailQuantity * $this->detailMenuPrice;

        if (isset($this->cart[$itemKey])) {
            $this->cart[$itemKey]['quantity'] += $this->detailQuantity;
            $this->cart[$itemKey]['subtotal'] = $this->cart[$itemKey]['quantity'] * $this->cart[$itemKey]['price'];
        } else {
            $this->cart[$itemKey] = [
                'menu_id' => $this->detailMenuId,
                'name' => $this->detailMenuName,
                'price' => $this->detailMenuPrice,
                'quantity' => $this->detailQuantity,
                'subtotal' => $subtotal,
                'temperature' => $this->detailTemperature,
                'sugar' => $this->detailSugar,
                'notes' => trim($this->detailNotes),
            ];
        }

        $addedName = $this->detailMenuName;
        $this->saveCart();
        $this->closeDetail();
        $this->triggerToast("{$addedName} ditambahkan ke keranjang pesanan!");
    }

    /**
     * Increase item quantity in cart.
     */
    public function increaseItem(string $itemKey): void
    {
        if (isset($this->cart[$itemKey])) {
            $this->cart[$itemKey]['quantity']++;
            $this->cart[$itemKey]['subtotal'] = $this->cart[$itemKey]['quantity'] * $this->cart[$itemKey]['price'];
            $this->saveCart();
        }
    }

    /**
     * Decrease item quantity in cart.
     */
    public function decreaseItem(string $itemKey): void
    {
        if (isset($this->cart[$itemKey])) {
            if ($this->cart[$itemKey]['quantity'] > 1) {
                $this->cart[$itemKey]['quantity']--;
                $this->cart[$itemKey]['subtotal'] = $this->cart[$itemKey]['quantity'] * $this->cart[$itemKey]['price'];
            } else {
                unset($this->cart[$itemKey]);
            }
            $this->saveCart();
        }
    }

    /**
     * Remove item completely from cart.
     */
    public function removeItem(string $itemKey): void
    {
        if (isset($this->cart[$itemKey])) {
            unset($this->cart[$itemKey]);
            $this->saveCart();
            $this->triggerToast('Item dihapus dari keranjang.');
        }
    }

    /**
     * Clear all items in cart.
     */
    public function clearCart(): void
    {
        $this->cart = [];
        $this->saveCart();
        $this->triggerToast('Keranjang pesanan dikosongkan.');
    }

    /**
     * Toggle slide-over cart drawer.
     */
    public function toggleCart(): void
    {
        $this->showCartDrawer = ! $this->showCartDrawer;
    }

    /**
     * Open cart drawer.
     */
    public function openCart(): void
    {
        $this->showCartDrawer = true;
    }

    /**
     * Close cart drawer.
     */
    public function closeCart(): void
    {
        $this->showCartDrawer = false;
    }

    /**
     * Set payment method (cash or qris).
     */
    public function setPaymentMethod(string $method): void
    {
        if (in_array($method, ['cash', 'qris'], true)) {
            $this->paymentMethod = $method;
        }
    }

    /**
     * Submit and place the order to barista and database.
     */
    public function checkoutOrder(): void
    {
        if (empty($this->cart)) {
            $this->triggerToast('Keranjang Anda masih kosong. Pilih menu favorit Anda.');

            return;
        }

        // Re-fetch menus from database to ensure zero-trust security & calculate official prices on server
        $menuIds = array_filter(array_column($this->cart, 'menu_id'));
        $menus = Menu::whereIn('id', $menuIds)->get()->keyBy('id');

        $computedSubtotal = 0.0;
        $orderItemsData = [];

        foreach ($this->cart as $item) {
            $menuId = (int) ($item['menu_id'] ?? 0);
            $menu = $menus->get($menuId);

            if (! $menu) {
                continue;
            }

            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $unitPrice = (float) $menu->price;
            $lineSubtotal = $unitPrice * $quantity;
            $computedSubtotal += $lineSubtotal;

            $options = [];
            if (! empty($item['temperature'])) {
                $options[] = ucfirst($item['temperature']);
            }
            if (! empty($item['sugar']) && $item['sugar'] !== 'normal') {
                $options[] = ucfirst($item['sugar']).' Sugar';
            }
            if (! empty($item['notes'])) {
                $options[] = $item['notes'];
            }

            $itemNotes = ! empty($options) ? implode(', ', $options) : null;
            if ($itemNotes !== null) {
                $itemNotes = Str::limit($itemNotes, 250, '');
            }

            $orderItemsData[] = [
                'menu_id' => $menu->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $lineSubtotal,
                'item_notes' => $itemNotes,
                'item_snapshot' => [
                    'menu_id' => $menu->id,
                    'name' => $menu->name,
                    'category' => $menu->category,
                    'price' => $unitPrice,
                    'quantity' => $quantity,
                    'subtotal' => $lineSubtotal,
                    'options_summary' => $itemNotes,
                    'formatted_price' => 'Rp '.number_format($unitPrice, 0, ',', '.'),
                    'formatted_subtotal' => 'Rp '.number_format($lineSubtotal, 0, ',', '.'),
                ],
            ];
        }

        if (empty($orderItemsData)) {
            $this->triggerToast('Menu yang Anda pilih tidak tersedia atau tidak valid.');

            return;
        }

        // Server-calculated PB1 10% Restaurant Tax and Total Amount
        $tax = round($computedSubtotal * 0.10, 2);
        $totalAmount = $computedSubtotal + $tax;

        // Generate clean unique order code
        $datePrefix = date('Ymd');
        $randomSuffix = strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
        $orderCode = "ORD-{$datePrefix}-{$randomSuffix}";

        DB::transaction(function () use ($orderCode, $totalAmount, $orderItemsData) {
            $order = Order::create([
                'order_code' => $orderCode,
                'table_id' => $this->tableId,
                'customer_name' => $this->customerName ?: 'Tamu',
                'handled_by' => null,
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $this->paymentMethod,
                'total_amount' => $totalAmount,
                'notes' => trim($this->orderNotes) ?: null,
            ]);

            foreach ($orderItemsData as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $itemData['menu_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal' => $itemData['subtotal'],
                    'item_notes' => $itemData['item_notes'],
                ]);
            }

            if ($this->tableId) {
                Table::where('id', $this->tableId)->where('status', 'available')->update(['status' => 'occupied']);
            }

            // Save order snapshot for confirmation modal
            $this->placedOrderCode = $order->order_code;
            $this->placedOrderTime = now()->format('H:i');
            $this->placedOrderTotal = (float) $order->total_amount;
            $this->formattedPlacedOrderTotal = 'Rp '.number_format($this->placedOrderTotal, 0, ',', '.');
            $this->placedOrderItems = array_column($orderItemsData, 'item_snapshot');
            $this->placedPaymentMethod = $this->paymentMethod;
            $this->placedOrderPaid = false;
        });

        // Clear cart after successful checkout
        $this->cart = [];
        $this->orderNotes = '';
        $this->saveCart();

        if ($this->placedPaymentMethod === 'qris' && $this->placedOrderCode) {
            $order = Order::where('order_code', $this->placedOrderCode)->first();
            if ($order) {
                $midtransService = app(MidtransService::class);
                $this->placedSnapToken = $midtransService->getOrCreateSnapToken($order);
                $qrisData = $midtransService->getOrCreateQris($order);
                if ($qrisData) {
                    $this->placedQrisUrl = $qrisData['qr_url'] ?? null;
                    $this->placedQrString = $qrisData['qr_string'] ?? null;
                }
            }
        } elseif ($this->placedPaymentMethod === 'cash' && $this->placedOrderCode) {
            $this->placedCashQrUrl = QrCodeService::generateForOrder($this->placedOrderCode);
        }

        $this->showCartDrawer = false;
        $this->showOrderSuccessModal = true;
    }

    /**
     * Toggle auto-check polling for order payment.
     */
    public function toggleAutoCheck(): void
    {
        $this->autoCheckPayment = ! $this->autoCheckPayment;
        $status = $this->autoCheckPayment ? 'diaktifkan' : 'dijeda';
        $this->triggerToast("Auto-cek pembayaran {$status}.");
    }

    /**
     * Polling or manual method to detect payment status updates from Midtrans or DB.
     */
    public function checkPaymentStatus(bool $isManual = false): void
    {
        if (! $this->showOrderSuccessModal || ! $this->placedOrderCode) {
            return;
        }

        if ($this->placedOrderPaid) {
            if ($isManual) {
                $this->triggerToast('Pembayaran pesanan sudah diverifikasi (Lunas).');
            }

            return;
        }

        $order = Order::where('order_code', $this->placedOrderCode)->first();
        if (! $order) {
            if ($isManual) {
                $this->triggerToast('Data pesanan tidak ditemukan.');
            }

            return;
        }

        if ($order->payment_status === 'paid') {
            $this->placedOrderPaid = true;
            $msg = $this->placedPaymentMethod === 'cash' ? 'Pembayaran Tunai Diterima Kasir!' : 'Pembayaran QRIS Berhasil Diverifikasi!';
            $this->triggerToast($msg);

            return;
        }

        if ($this->placedPaymentMethod === 'qris') {
            try {
                $status = (object) Transaction::status($this->placedOrderCode);
                $txStatus = $status->transaction_status ?? null;

                if (in_array($txStatus, ['settlement', 'capture'], true)) {
                    $order->update([
                        'payment_status' => 'paid',
                        'order_status' => $order->order_status === 'pending' ? 'processing' : $order->order_status,
                    ]);
                    $this->placedOrderPaid = true;
                    $this->triggerToast('Pembayaran QRIS Berhasil Diverifikasi!');

                    return;
                }
            } catch (\Throwable $e) {
                // Ignore during polling while payment is still pending
            }
        }

        if ($isManual) {
            $channel = $this->placedPaymentMethod === 'cash' ? 'kasir' : 'QRIS';
            $this->triggerToast("Belum ada konfirmasi pembayaran {$channel}. Silakan tunggu beberapa saat atau cek lagi.");
        }
    }

    /**
     * Trigger Midtrans Snap payment popup.
     */
    public function payWithMidtrans(): void
    {
        if (! $this->placedOrderCode) {
            return;
        }

        $order = Order::where('order_code', $this->placedOrderCode)->first();
        if (! $order) {
            return;
        }

        if (empty($this->placedSnapToken)) {
            $midtransService = app(MidtransService::class);
            $this->placedSnapToken = $midtransService->getOrCreateSnapToken($order);
        }

        if ($this->placedSnapToken) {
            $this->dispatch('open-midtrans-snap', token: $this->placedSnapToken);
        }
    }

    /**
     * Handle payment success callback from Midtrans Snap popup.
     */
    public function handleMidtransSuccess(): void
    {
        if (! $this->placedOrderCode) {
            return;
        }

        $this->triggerToast('Menunggu konfirmasi pembayaran dari Midtrans...');
        $this->checkPaymentStatus(false);
    }

    /**
     * Simulate payment success for QRIS order.
     */
    public function simulatePaymentSuccess(): void
    {
        if (! app()->environment('local', 'testing')) {
            abort(403, 'Aksi simulasi pembayaran tidak diizinkan pada environment ini.');
        }

        if (! $this->placedOrderCode) {
            return;
        }

        $order = Order::where('order_code', $this->placedOrderCode)->first();
        if ($order) {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'processing',
            ]);
            $this->placedOrderPaid = true;
            $this->triggerToast('Pembayaran QRIS Berhasil Diverifikasi!');
        }
    }

    /**
     * Redirect customer to real-time live order tracking.
     */
    public function goToTracking(): void
    {
        if ($this->placedOrderCode) {
            $this->redirectRoute('order.tracking', ['code' => $this->placedOrderCode], navigate: true);
        }
    }

    /**
     * Close order confirmation modal and continue browsing.
     */
    public function closeOrderSuccess(): void
    {
        $this->showOrderSuccessModal = false;
        $this->placedOrderCode = null;
    }

    /**
     * Change table and redirect to table selection.
     */
    public function changeTable(): void
    {
        $this->redirectRoute('table.selection', navigate: true);
    }

    /**
     * Change name and return to portal.
     */
    public function changeName(): void
    {
        session()->forget(['table_id', 'table_number', 'access_code']);
        $this->redirectRoute('portal', navigate: true);
    }

    /**
     * Helper to show temporary toast message.
     */
    protected function triggerToast(string $message): void
    {
        $this->toastMessage = $message;
        $this->dispatch('toast-notify');
    }

    /**
     * Generate unique hash key for cart item variations.
     */
    protected function generateCartKey(int $menuId, string $temperature, string $sugar, string $notes): string
    {
        $cleanNotes = strtolower(trim($notes));

        return md5("{$menuId}_{$temperature}_{$sugar}_{$cleanNotes}");
    }

    /**
     * Format cart item with calculated subtotal, currency strings, and options summary.
     *
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    protected function formatCartItem(array $item): array
    {
        $options = [];
        if (! empty($item['temperature'])) {
            $options[] = ucfirst((string) $item['temperature']);
        }
        if (! empty($item['sugar']) && $item['sugar'] !== 'normal') {
            $options[] = ucfirst((string) $item['sugar']).' Sugar';
        }
        if (! empty($item['notes'])) {
            $options[] = (string) $item['notes'];
        }

        $price = (float) ($item['price'] ?? 0);
        $qty = (int) ($item['quantity'] ?? 1);
        $subtotal = $price * $qty;

        $item['subtotal'] = $subtotal;
        $item['options_summary'] = ! empty($options) ? implode(', ', $options) : null;
        $item['formatted_price'] = 'Rp '.number_format($price, 0, ',', '.');
        $item['formatted_subtotal'] = 'Rp '.number_format($subtotal, 0, ',', '.');

        return $item;
    }

    /**
     * Recalculate cart item totals, tax, and overall total.
     */
    protected function calculateCartTotals(): void
    {
        $count = 0;
        $subtotal = 0;

        foreach ($this->cart as $item) {
            $count += (int) ($item['quantity'] ?? 0);
            $subtotal += (float) ($item['subtotal'] ?? 0);
        }

        $this->cartCount = $count;
        $this->cartSubtotal = $subtotal;
        $this->cartTax = round($subtotal * 0.10, 2); // 10% PB1 Restaurant Tax
        $this->cartTotal = $subtotal + $this->cartTax;

        $this->formattedCartSubtotal = 'Rp '.number_format($this->cartSubtotal, 0, ',', '.');
        $this->formattedCartTax = 'Rp '.number_format($this->cartTax, 0, ',', '.');
        $this->formattedCartTotal = 'Rp '.number_format($this->cartTotal, 0, ',', '.');
    }

    /**
     * Persist cart to session and update local totals.
     */
    protected function saveCart(): void
    {
        foreach ($this->cart as $key => $item) {
            $this->cart[$key] = $this->formatCartItem((array) $item);
        }
        $this->calculateCartTotals();
        session(['customer_cart' => $this->cart]);
    }

    /**
     * Open Barista AI consultation modal.
     */
    public function openAiModal(): void
    {
        $this->showAiModal = true;
        if (empty($this->aiRecommendedMenus)) {
            $this->askAiBarista('Sweet & Creamy');
        }
    }

    /**
     * Close Barista AI consultation modal.
     */
    public function closeAiModal(): void
    {
        $this->showAiModal = false;
    }

    /**
     * Virtual Barista consultation recommendations based on mood or flavor.
     */
    public function askAiBarista(string $mood = ''): void
    {
        $prompt = ! empty($mood) ? $mood : trim($this->aiMoodInput);
        if (empty($prompt)) {
            $prompt = 'Sweet & Creamy';
        }

        $this->aiMoodInput = $prompt;
        $cleanPrompt = strtolower($prompt);

        if (str_contains($cleanPrompt, 'bold') || str_contains($cleanPrompt, 'pahit') || str_contains($cleanPrompt, 'strong')) {
            $this->aiRecommendationResponse = 'Untuk kamu yang butuh dorongan fokus tinggi dengan karakter rasa kopi yang bold & mantap, Barista merekomendasikan ekstraksi Arabika murni kami.';
            $menus = Menu::whereIn('id', [4, 5])->get();
        } elseif (str_contains($cleanPrompt, 'fresh') || str_contains($cleanPrompt, 'segar') || str_contains($cleanPrompt, 'dingin') || str_contains($cleanPrompt, 'fruit')) {
            $this->aiRecommendationResponse = 'Sensasi dingin menyegarkan dengan paduan citrus sparkling atau cold brew fruity siap recharge harimu!';
            $menus = Menu::whereIn('id', [10, 4])->get();
        } elseif (str_contains($cleanPrompt, 'snack') || str_contains($cleanPrompt, 'cemilan') || str_contains($cleanPrompt, 'makan') || str_contains($cleanPrompt, 'croissant')) {
            $this->aiRecommendationResponse = 'Pasangan sempurna untuk sesi nongkrong santai: pastry butter Prancis renyah atau kentang truffle parmesan gurih!';
            $menus = Menu::whereIn('id', [12, 14])->get();
        } elseif (str_contains($cleanPrompt, 'cokelat') || str_contains($cleanPrompt, 'chocolate') || str_contains($cleanPrompt, 'manis')) {
            $this->aiRecommendationResponse = 'Kombinasi Belgian chocolate mewah dan cake leleh hangat memberikan mood booster instan!';
            $menus = Menu::whereIn('id', [8, 13])->get();
        } else {
            // Default: Sweet & Creamy
            $this->aiRecommendationResponse = 'Pilihan racikan manis lembut paling favorit pengunjung! Susu segar dipadu gula aren alami dan saus karamel legit.';
            $menus = Menu::whereIn('id', [1, 2])->get();
        }

        $this->aiRecommendedMenus = $menus->map(function ($menu) {
            return [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => (float) $menu->price,
                'formatted_price' => 'Rp '.number_format((float) $menu->price, 0, ',', '.'),
                'description' => $menu->description,
                'flavor' => $this->getMenuFlavorTag($menu),
                'visual' => $this->getMenuVisual($menu),
            ];
        })->toArray();
    }

    /**
     * Helper to get high-level flavor tag for menu.
     */
    public function getMenuFlavorTag(Menu $menu): string
    {
        $taste = (array) ($menu->taste_profile ?? []);
        if (in_array('sweet', $taste) && in_array('creamy', $taste)) {
            return 'Sweet & Creamy';
        }
        if (in_array('caramel', $taste) || in_array('nutty', $taste)) {
            return 'Caramel & Nutty';
        }
        if (in_array('bold', $taste) || in_array('bitter', $taste) || in_array('strong', $taste)) {
            return 'Bitter & Bold';
        }
        if (in_array('earthy', $taste)) {
            return 'Earthy & Sweet';
        }
        if (in_array('flaky', $taste) || in_array('buttery', $taste)) {
            return 'Flaky & Butter';
        }
        if (in_array('savory', $taste) || in_array('crispy', $taste)) {
            return 'Savory & Crispy';
        }
        if (in_array('fruity', $taste) || in_array('fresh', $taste)) {
            return 'Fresh & Fruity';
        }
        if (in_array('floral', $taste) || in_array('aromatic', $taste)) {
            return 'Floral & Aromatic';
        }
        if (in_array('rich chocolate', $taste)) {
            return 'Rich Chocolate';
        }

        return 'Barista Choice';
    }

    /**
     * Helper to get visual emoji/icon for menu.
     */
    public function getMenuVisual(Menu $menu): string
    {
        $name = strtolower($menu->name);

        if (str_contains($name, 'croissant') || str_contains($name, 'bakery')) {
            return '🥐';
        }
        if (str_contains($name, 'toast') || str_contains($name, 'panini') || str_contains($name, 'sandwich')) {
            return '🥪';
        }
        if (str_contains($name, 'fries') || str_contains($name, 'snack')) {
            return '🍟';
        }
        if (str_contains($name, 'carbonara') || str_contains($name, 'pasta')) {
            return '🍝';
        }
        if (str_contains($name, 'cake') || str_contains($name, 'marshmallow') || str_contains($name, 'chocolate')) {
            return '🍰';
        }
        if (str_contains($name, 'matcha') || str_contains($name, 'tea') || str_contains($name, 'earl grey')) {
            return '🍵';
        }
        if (str_contains($name, 'americano') || str_contains($name, 'cold brew') || str_contains($name, 'sparkler')) {
            return '🧊';
        }
        if (str_contains($name, 'macchiato') || str_contains($name, 'latte') || str_contains($name, 'milk') || str_contains($name, 'frappe')) {
            return '🥛';
        }
        if (str_contains($name, 'aren') || str_contains($name, 'cappuccino') || str_contains($name, 'coffee') || str_contains($name, 'espresso')) {
            return '☕';
        }

        return '☕';
    }

    /**
     * Render menu catalog view.
     */
    public function render()
    {
        $categories = Category::where('is_active', true)->orderBy('id')->get();

        $menuQuery = Menu::with('category')->where('is_available', true);

        // Filter by Category
        if ($this->selectedCategory !== 'all') {
            $menuQuery->whereHas('category', function ($q) {
                $q->where('slug', $this->selectedCategory);
            });
        }

        // Search query filter
        if (! empty(trim($this->searchQuery))) {
            $term = trim($this->searchQuery);
            $menuQuery->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        // Filter by Taste Profile tag
        if ($this->selectedTaste !== 'all') {
            $taste = $this->selectedTaste;
            $menuQuery->whereJsonContains('taste_profile', $taste);
        }

        // Ensure cart items are properly formatted for presentation
        foreach ($this->cart as $key => $item) {
            if (! isset($item['formatted_subtotal'])) {
                $this->cart[$key] = $this->formatCartItem((array) $item);
            }
        }

        $menus = $menuQuery->orderBy('category_id')->orderBy('name')->get()->map(function ($menu) {
            $menu->flavor_tag = $this->getMenuFlavorTag($menu);
            $menu->visual = $this->getMenuVisual($menu);
            $menu->formatted_price = 'Rp '.number_format((float) $menu->price, 0, ',', '.');

            return $menu;
        });

        return view('livewire.user.CustomerOrder', [
            'categories' => $categories,
            'menus' => $menus,
        ]);
    }
}
