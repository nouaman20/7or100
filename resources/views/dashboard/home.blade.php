@extends('dashboard')

@section('content')
    <div class="page">
        <header class="topbar">
            <div class="title-group">
                <button class="mobile-menu" type="button" data-menu-toggle aria-label="Menu"><i data-lucide="menu"></i></button>
                <div class="title-bee" aria-hidden="true">🐝</div>
                <div>
                    <h1>{{ __('dashboard.title') }}</h1>
                    <p>{{ __('dashboard.subtitle') }}</p>
                </div>
            </div>
            <form class="toolbar dashboard-date-filter" method="GET" action="{{ route('dashboard') }}">
                <label>
                    <span class="sr-only">{{ __('dashboard.from') }}</span>
                    <input type="date" name="from" value="{{ $filters['from'] }}" max="{{ $filters['to'] }}">
                </label>
                <span class="date-separator">—</span>
                <label>
                    <span class="sr-only">{{ __('dashboard.to') }}</span>
                    <input type="date" name="to" value="{{ $filters['to'] }}" max="{{ now()->toDateString() }}">
                </label>
                <button class="tool-button" type="submit"><i data-lucide="filter"></i><span>{{ __('dashboard.apply_filter') }}</span></button>
            </form>
        </header>

        @if (session('status'))
            <p class="language-status" role="status">{{ session('status') }}</p>
        @endif

        <section class="kpi-grid" aria-label="{{ __('dashboard.key_metrics') }}">
            @foreach ($metrics as $metric)
                <article class="metric-card {{ $metric['tone'] }}">
                    <div class="metric-head">
                        <div class="metric-icon"><i data-lucide="{{ $metric['icon'] }}"></i></div>
                        <span class="metric-label">{{ $metric['label'] }}</span>
                    </div>
                    <div class="metric-value">{{ $metric['value'] }}</div>
                    <div class="metric-foot">
                        <strong class="metric-trend">{{ $metric['trend'] ?? '—' }}</strong>
                        <span>{{ $metric['footnote'] }}</span>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="upper-grid">
            <article class="panel chart-panel">
                <div class="panel-header">
                    <div class="heading">
                        <div class="heading-badge"><i data-lucide="chart-spline"></i></div>
                        <div>
                            <h2>{{ __('dashboard.sales_growth') }}</h2>
                            <p>{{ __('dashboard.sales_growth_subtitle') }}</p>
                        </div>
                    </div>
                    <div class="legend"><span><i class="dot honey"></i>{{ __('dashboard.sales') }}</span></div>
                </div>
                <div class="line-chart">
                    <div class="grid-lines"><i></i><i></i><i></i><i></i><i></i></div>
                    <div class="axis-y">
                        @foreach ($salesChart['axis'] as $label)
                            <span>{{ $label }}</span>
                        @endforeach
                    </div>
                    <div class="chart-tooltip">{{ $salesChart['last_month'] }}<strong>{{ number_format($salesChart['last_revenue'], 0) }} MAD · {{ number_format($salesChart['last_orders']) }} {{ __('dashboard.orders_short') }}</strong></div>
                    <svg class="chart-svg" viewBox="0 0 720 205" preserveAspectRatio="none" role="img" aria-label="{{ __('dashboard.sales_growth') }}">
                        <defs>
                            <linearGradient id="sales-fill" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0" stop-color="#ffb000" stop-opacity=".28" />
                                <stop offset="1" stop-color="#ffb000" stop-opacity="0" />
                            </linearGradient>
                        </defs>
                        <path d="{{ $salesChart['area'] }}" fill="url(#sales-fill)" />
                        <polyline points="{{ $salesChart['points'] }}" fill="none" stroke="#ffa800" stroke-width="2.2" />
                        @foreach ($salesChart['coordinates'] as [$x, $y])
                            <circle cx="{{ $x }}" cy="{{ $y }}" r="3.1" fill="#ffa800" />
                        @endforeach
                    </svg>
                    <div class="months">
                        @foreach ($salesChart['months'] as $month)
                            <span>{{ $month }}</span>
                        @endforeach
                    </div>
                </div>
            </article>

            <article class="panel category-panel">
                <div class="panel-header">
                    <div class="heading">
                        <div class="heading-badge"><i data-lucide="chart-pie"></i></div>
                        <div>
                            <h2>{{ __('dashboard.revenue_by_category') }}</h2>
                            <p>{{ __('dashboard.period_contribution') }}</p>
                        </div>
                    </div>
                </div>
                <div class="category-body">
                    <div class="donut" style="background: {{ $donutStyle }}">
                        <div class="donut-label"><b>{{ number_format($categoryTotal / 1000, 1) }}k</b><small>{{ __('dashboard.total_mad') }}</small></div>
                    </div>
                    <div class="category-list">
                        @forelse ($categories as $category)
                            <div class="category-item">
                                <i class="dot" style="--dot-color: {{ $category['color'] }}"></i>
                                <div><strong>{{ $category['name'] }}</strong><small>{{ $category['share'] }} ({{ $category['amount'] }})</small></div>
                            </div>
                        @empty
                            <p class="dashboard-empty dashboard-empty-compact">{{ __('dashboard.no_category_sales') }}</p>
                        @endforelse
                    </div>
                </div>
            </article>
        </section>

        <section class="lower-grid dashboard-real-lower-grid">
            <article class="panel funnel-panel status-panel">
                <div class="panel-header">
                    <div class="heading">
                        <div class="heading-badge"><i data-lucide="clipboard-list"></i></div>
                        <div>
                            <h2>{{ __('dashboard.orders_by_status') }}</h2>
                            <p>{{ __('dashboard.selected_period') }}</p>
                        </div>
                    </div>
                </div>
                <div class="funnel-steps">
                    @foreach ($orderStatuses as $index => $status)
                        <div class="funnel-step">
                            <div class="step-icon"><i data-lucide="{{ ['clock-3', 'badge-check', 'truck', 'circle-check', 'ban'][$index] }}"></i></div>
                            <div>
                                <div class="step-title"><span>{{ $status['label'] }}</span><strong>{{ number_format($status['count']) }}</strong></div>
                                <div class="progress"><i style="--level: {{ $status['percentage'] }}%"></i></div>
                            </div>
                            <span class="step-percent">{{ $status['percentage'] }}%</span>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="panel product-panel">
                <div class="panel-header">
                    <div class="heading">
                        <div class="heading-badge"><i data-lucide="sprout"></i></div>
                        <div>
                            <h2>{{ __('dashboard.top_products') }}</h2>
                            <p>{{ __('dashboard.top_products_subtitle') }}</p>
                        </div>
                    </div>
                </div>
                <div class="grid gap-2.5 p-3 pt-4">
                    @forelse ($topProducts as $product)
                        <article class="group grid grid-cols-[minmax(0,1fr)_auto] items-center gap-x-3 gap-y-2 rounded-xl border border-amber-100/80 bg-white/80 p-3 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-amber-300 hover:shadow-md">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-amber-100 text-xs font-bold text-amber-800">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-amber-50 to-orange-100 text-amber-700 ring-1 ring-amber-100"><i data-lucide="package" class="size-5"></i></span>
                                <div class="min-w-0">
                                    <h3 class="truncate text-sm font-semibold text-stone-800" title="{{ $product->product_name }}">{{ $product->product_name }}</h3>
                                    <div class="mt-1 flex flex-wrap items-center gap-x-2 text-[11px] text-stone-500">
                                        <span>{{ number_format((int) $product->units_sold) }} {{ __('dashboard.units_sold') }}</span>
                                        <span aria-hidden="true">·</span>
                                        <span>{{ number_format((int) $product->order_count) }} {{ __('dashboard.orders') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <strong class="block whitespace-nowrap text-sm font-bold text-emerald-800">{{ number_format((float) $product->revenue, 0) }} MAD</strong>
                                <span class="mt-0.5 block text-[10px] text-stone-500">{{ __('dashboard.revenue') }}</span>
                            </div>
                            <div class="col-span-2 h-1.5 overflow-hidden rounded-full bg-amber-50" role="presentation">
                                <span class="block h-full rounded-full bg-gradient-to-r from-amber-400 to-orange-400 transition-[width] duration-300" style="width: {{ min(100, ((float) $product->revenue / $topProductMaxRevenue) * 100) }}%"></span>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-xl border border-dashed border-amber-200 bg-amber-50/50 px-4 py-8 text-center text-sm text-stone-500">{{ __('dashboard.no_product_sales') }}</p>
                    @endforelse
                </div>
            </article>
        </section>

        <section class="panel dashboard-recent-orders">
            <div class="panel-header">
                <div class="heading">
                    <div class="heading-badge"><i data-lucide="receipt-text"></i></div>
                    <div>
                        <h2>{{ __('dashboard.recent_orders') }}</h2>
                        <p>{{ __('dashboard.latest_orders_period') }}</p>
                    </div>
                </div>
                <a class="dashboard-view-all" href="{{ route('dashboard.orders.index') }}">{{ __('dashboard.view_all_orders') }} <i data-lucide="arrow-up-right"></i></a>
            </div>
            <div class="dashboard-table-wrap dashboard-recent-table-wrap">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>{{ __('orders.order_number') }}</th>
                            <th>{{ __('orders.customer') }}</th>
                            <th>{{ __('orders.city') }}</th>
                            <th>{{ __('orders.status') }}</th>
                            <th>{{ __('orders.created_at') }}</th>
                            <th>{{ __('dashboard.total_mad') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->customer_name }}<small>{{ $order->customer_phone }}</small></td>
                                <td>{{ $order->customer_city }}</td>
                                <td><span class="dashboard-order-status status-{{ $order->status }}">{{ __('orders.status_'.$order->status) }}</span></td>
                                <td>{{ $order->created_at->locale($locale)->translatedFormat('d M Y, H:i') }}</td>
                                <td>{{ number_format((float) $order->subtotal, 0) }} MAD</td>
                            </tr>
                        @empty
                            <tr><td class="orders-empty" colspan="6">{{ __('dashboard.no_recent_orders') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
