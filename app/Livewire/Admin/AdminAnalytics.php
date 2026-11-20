<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\HasLogout;
use App\Livewire\Concerns\RequiresAdminRole;
use App\Models\AiConsultation;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Executive Dashboard & Analytics - NONGKRONG.id')]
class AdminAnalytics extends Component
{
    use HasLogout;
    use RequiresAdminRole;

    public string $period = 'all'; // 'today', 'week', 'month', 'all'
    public string $statusFilter = 'all'; // 'all', 'pending', 'in_progress', 'ready', 'completed', 'unpaid'
    public string $search = '';

    public function setPeriod(string $period): void
    {
        $this->period = in_array($period, ['today', 'week', 'month', 'all']) ? $period : 'all';
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
    }

    public function render()
    {
        // Revenue query with period filter
        $revenueQuery = Order::where('payment_status', 'paid');
        $this->applyPeriodFilter($revenueQuery);
        $totalRevenue = (float) $revenueQuery->sum('total_amount');

        // Total orders with period filter
        $ordersQuery = Order::query();
        $this->applyPeriodFilter($ordersQuery);
        $totalOrders = (int) $ordersQuery->count();

        // Operational Pulse (Real-time snapshot across all active orders)
        $pulse = [
            'unpaid' => Order::where('payment_status', 'unpaid')->count(),
            'pending' => Order::where('order_status', 'pending')->count(),
            'cooking' => Order::where('order_status', 'in_progress')->count(),
            'ready' => Order::where('order_status', 'ready')->count(),
            'completed' => Order::where('order_status', 'completed')->count(),
        ];

        // Total items sold with period filter
        $itemsQuery = OrderItem::whereHas('order', function ($q) {
            $this->applyPeriodFilter($q);
        });
        $totalItemsSold = (int) $itemsQuery->sum('quantity');

        // AI Conversion Rate (real-time dari data konsultasi AI Barista)
        $consultationsQuery = AiConsultation::query();
        $this->applyPeriodFilter($consultationsQuery);
        $totalConsultations = (int) $consultationsQuery->count();

        $convertedConsultationsQuery = AiConsultation::where('is_converted', true);
        $this->applyPeriodFilter($convertedConsultationsQuery);
        $convertedConsultations = (int) $convertedConsultationsQuery->count();

        $aiConversionRate = $totalConsultations > 0
            ? (int) round(($convertedConsultations / $totalConsultations) * 100)
            : 0;

        // Top Selling Menus
        $topSellingQuery = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_rev'))
            ->whereHas('order', function ($q) {
                $this->applyPeriodFilter($q);
            })
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->with(['menu.category'])
            ->take(5)
            ->get();

        $maxQty = $topSellingQuery->max('total_qty') ?: 1;

        $topSelling = $topSellingQuery->map(function ($item) use ($maxQty, $totalItemsSold) {
            $item->formatted_total_rev = 'Rp ' . number_format((float) $item->total_rev, 0, ',', '.');
            $item->share_pct = $totalItemsSold > 0 ? round(($item->total_qty / $totalItemsSold) * 100) : 0;
            $item->bar_pct = max(15, round(($item->total_qty / $maxQty) * 100));
            return $item;
        });

        // Hourly distribution calculation
        $hourlyData = $this->calculateHourlyDistribution();

        // Recent Orders with search & status filter
        $recentOrdersQuery = Order::with(['table', 'items.menu'])
            ->latest();

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';
            $recentOrdersQuery->where(function ($q) use ($search) {
                $q->where('order_code', 'like', $search)
                    ->orWhere('customer_name', 'like', $search)
                    ->orWhereHas('table', function ($tq) use ($search) {
                        $tq->where('table_number', 'like', $search);
                    });
            });
        }

        if ($this->statusFilter === 'unpaid') {
            $recentOrdersQuery->where('payment_status', 'unpaid');
        } elseif ($this->statusFilter !== 'all') {
            $recentOrdersQuery->where('order_status', $this->statusFilter);
        }

        $recentOrders = $recentOrdersQuery->take(8)->get();

        return view('livewire.admin.AdminAnalytics', [
            'totalRevenue' => $totalRevenue,
            'formattedTotalRevenue' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
            'totalOrders' => $totalOrders,
            'formattedTotalOrders' => number_format($totalOrders, 0, ',', '.'),
            'totalItemsSold' => $totalItemsSold,
            'formattedTotalItemsSold' => number_format($totalItemsSold, 0, ',', '.'),
            'aiConversionRate' => $aiConversionRate,
            'pulse' => $pulse,
            'recentOrders' => $recentOrders,
            'topSelling' => $topSelling,
            'hourlyDistribution' => $hourlyData['slots'],
            'peakHoursText' => $hourlyData['peak_text'],
        ]);
    }

    private function applyPeriodFilter($query): void
    {
        match ($this->period) {
            'today' => $query->where('created_at', '>=', Carbon::today()),
            'week' => $query->where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay()),
            'month' => $query->where('created_at', '>=', Carbon::now()->subDays(30)->startOfDay()),
            default => null,
        };
    }

    private function calculateHourlyDistribution(): array
    {
        $slots = [
            '10:00' => 0,
            '12:00' => 0,
            '14:00' => 0,
            '16:00' => 0,
            '18:00' => 0,
            '20:00' => 0,
            '22:00' => 0,
        ];

        $ordersQuery = Order::query();
        $this->applyPeriodFilter($ordersQuery);
        $orders = $ordersQuery->get(['id', 'created_at']);

        foreach ($orders as $order) {
            if (! $order->created_at) {
                continue;
            }

            $hour = (int) $order->created_at->format('H');

            if ($hour < 12) {
                $slots['10:00']++;
            } elseif ($hour < 14) {
                $slots['12:00']++;
            } elseif ($hour < 16) {
                $slots['14:00']++;
            } elseif ($hour < 18) {
                $slots['16:00']++;
            } elseif ($hour < 20) {
                $slots['18:00']++;
            } elseif ($hour < 22) {
                $slots['20:00']++;
            } else {
                $slots['22:00']++;
            }
        }

        $maxVal = max(array_values($slots));
        $result = [];
        $peakSlot = null;

        $slotRanges = [
            '10:00' => '10:00 - 12:00',
            '12:00' => '12:00 - 14:00',
            '14:00' => '14:00 - 16:00',
            '16:00' => '16:00 - 18:00',
            '18:00' => '18:00 - 20:00',
            '20:00' => '20:00 - 22:00',
            '22:00' => '22:00 - 24:00',
        ];

        foreach ($slots as $hour => $count) {
            $isPeak = $maxVal > 0 && $count === $maxVal;
            if ($isPeak && $peakSlot === null) {
                $peakSlot = $hour;
            }

            $result[] = [
                'hour' => $hour,
                'orders' => $count,
                'pct' => $maxVal > 0 ? max(8, round(($count / $maxVal) * 100)) : 0,
                'is_peak' => $isPeak,
            ];
        }

        $peakText = $maxVal > 0
            ? 'Puncak: ' . ($slotRanges[$peakSlot] ?? $peakSlot) . " ({$maxVal} Pesanan)"
            : 'Puncak: Belum Ada Data (0 Pesanan)';

        return [
            'slots' => $result,
            'peak_text' => $peakText,
        ];
    }
}
