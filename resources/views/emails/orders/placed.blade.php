<x-mail::message>
# {{ __('orders.email_title') }}

**{{ __('orders.order_number') }}:** {{ $order->order_number }}  
**{{ __('orders.customer') }}:** {{ $order->customer_name }}  
**{{ __('orders.phone') }}:** {{ $order->customer_phone }}  
**{{ __('orders.city') }}:** {{ $order->customer_city }}  
**{{ __('orders.address') }}:** {{ $order->customer_address }}

@if ($order->customer_note)
**{{ __('orders.note') }}:** {{ $order->customer_note }}
@endif

## {{ __('orders.items') }}

@foreach ($order->items as $item)
- {{ $item->product_name }} × {{ $item->quantity }} — {{ number_format((float) $item->unit_price, 2) }} {{ __('orders.madd') }}
@endforeach

**{{ __('orders.total') }}:** {{ number_format((float) $order->subtotal, 2) }} {{ __('orders.madd') }}

{{ config('app.name') }}
</x-mail::message>
