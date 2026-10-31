@props(['categories'])

<section class="categories-section" id="shop"><div class="store-container"><x-store.section-heading :eyebrow="__('store.categories_eyebrow')" :title="__('store.categories_title')" :copy="__('store.categories_copy')" /><div class="category-grid">@foreach($categories as $category)<x-store.category-card :category="$category" />@endforeach</div></div></section>
