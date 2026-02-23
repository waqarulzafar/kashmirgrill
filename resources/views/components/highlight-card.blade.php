@props([
    'title',
    'text',
    'ctaLabel' => null,
    'ctaHref' => null,
    'class' => '',
])

<article {{ $attributes->merge(['class' => 'h-100 p-4 bg-white rounded-4 shadow-sm ' . $class]) }}>
    <h3 class="h5 mb-3">{{ $title }}</h3>
    <p class="mb-0 text-secondary">{{ $text }}</p>
    @if($ctaLabel && $ctaHref)
        <a href="{{ $ctaHref }}" class="btn btn-sm btn-brand-outline mt-3">{{ $ctaLabel }}</a>
    @endif
</article>
