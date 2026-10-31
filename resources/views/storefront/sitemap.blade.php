<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ([route('storefront.home'), route('storefront.shop'), route('storefront.about'), route('storefront.contact'), route('storefront.blog.index')] as $url)
        <url><loc>{{ $url }}</loc></url>
    @endforeach
    @foreach ($posts as $post)
        <url><loc>{{ route('storefront.blog.show', $post->slug) }}</loc><lastmod>{{ $post->updated_at->toAtomString() }}</lastmod></url>
    @endforeach
    @foreach ($products as $product)
        <url><loc>{{ route('storefront.products.show', $product->slug) }}</loc><lastmod>{{ $product->updated_at->toAtomString() }}</lastmod></url>
    @endforeach
</urlset>
