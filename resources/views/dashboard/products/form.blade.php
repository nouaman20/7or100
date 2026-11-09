@extends('dashboard')

@section('content')
@php
    $editing = $product->exists;
@endphp
<div class="page">
    <header class="topbar">
        <div class="title-group"><a class="product-back-link" href="{{ route('dashboard.products.index') }}" aria-label="{{ __('products.back') }}"><i data-lucide="arrow-left"></i></a><div class="title-bee">🐝</div><div><h1>{{ $editing ? __('products.edit_title') : __('products.create_title') }}</h1><p>{{ __('products.form_subtitle') }}</p></div></div>
    </header>

    @if($errors->any())<div class="commerce-alert" role="alert"><strong>{{ __('products.fix_errors') }}</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <form class="product-admin-form" action="{{ $editing ? route('dashboard.products.update', $product) : route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($editing)@method('PUT')@endif

        <section class="product-form-panel">
            <div class="product-panel-heading"><span>01</span><div><h2>{{ __('products.product_details') }}</h2><p>{{ __('products.product_details_help') }}</p></div></div>
            <div class="product-form-grid">
                <label>{{ __('products.name') }}<input name="name" value="{{ old('name', $product->name) }}" required maxlength="255" autocomplete="off"></label>
                <label>{{ __('products.slug') }}<input name="slug" value="{{ old('slug', $product->slug) }}" required maxlength="255" autocomplete="off"></label>
                <label class="wide">{{ __('products.category') }}<select name="category_id" required><option value="">{{ __('products.choose_category') }}</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) === $category->id)>{{ $category->name }}</option>@endforeach</select>@if($categories->isEmpty())<small>{{ __('products.no_categories') }} <a href="{{ route('dashboard.categories.index') }}">{{ __('products.manage_categories') }}</a></small>@endif</label>
                <label class="wide">{{ __('products.description') }}<textarea name="description" rows="4">{{ old('description', $product->description) }}</textarea></label>
                <div class="product-option-grid wide">
                    <label class="product-option-card">
                        <span class="product-option-copy"><span class="product-option-icon"><i data-lucide="eye"></i></span><span class="product-option-text"><strong>{{ __('products.active') }}</strong><small>{{ __('products.active_help') }}</small></span></span>
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))>
                        <span class="product-option-switch" aria-hidden="true"></span>
                    </label>
                    <label class="product-option-card">
                        <span class="product-option-copy"><span class="product-option-icon"><i data-lucide="star"></i></span><span class="product-option-text"><strong>{{ __('products.featured') }}</strong><small>{{ __('products.featured_help') }}</small></span></span>
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false))>
                        <span class="product-option-switch" aria-hidden="true"></span>
                    </label>
                    <label class="product-option-card">
                        <span class="product-option-copy"><span class="product-option-icon"><i data-lucide="package-check"></i></span><span class="product-option-text"><strong>{{ __('products.is_pack') }}</strong><small>{{ __('products.is_pack_help') }}</small></span></span>
                        <input type="checkbox" name="is_pack" value="1" @checked(old('is_pack', $product->is_pack ?? false))>
                        <span class="product-option-switch" aria-hidden="true"></span>
                    </label>
                </div>
            </div>
        </section>

        <section class="product-form-panel">
            <div class="product-panel-heading"><span>02</span><div><h2>{{ __('products.images') }}</h2><p>{{ __('products.images_help') }}</p></div></div>
            <div class="existing-product-images">
                @foreach($product->product_images as $image)
                    <article class="product-image-editor">
                        <img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($image->image) }}" alt="{{ $image->alt ?: $product->name }}">
                        <label class="image-primary"><input type="radio" name="primary_image" value="{{ $image->id }}" @checked(old('primary_image', $product->product_images->firstWhere('is_primary', true)?->id ?? $product->product_images->first()?->id) === $image->id)> {{ __('products.primary') }}</label>
                        <label>{{ __('products.alt') }}<input type="text" name="image_alts[existing_{{ $image->id }}]" value="{{ $image->alt }}" maxlength="255"></label>
                        <label>{{ __('products.sort_order') }}<input type="number" name="image_orders[existing_{{ $image->id }}]" value="{{ $image->sort_order }}" min="0" max="9999"></label>
                        <label class="image-remove"><input type="checkbox" name="remove_images[]" value="{{ $image->id }}">{{ __('products.remove_image') }}</label>
                    </article>
                @endforeach
            </div>
            <label class="image-upload-label">{{ __('products.add_images') }}<input id="productImageFiles" type="file" name="images[]" accept="image/*" multiple><small>{{ __('products.image_limit') }}</small></label>
            <div class="new-product-images" id="newProductImages"></div>
        </section>

        <section class="product-form-panel">
            <div class="product-panel-heading"><span>03</span><div><h2>{{ __('products.variants') }}</h2><p>{{ __('products.variants_help') }} {{ __('products.variants_optional') }}</p></div><button type="button" class="product-add-row" id="addVariant"><i data-lucide="plus"></i>{{ __('products.add_variant') }}</button></div>
            <div class="variant-editor-list" id="variantEditorList">
                @foreach($product->product_variants as $variant)
                    <article class="variant-editor-row" data-variant-row>
                        <input type="hidden" name="variants[{{ $variant->id }}][is_active]" value="0"><label>{{ __('products.variant_name') }}<input name="variants[{{ $variant->id }}][name]" value="{{ $variant->name }}" required></label>
                        <label>{{ __('products.weight_grams') }}<input type="number" name="variants[{{ $variant->id }}][weight_grams]" value="{{ $variant->weight_grams }}" min="1" required></label>
                        <label>{{ __('products.price') }}<input type="number" name="variants[{{ $variant->id }}][price]" value="{{ $variant->price }}" min="0" step="0.01" required></label>
                        <label>{{ __('products.stock') }}<input type="number" name="variants[{{ $variant->id }}][stock_quantity]" value="{{ $variant->stock_quantity }}" min="0" placeholder="{{ __('products.unlimited_stock') }}"></label>
                        <label class="product-check"><input type="checkbox" name="variants[{{ $variant->id }}][is_active]" value="1" @checked($variant->is_active)>{{ __('products.active') }}</label>
                        <label class="variant-remove-check"><input type="checkbox" name="remove_variants[]" value="{{ $variant->id }}">{{ __('products.remove_variant') }}</label>
                    </article>
                @endforeach
            </div>
        </section>

        <div class="product-form-actions"><a href="{{ route('dashboard.products.index') }}">{{ __('products.cancel') }}</a><button type="submit" class="gold-button"><i data-lucide="save"></i>{{ $editing ? __('products.save_changes') : __('products.create_title') }}</button></div>
    </form>
