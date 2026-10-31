@extends('dashboard')

@section('content')
    <div class="page">
        <header class="topbar">
            <div class="title-group"><button class="mobile-menu" type="button" data-menu-toggle aria-label="Menu"><i data-lucide="menu"></i></button><div class="title-bee" aria-hidden="true">🐝</div><div><h1>{{ __('orders.orders_title') }}</h1><p>{{ __('orders.orders_subtitle') }}</p></div></div>
        </header>
        @if (session('success'))<p class="language-status" role="status">{{ session('success') }}</p>@endif
        <section class="orders-table-wrap">
            <table class="orders-table"><thead><tr><th>{{ __('orders.order_number') }}</th><th>{{ __('orders.customer') }}</th><th>{{ __('orders.items') }}</th><th>{{ __('orders.total') }}</th><th>{{ __('orders.created_at') }}</th><th>{{ __('orders.status') }}</th></tr></thead><tbody>
                @forelse($orders as $order)
                    <tr><td><strong>{{ $order->order_number }}</strong><small>{{ __('orders.status_'.$order->status) }}</small></td><td>{{ $order->customer_name }}<small>{{ $order->customer_phone }} · {{ $order->customer_city }}</small><small>{{ $order->customer_address }}</small>@if($order->customer_note)<small>{{ __('orders.note') }}: {{ $order->customer_note }}</small>@endif</td><td><ul>@foreach($order->items as $item)<li>{{ $item->product_name }} × {{ $item->quantity }} <small>({{ number_format((float) $item->unit_price, 2) }} {{ __('orders.madd') }})</small></li>@endforeach</ul></td><td><strong>{{ number_format((float) $order->subtotal, 2) }} {{ __('orders.madd') }}</strong></td><td>{{ $order->created_at->format('d/m/Y H:i') }}</td><td><form class="order-status-form" action="{{ route('dashboard.orders.status.update', $order) }}" method="POST">@csrf @method('PATCH')<select name="status" aria-label="{{ __('orders.status') }}">@foreach($statuses as $status)<option value="{{ $status->value }}" @selected($order->status === $status->value)>{{ __('orders.status_'.$status->value) }}</option>@endforeach</select><button type="submit">{{ __('orders.update_status') }}</button></form></td></tr>
                @empty
                    <tr><td colspan="6" class="orders-empty">{{ __('orders.no_orders') }}</td></tr>
                @endforelse
            </tbody></table>
            <div class="orders-pagination">{{ $orders->links() }}</div>
        </section>
    </div>
@endsection
