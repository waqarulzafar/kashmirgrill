@props([
    'title',
    'subtitle' => null,
    'badge' => null,
    'class' => '',
    'subtitleClass' => 'text-secondary',
])

<header {{ $attributes->merge(['class' => 'section-header mb-4 ' . $class]) }}>
    @if($badge)
        <span class="badge badge-brand rounded-pill mb-2">{{ $badge }}</span>
    @endif
    <h2 class="h3 fw-semibold section-accent mb-2">{{ $title }}</h2>
    @if($subtitle)
        <p class="section-header-subtitle mb-0 {{ $subtitleClass }}">{{ $subtitle }}</p>
    @endif
</header>