</div>

<template id="variantTemplate"><article class="variant-editor-row" data-variant-row><label>{{ __('products.variant_name') }}<input data-name="name" required placeholder="500 g"></label><label>{{ __('products.weight_grams') }}<input data-name="weight_grams" type="number" min="1" required placeholder="500"></label><label>{{ __('products.price') }}<input data-name="price" type="number" min="0" step="0.01" required placeholder="139.00"></label><label>{{ __('products.stock') }}<input data-name="stock_quantity" type="number" min="0" placeholder="{{ __('products.unlimited_stock') }}"></label><label class="product-check"><input data-name="is_active" type="checkbox" value="1" checked>{{ __('products.active') }}</label><button type="button" class="variant-remove-button" data-remove-new>{{ __('products.remove_variant') }}</button></article></template>

<template id="imageTemplate"><article class="new-image-editor"><img data-preview alt=""><label class="image-primary"><input data-primary type="radio" name="primary_image">{{ __('products.primary') }}</label><label>{{ __('products.alt') }}<input data-alt type="text" maxlength="255"></label><label>{{ __('products.sort_order') }}<input data-order type="number" min="0" max="9999"></label></article></template>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let variantIndex = 0;
    const variants = document.getElementById('variantEditorList');
    document.getElementById('addVariant')?.addEventListener('click', () => {
        const row = document.getElementById('variantTemplate').content.cloneNode(true);
        row.querySelectorAll('[data-name]').forEach((field) => field.name = `variants[new_${variantIndex}][${field.dataset.name}]`);
        variants.append(row);
        variantIndex++;
    });
    variants?.addEventListener('click', (event) => {
        if (event.target.closest('[data-remove-new]')) event.target.closest('[data-variant-row]').remove();
    });

    const files = document.getElementById('productImageFiles');
    const newImages = document.getElementById('newProductImages');
    files?.addEventListener('change', () => {
        newImages.replaceChildren();
        Array.from(files.files).slice(0, 10).forEach((file, index) => {
            const row = document.getElementById('imageTemplate').content.cloneNode(true);
            row.querySelector('[data-preview]').src = URL.createObjectURL(file);
            row.querySelector('[data-primary]').value = `new:${index}`;
            row.querySelector('[data-primary]').checked = document.querySelectorAll('.product-image-editor').length === 0 && index === 0;
            row.querySelector('[data-alt]').name = `image_alts[${index}]`;
            row.querySelector('[data-order]').name = `image_orders[${index}]`;
            row.querySelector('[data-order]').value = document.querySelectorAll('.product-image-editor').length + index;
            newImages.append(row);
        });
    });
});
</script>
@endsection
