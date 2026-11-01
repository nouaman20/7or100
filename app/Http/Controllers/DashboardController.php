<?php

namespace App\Http\Controllers;

use App\Enums\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filters = $request->validate([
            'from' => ['nullable', 'date', 'before_or_equal:today'],
            'to' => ['nullable', 'date', 'after_or_equal:from', 'before_or_equal:today'],
        ]);

        $today = CarbonImmutable::today();
        $to = CarbonImmutable::parse($filters['to'] ?? $today->toDateString())->endOfDay();
        $from = CarbonImmutable::parse($filters['from'] ?? $to->subDays(29)->toDateString())->startOfDay();
        $periodDays = max(1, (int) $from->diffInDays($to) + 1);
        $previousTo = $from->subDay()->endOfDay();
        $previousFrom = $previousTo->subDays($periodDays - 1)->startOfDay();

        $periodOrders = $this->ordersInPeriod($from, $to);
        $previousOrders = $this->ordersInPeriod($previousFrom, $previousTo);
        $revenue = (float) (clone $periodOrders)->where('status', '!=', OrderStatus::Cancelled->value)->sum('subtotal');
        $previousRevenue = (float) (clone $previousOrders)->where('status', '!=', OrderStatus::Cancelled->value)->sum('subtotal');
        $orderCount = (clone $periodOrders)->count();
        $previousOrderCount = (clone $previousOrders)->count();
        $openOrdersCount = Order::query()
            ->whereIn('status', [OrderStatus::Pending->value, OrderStatus::Confirmed->value, OrderStatus::Shipped->value])
            ->count();
        $activeProductCount = Product::query()->where('is_active', true)->count();
        $metrics = [
            [
                'label' => __('dashboard.revenue_period'),
                'value' => number_format($revenue, 0).' MAD',
                'icon' => 'coins',
                'tone' => 'honey',
                'trend' => $this->trend($revenue, $previousRevenue),
                'footnote' => __('dashboard.vs_previous_period'),
            ],
            [
                'label' => __('dashboard.orders_period'),
                'value' => number_format($orderCount),
                'icon' => 'shopping-bag',
                'tone' => 'gold',
                'trend' => $this->trend((float) $orderCount, (float) $previousOrderCount),
                'footnote' => __('dashboard.vs_previous_period'),
            ],
            [
                'label' => __('dashboard.open_orders'),
                'value' => number_format($openOrdersCount),
                'icon' => 'package-open',
                'tone' => 'green',
                'trend' => null,
                'footnote' => __('dashboard.needs_processing'),
            ],
            [
                'label' => __('dashboard.active_products'),
                'value' => number_format($activeProductCount),
                'icon' => 'boxes',
                'tone' => 'purple',
                'trend' => null,
                'footnote' => __('dashboard.current_catalog'),
            ],
        ];

        $salesChart = $this->salesChart($today);
        $categorySales = $this->categorySales($from, $to);
        $categoryTotal = (float) $categorySales->sum('revenue');
        $categoryColors = ['#ffb000', '#ff7500', '#329547', '#8051bf', '#afb1b2'];
        $donutOffset = 0.0;
        $categories = $categorySales->take(4)->values()->map(function (object $category, int $index) use (&$donutOffset, $categoryTotal, $categoryColors): array {
            $amount = (float) $category->revenue;
            $share = $categoryTotal > 0 ? ($amount / $categoryTotal) * 100 : 0;
            $start = $donutOffset;
            $donutOffset += $share;

            return [
                'name' => $category->category_name ?: __('dashboard.other_category'),
                'amount' => number_format($amount, 0).' MAD',
                'share' => number_format($share, 1).'%',
                'color' => $categoryColors[$index],
                'start' => $start,
                'end' => $donutOffset,
            ];
        });

        if ($categorySales->count() > 4) {
            $otherAmount = (float) $categorySales->slice(4)->sum('revenue');
            $otherShare = $categoryTotal > 0 ? ($otherAmount / $categoryTotal) * 100 : 0;
            $categories->push([
                'name' => __('dashboard.other_category'),
                'amount' => number_format($otherAmount, 0).' MAD',
                'share' => number_format($otherShare, 1).'%',
                'color' => $categoryColors[4],
                'start' => $donutOffset,
                'end' => 100,
            ]);
        }

        $donutSegments = $categories->map(fn (array $category): string => $category['color'].' '.$category['start'].'% '.$category['end'].'%');
        $donutStyle = $donutSegments->isNotEmpty()
            ? 'conic-gradient('.$donutSegments->implode(', ').')'
            : 'conic-gradient(#e8e4dc 0% 100%)';

        $orderStatuses = $this->orderStatuses($from, $to);
        $topProducts = $this->topProducts($from, $to);
        $topProductMaxRevenue = max((float) $topProducts->max('revenue'), 1);
        $recentOrders = Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->limit(6)
            ->get();

        return view('dashboard.home', [
            'locale' => app()->getLocale(),
            'filters' => [
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
            ],
            'metrics' => $metrics,
            'salesChart' => $salesChart,
            'categories' => $categories,
            'categoryTotal' => $categoryTotal,
            'donutStyle' => $donutStyle,
            'orderStatuses' => $orderStatuses,
            'topProducts' => $topProducts,
            'topProductMaxRevenue' => $topProductMaxRevenue,
            'recentOrders' => $recentOrders,
        ]);
    }

    private function ordersInPeriod(CarbonImmutable $from, CarbonImmutable $to): Builder
    {
        return DB::table('orders')
            ->whereBetween('created_at', [$from, $to]);
    }

    private function trend(float $current, float $previous): ?string
    {
        if ($previous <= 0) {
            return null;
        }

        $change = (($current - $previous) / $previous) * 100;

        return ($change > 0 ? '+' : '').number_format($change, 1).'%';
    }

    /** @return array{months: array<int, string>, coordinates: array<int, array{0: string, 1: string}>, points: string, area: string, axis: array<int, string>, last_month: string, last_revenue: float, last_orders: int} */
    private function salesChart(CarbonImmutable $today): array
    {
        $months = [];
        $revenues = [];
        $orderCounts = [];
        $monthStarts = [];

        for ($offset = 11; $offset >= 0; $offset--) {
            $monthStart = $today->startOfMonth()->subMonths($offset);
            $monthEnd = $monthStart->endOfMonth();
            $aggregate = DB::table('orders')
                ->where('status', '!=', OrderStatus::Cancelled->value)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->selectRaw('COALESCE(SUM(subtotal), 0) AS revenue, COUNT(*) AS order_count')
                ->first();

            $monthStarts[] = $monthStart;
            $months[] = $monthStart->locale(app()->getLocale())->translatedFormat('M');
            $revenues[] = (float) $aggregate->revenue;
            $orderCounts[] = (int) $aggregate->order_count;
        }

        $peak = max($revenues) ?: 1;
        $points = [];
        $coordinates = [];

        foreach ($revenues as $index => $amount) {
            $x = ($index / max(count($revenues) - 1, 1)) * 720;
            $y = 185 - (($amount / $peak) * 170);
            $coordinate = [number_format($x, 1, '.', ''), number_format($y, 1, '.', '')];
            $coordinates[] = $coordinate;
            $points[] = implode(',', $coordinate);
        }

        $area = 'M0 185 L'.implode(' L', $points).' L720 185 Z';
        $axis = [];
        for ($step = 4; $step >= 0; $step--) {
            $amount = ($peak / 4) * $step;
            $axis[] = $amount >= 1000 ? number_format($amount / 1000, $amount >= 10000 ? 0 : 1).'k' : number_format($amount, 0);
        }

        $lastIndex = count($revenues) - 1;

        return [
            'months' => $months,
            'coordinates' => $coordinates,
            'points' => implode(' ', $points),
            'area' => $area,
            'axis' => $axis,
            'last_month' => $monthStarts[$lastIndex]->locale(app()->getLocale())->translatedFormat('M Y'),
            'last_revenue' => $revenues[$lastIndex],
            'last_orders' => $orderCounts[$lastIndex],
        ];
    }

    /** @return Collection<int, object> */
    private function categorySales(CarbonImmutable $from, CarbonImmutable $to): Collection
    {
        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->where('orders.status', '!=', OrderStatus::Cancelled->value)
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw('categories.name AS category_name, SUM(order_items.total_price) AS revenue')
            ->groupBy('categories.name')
            ->orderByDesc('revenue')
            ->get();
    }

    /** @return array<int, array{status: string, label: string, count: int, percentage: int}> */
    private function orderStatuses(CarbonImmutable $from, CarbonImmutable $to): array
    {
        $counts = Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->select('status', DB::raw('COUNT(*) AS total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $total = (int) $counts->sum();

        return collect(OrderStatus::cases())->map(fn (OrderStatus $status): array => [
            'status' => $status->value,
            'label' => __('orders.status_'.$status->value),
            'count' => (int) ($counts[$status->value] ?? 0),
            'percentage' => $total > 0 ? (int) round(((int) ($counts[$status->value] ?? 0) / $total) * 100) : 0,
        ])->all();
    }

    /** @return Collection<int, object> */
    private function topProducts(CarbonImmutable $from, CarbonImmutable $to): Collection
    {
        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->where('orders.status', '!=', OrderStatus::Cancelled->value)
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw('COALESCE(products.name, order_items.product_name) AS product_name, SUM(order_items.quantity) AS units_sold, COUNT(DISTINCT order_items.order_id) AS order_count, SUM(order_items.total_price) AS revenue')
            ->groupBy('products.name', 'order_items.product_name', 'order_items.product_id')
            ->orderByDesc('units_sold')
            ->limit(5)
            ->get();
    }
}
