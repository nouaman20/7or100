@props(['testimonial'])

<article class="testimonial"><div class="testimonial-top"><span>{{ $testimonial['initials'] }}</span><div><strong>{{ $testimonial['name'] }}</strong><small>★★★★★</small></div></div><p>“{{ $testimonial['quote'] }}”</p><em>● {{ __('store.verified') }}</em></article>
