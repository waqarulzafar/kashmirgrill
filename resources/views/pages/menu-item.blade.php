@extends('layouts.master')

@section('title', $menuItem->name . ' | Menu | Kashmir Grill House Como')
@section('meta_description', $menuItem->description ?: ('Discover ' . $menuItem->name . ' at Kashmir Grill House Como.'))

@php
    $image = $menuItem->imageUrl() ?? asset('assets/images/menu/main-course.jpg');
    $tags = $menuItem->tagList();
@endphp

@section('content')
    <div class="menu-detail-page py-5">
        <section class="container">
            <div class="menu-detail-shell">
                <div class="row g-4 g-lg-5 align-items-stretch">
                    <div class="col-12 col-lg-6">
                        <div class="menu-detail-media">
                            <img
                                src="{{ $image }}"
                                alt="{{ $menuItem->name }}"
                                class="w-100 h-100"
                                width="960"
                                height="760"
                                loading="eager"
                                decoding="async"
                            >
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="menu-detail-content h-100">
                            <a href="{{ route('menu') }}" class="menu-detail-back">Back to Menu</a>
                            <p class="menu-detail-kicker mb-2">{{ $menuItem->category?->name ?? 'Menu Item' }}</p>
                            <h1 class="menu-detail-title mb-3">{{ $menuItem->name }}</h1>
                            <div class="menu-detail-price mb-3">&euro;{{ number_format((float) $menuItem->price, 2) }}</div>

                            @if($menuItem->description)
                                <p class="menu-detail-copy mb-4">{{ $menuItem->description }}</p>
                            @endif

                            @if(!empty($tags))
                                <div class="menu-detail-tags mb-4">
                                    @foreach($tags as $tag)
                                        <span class="menu-detail-tag">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('book-now') }}" class="menu-detail-btn menu-detail-btn-primary">Reserve Table</a>
                                <a href="{{ route('menu') }}#menu-{{ $menuItem->category?->slug }}" class="menu-detail-btn menu-detail-btn-ghost">View {{ $menuItem->category?->name }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if($relatedItems->isNotEmpty())
            <section class="container pt-4 pt-lg-5">
                <div class="menu-detail-related">
                    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                        <div>
                            <p class="menu-detail-kicker mb-2">More from {{ $menuItem->category?->name }}</p>
                            <h2 class="menu-detail-related-title mb-0">You May Also Like</h2>
                        </div>
                        <a href="{{ route('menu') }}#menu-{{ $menuItem->category?->slug }}" class="menu-detail-back m-0">View Full Category</a>
                    </div>

                    <div class="row g-4">
                        @foreach($relatedItems as $relatedItem)
                            <div class="col-12 col-md-6 col-xl-4">
                                <a href="{{ route('menu.items.show', $relatedItem) }}" class="menu-detail-related-card text-decoration-none">
                                    <div class="menu-detail-related-media">
                                        <img
                                            src="{{ $relatedItem->imageUrl() ?? $image }}"
                                            alt="{{ $relatedItem->name }}"
                                            class="w-100 h-100"
                                            width="640"
                                            height="420"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                    </div>
                                    <div class="menu-detail-related-body">
                                        <h3 class="menu-detail-related-item-title mb-1">{{ $relatedItem->name }}</h3>
                                        <p class="menu-detail-related-price mb-0">&euro;{{ number_format((float) $relatedItem->price, 2) }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .menu-detail-page {
            background:
                radial-gradient(circle at 82% 0%, rgba(219, 29, 48, 0.16), transparent 42%),
                linear-gradient(180deg, #050505 0%, #0b0b0b 100%);
            color: #f5f5f5;
            min-height: calc(100vh - var(--nav-height, 84px));
        }

        .menu-detail-shell,
        .menu-detail-related {
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0)),
                #121212;
            box-shadow: 0 20px 44px rgba(0, 0, 0, 0.22);
            padding: clamp(1rem, 2.6vw, 1.5rem);
        }

        .menu-detail-media,
        .menu-detail-related-media {
            border-radius: 1rem;
            overflow: hidden;
            background: #0f0f0f;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .menu-detail-media {
            min-height: 24rem;
        }

        .menu-detail-media img,
        .menu-detail-related-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .menu-detail-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .menu-detail-back {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            color: rgba(255, 255, 255, 0.72);
            text-decoration: none;
            font: 700 .86rem/1 'Rajdhani', sans-serif;
            letter-spacing: .1em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .menu-detail-kicker {
            color: rgba(255, 255, 255, 0.58);
            font: 700 .82rem/1 'Rajdhani', sans-serif;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .menu-detail-title,
        .menu-detail-related-title {
            color: #fff;
            font-family: 'Bebas Neue', sans-serif;
            line-height: .95;
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        .menu-detail-title {
            font-size: clamp(2.4rem, 5vw, 4.2rem);
        }

        .menu-detail-related-title {
            font-size: clamp(1.8rem, 4vw, 2.8rem);
        }

        .menu-detail-price {
            color: #fff;
            font: 700 1.5rem/1 'Rajdhani', sans-serif;
        }

        .menu-detail-copy {
            color: rgba(255, 255, 255, 0.78);
            font: 500 1rem/1.7 'Rajdhani', sans-serif;
            max-width: 38rem;
        }

        .menu-detail-tags {
            display: flex;
            flex-wrap: wrap;
            gap: .55rem;
        }

        .menu-detail-tag {
            display: inline-flex;
            align-items: center;
            padding: .45rem .75rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.86);
            font: 700 .76rem/1 'Rajdhani', sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .menu-detail-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 3rem;
            padding: .8rem 1.1rem;
            border-radius: .85rem;
            text-decoration: none;
            font: 700 .92rem/1 'Rajdhani', sans-serif;
            letter-spacing: .07em;
            text-transform: uppercase;
            border: 1px solid transparent;
        }

        .menu-detail-btn-primary {
            background: linear-gradient(180deg, #ff2332, #ca0817);
            color: #fff;
        }

        .menu-detail-btn-ghost {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .menu-detail-related-card {
            display: block;
            height: 100%;
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0)),
                #131313;
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.18);
            transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease;
        }

        .menu-detail-related-card:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 255, 255, 0.16);
            box-shadow: 0 22px 40px rgba(0, 0, 0, 0.26);
        }

        .menu-detail-related-media {
            height: 13rem;
        }

        .menu-detail-related-body {
            padding: 1rem;
        }

        .menu-detail-related-item-title {
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .menu-detail-related-price {
            color: rgba(255, 255, 255, 0.76);
            font: 700 1rem/1 'Rajdhani', sans-serif;
        }
    </style>
@endpush
