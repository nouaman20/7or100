@props(['testimonials'])

<section class="testimonials-section"><div class="store-container"><x-store.section-heading :eyebrow="__('store.reviews_title')" :title="__('store.reviews_title')" :copy="__('store.reviews_copy')" /><div class="testimonials-grid">@foreach($testimonials as $testimonial)<x-store.testimonial-card :testimonial="$testimonial" />@endforeach</div></div></section>
