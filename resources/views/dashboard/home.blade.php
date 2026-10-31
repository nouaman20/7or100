@extends('dashboard')

@section('content')
    <div class="page">
        <header class="topbar">
            <div class="title-group">
                <button class="mobile-menu" type="button" data-menu-toggle aria-label="Menu"><i
                        data-lucide="menu"></i></button>
                <div class="title-bee" aria-hidden="true">🐝</div>
                <div>
                    <h1>{{ __('dashboard.title') }}</h1>
                    <p>{{ __('dashboard.subtitle') }}</p>
                </div>
            </div>
            <div class="toolbar">
                <button class="tool-button date-button" type="button"
                    data-interaction="{{ __('dashboard.date_range') }}"><i
                        data-lucide="calendar-days"></i><span>{{ __('dashboard.date_range') }}</span><i
                        data-lucide="chevron-down"></i></button>
                <button class="tool-button" type="button" data-interaction="{{ __('dashboard.filter') }}"><i
                        data-lucide="funnel"></i><span>{{ __('dashboard.filter') }}</span></button>
                <button class="icon-button" type="button" data-interaction="{{ __('dashboard.notifications') }}"><i
                        data-lucide="bell"></i><b class="badge">3</b></button>
            </div>
        </header>

        @if (session('status'))
            <p class="language-status" role="status">{{ session('status') }}</p>
        @endif

        <section class="kpi-grid" aria-label="Indicateurs clés">
            @foreach (__('dashboard.metrics') as $metric)
                <article class="metric-card {{ $metric['tone'] }}">
                    <div class="metric-head">
                        <div class="metric-icon"><i data-lucide="{{ $metric['icon'] }}"></i></div><span
                            class="metric-label">{{ $metric['label'] }}</span>
                    </div>
                    <div class="metric-value">{{ $metric['value'] }}</div>
                    <div class="metric-foot"><strong
                            class="metric-trend">{{ $metric['trend'] }}</strong><span>{{ __('dashboard.vs_last_month') }}</span>
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
                    <div class="legend"><span><i class="dot honey"></i>{{ __('dashboard.sales') }}</span><span><i
                                class="dot green"></i>{{ __('dashboard.subscribers') }}</span></div>
                </div>
                <div class="line-chart">
                    <div class="grid-lines"><i></i><i></i><i></i><i></i><i></i></div>
                    <div class="axis-y">
                        <span>25k</span><span>20k</span><span>15k</span><span>10k</span><span>5k</span><span>0</span></div>
                    <div class="chart-tooltip">Déc 2025<strong>24.8k MAD</strong></div>
                    <svg class="chart-svg" viewBox="0 0 720 205" preserveAspectRatio="none" role="img"
                        aria-label="{{ __('dashboard.sales_growth') }}">
                        <defs>
                            <linearGradient id="sales-fill" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0" stop-color="#ffb000" stop-opacity=".28" />
                                <stop offset="1" stop-color="#ffb000" stop-opacity="0" />
                            </linearGradient>
                            <linearGradient id="sub-fill" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0" stop-color="#329547" stop-opacity=".22" />
                                <stop offset="1" stop-color="#329547" stop-opacity="0" />
                            </linearGradient>
                        </defs>
                        <path
                            d="M0 139 L65 122 L130 106 L195 91 L260 80 L325 63 L390 49 L455 34 L520 27 L585 20 L650 13 L720 2 L720 185 L0 185Z"
                            fill="url(#sales-fill)" />
                        <path
                            d="M0 173 L65 164 L130 154 L195 145 L260 137 L325 129 L390 122 L455 111 L520 99 L585 87 L650 76 L720 65 L720 185 L0 185Z"
                            fill="url(#sub-fill)" />
                        <polyline
                            points="0,139 65,122 130,106 195,91 260,80 325,63 390,49 455,34 520,27 585,20 650,13 720,2"
                            fill="none" stroke="#ffa800" stroke-width="2.2" />
                        <polyline
                            points="0,173 65,164 130,154 195,145 260,137 325,129 390,122 455,111 520,99 585,87 650,76 720,65"
                            fill="none" stroke="#329547" stroke-width="2.2" />
                        @foreach ([['0', '139'], ['65', '122'], ['130', '106'], ['195', '91'], ['260', '80'], ['325', '63'], ['390', '49'], ['455', '34'], ['520', '27'], ['585', '20'], ['650', '13'], ['720', '2']] as [$x, $y])
                            <circle cx="{{ $x }}" cy="{{ $y }}" r="3.1" fill="#ffa800" />
                        @endforeach
                        @foreach ([['0', '173'], ['65', '164'], ['130', '154'], ['195', '145'], ['260', '137'], ['325', '129'], ['390', '122'], ['455', '111'], ['520', '99'], ['585', '87'], ['650', '76'], ['720', '65']] as [$x, $y])
                            <circle cx="{{ $x }}" cy="{{ $y }}" r="3.1" fill="#329547" />
                        @endforeach
                    </svg>
                    {{-- Return LaTer --}}
                    {{-- <div class="months">
                        @foreach ($months as $month)
                            <span>{{ $month }}</span>
                        @endforeach
                    </div> --}}
                </div>
            </article>

            <article class="panel category-panel">
                <div class="panel-header">
                    <div class="heading">
                        <div class="heading-badge"><i data-lucide="badge-dollar-sign"></i></div>
                        <div>
                            <h2>{{ __('dashboard.revenue_by_category') }}</h2>
                            <p>{{ __('dashboard.contribution') }}</p>
                        </div>
                    </div>
                </div>
                <div class="category-body">
                    <div class="donut">
                        <div class="donut-label"><b>128.5K</b><small>{{ __('dashboard.total_mad') }}</small></div>
                    </div>
                    <div class="category-list">
                        @foreach (__('dashboard.categories') as $category)
                            <div class="category-item"><i class="dot {{ $category['color'] }}"></i>
                                <div><strong>{{ $category['name'] }}</strong><small>{{ $category['share'] }}
                                        ({{ $category['amount'] }})</small></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </article>
        </section>

        <section class="lower-grid">
            <article class="panel funnel-panel">
                <div class="panel-header">
                    <div class="heading">
                        <div class="heading-badge"><i data-lucide="waypoints"></i></div>
                        <div>
                            <h2>{{ __('dashboard.funnel') }}</h2>
                            <p>{{ __('dashboard.funnel_subtitle') }}</p>
                        </div>
                    </div>
                </div>
                <div class="funnel-steps">
                    @foreach (__('dashboard.funnel_steps') as $index => $step)
                        <div class="funnel-step">
                            <div class="step-icon"><i data-lucide="{{ $step['icon'] }}"></i></div>
                            <div>
                                <div class="step-title"><span>{{ $index + 1 }}.
                                        {{ $step['title'] }}</span><strong>{{ $step['number'] }}</strong></div>
                                <div class="progress"><i style="--level: {{ $step['level'] }}%"></i></div>
                            </div><span class="step-percent">{{ $step['percentage'] }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="conversion"><span>🐝 &nbsp;{{ __('dashboard.conversion') }}</span><strong>↗ +4.3%</strong>
                </div>
            </article>

            <article class="panel country-panel">
                <div class="panel-header">
                    <div class="heading">
                        <div class="heading-badge"><i data-lucide="globe-2"></i></div>
                        <div>
                            <h2>{{ __('dashboard.countries') }}</h2>
                            <p>{{ __('dashboard.countries_subtitle') }}</p>
                        </div>
                    </div>
                </div>
                <div class="country-list">
                    @foreach (__('dashboard.countries_data') as $country)
                        <div class="country-item">
                            <div class="flag">{{ $country['flag'] }}</div>
                            <div class="country-data">
                                <div class="country-top">
                                    <b>{{ $country['name'] }}</b><strong>{{ $country['amount'] }}</strong></div>
                                <div class="country-progress-line">
                                    <div class="progress"><i style="--level: {{ $country['level'] }}%"></i></div>
                                    <small>{{ $country['share'] }} {{ __('dashboard.of_total') }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="panel product-panel">
                <div class="panel-header">
                    <div class="heading">
                        <div class="heading-badge"><i data-lucide="sprout"></i></div>
                        <div>
                            <h2>{{ __('dashboard.product_performance') }}</h2>
                            <p>{{ __('dashboard.product_subtitle') }}</p>
                        </div>
                    </div>
                </div>
                <table class="product-table">
                    <thead>
                        <tr>
                            @foreach (__('dashboard.columns') as $column)
                                <th>{{ $column }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (__('dashboard.products_data') as $product)
                            <tr>
                                <td>
                                    <div class="product-name"><span
                                            class="product-art">{{ $product['icon'] }}</span>{{ $product['name'] }}</div>
                                </td>
                                <td>{{ $product['sales'] }}</td>
                                <td>{{ $product['orders'] }}</td>
                                <td>{{ $product['revenue'] }}</td>
                                <td><span
                                        class="status {{ $product['status'] === 'low_stock' ? 'low' : '' }}">{{ __('dashboard.' . $product['status']) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>
        </section>
    </div>
@endsection
