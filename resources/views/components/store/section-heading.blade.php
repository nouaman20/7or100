@props(['eyebrow', 'title', 'copy' => null])

<header class="store-section-heading"><span>{{ $eyebrow }}</span><h2>{{ $title }}</h2>@if($copy)<p>{{ $copy }}</p>@endif</header>
