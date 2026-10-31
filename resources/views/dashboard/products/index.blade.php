@extends('dashboard')

@section('content')
<div class="page">
    <header class="topbar">
        <div class="title-group"><button class="mobile-menu" type="button" data-menu-toggle aria-label="Menu"><i data-lucide="menu"></i></button><div class="title-bee">🐝</div><div><h1>{{ __('products.title') }}</h1><p>{{ __('products.subtitle') }}</p></div></div>
        <a class="gold-button" href="{{ route('dashboard.products.create') }}"><i data-lucide="plus"></i>{{ __('products.add') }}</a>
    </header>

    @if(session('success'))<p class="language-status" role="status">{{ session('success') }}</p>@endif
    @if($errors->any())<div class="commerce-alert" role="alert">{{ $errors->first() }}</div>@endif

    <section class="orders-table-wrap">
        <form class="product-search" method="GET" action="{{ route('dashboard.products.index') }}"><input type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('products.search') }}"><button type="submit">{{ __('products.search_button') }}</button></form>
        <div class="overflow-x-auto"><table class="orders-table products-admin-table"><thead><tr><th>{{ __('products.product') }}</th><th>{{ __('products.category') }}</th><th>{{ __('products.variants') }}</th><th>{{ __('products.images') }}</th><th>{{ __('products.status') }}</th><th>{{ __('products.actions') }}</th></tr></thead><tbody>
            @forelse($products as $product)
                <tr>
                    <td><div class="admin-product-cell">@php
                        $primary = $product->product_images->firstWhere('is_primary', true) ?? $product->product_images->first();
                    @endphp
                    @if($primary)<img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($primary->image) }}" alt="{{ $primary->alt ?: $product->name }}">@else<span class="admin-product-placeholder">🍯</span>@endif<div><strong>{{ $product->name }}</strong><small>{{ $product->slug }}</small><div class="product-admin-tags">@if($product->is_pack)<span>{{ __('products.pack') }}</span>@endif @if($product->is_featured)<span class="featured">{{ __('products.featured') }}</span>@endif</div></div></div></td>
                    <td>{{ $product->category?->name ?? '—' }}</td>
                    <td>{{ $product->product_variants->count() }} <small>{{ __('products.variants') }}</small></td>
                    <td>{{ $product->product_images->count() }} <small>{{ __('products.images') }}</small></td>
                    <td><span class="admin-status {{ $product->is_active ? 'active' : 'inactive' }}">{{ $product->is_active ? __('products.active') : __('products.inactive') }}</span></td>
                    <td><div class="product-row-actions"><a href="{{ route('dashboard.products.edit', $product) }}">{{ __('products.edit') }}</a><form action="{{ route('dashboard.products.destroy', $product) }}" method="POST" onsubmit="return confirm(@js(__('products.confirm_delete')))" >@csrf @method('DELETE')<button type="submit">{{ __('products.delete') }}</button></form></div></td>
                </tr>
            @empty
                <tr><td colspan="6" class="orders-empty">{{ __('products.empty') }} <a href="{{ route('dashboard.products.create') }}">{{ __('products.add') }}</a></td></tr>
            @endforelse
        </tbody></table></div>
        <div class="orders-pagination">{{ $products->links() }}</div>
    </section>
</div>
@endsection
