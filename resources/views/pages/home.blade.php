@extends('layouts.master')

@section('title', 'Home | Kashmir Grill House')
@section('meta_description', 'Discover Kashmir Grill House for premium South Asian dining, signature menu highlights, and effortless table reservations.')

@section('hero')
    @include('partials.home.hero')
@endsection

@section('content')
    @include('partials.home.events-grid')
    @include('partials.home.chef-spotlight')
    @include('partials.home.menu-showcase')
    @include('partials.home.gallery')
    @include('partials.home.testimonials')
@endsection

@push('styles')
    <style>
        .hero-signature {
            min-height: clamp(520px, 72vh, 760px);
            isolation: isolate;
        }

        .hero-signature__media,
        .hero-signature__veil {
            position: absolute;
            inset: 0;
        }

        .hero-signature__video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .hero-signature__veil {
            background:
                radial-gradient(circle at 78% 22%, rgba(248, 155, 32, 0.26), transparent 45%),
                linear-gradient(120deg, rgba(0, 0, 0, 0.76) 0%, rgba(0, 0, 0, 0.62) 40%, rgba(0, 0, 0, 0.4) 100%);
            z-index: 1;
        }

        .hero-signature__content {
            z-index: 2;
            padding-top: clamp(2rem, 4vw, 3rem);
            padding-bottom: clamp(2rem, 4vw, 3rem);
            color: #fff;
        }

        .hero-signature__panel {
            background: rgba(8, 8, 8, 0.68);
            border: 1px solid rgba(248, 155, 32, 0.28);
            backdrop-filter: blur(8px);
        }

        .hero-signature__panel h2 {
            color: #fff;
        }

        .hero-signature__panel li {
            color: rgba(255, 255, 255, 0.88);
            position: relative;
            padding-left: 1.25rem;
        }

        .hero-signature__panel li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0.5rem;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--brand-orange);
        }

        .chef-spotlight {
            border: 1px solid rgba(224, 29, 48, 0.15);
            background: #fff;
            position: relative;
        }

        .chef-spotlight__content {
            background: linear-gradient(180deg, #fff 0%, #fff7ef 100%);
        }

        .chef-spotlight__line strong {
            font-weight: 600;
        }

        .chef-spotlight__line span {
            color: var(--brand-red);
            font-weight: 600;
        }

        .chef-spotlight__image-wrap {
            min-height: 100%;
            background: #111;
        }

        .chef-spotlight__image {
            width: 100%;
            height: 100%;
            min-height: 320px;
            object-fit: cover;
            display: block;
            transform-origin: 52% 52%;
            animation: dishRotateFloat 9s ease-in-out infinite;
        }

        .carousel-item:not(.active) .chef-spotlight__image {
            animation-play-state: paused;
        }

        .chef-spotlight__indicators [data-bs-target] {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: 0;
            background-color: rgba(0, 0, 0, 0.22);
        }

        .chef-spotlight__indicators .active {
            background-color: var(--brand-red);
        }

        @keyframes dishRotateFloat {
            0% { transform: rotate(-2.1deg) scale(1.02); }
            50% { transform: rotate(2.1deg) scale(1.04); }
            100% { transform: rotate(-2.1deg) scale(1.02); }
        }

        .dish-tile {
            background: linear-gradient(145deg, #fff, #fff4e8);
            border: 1px solid rgba(248, 155, 32, 0.25);
            transition: box-shadow .25s ease, opacity .55s ease, filter .55s ease;
            will-change: transform;
        }

        .dish-tile:hover {
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
        }

        .dish-visual {
            position: relative;
            height: 190px;
            border-radius: 0.9rem;
            overflow: hidden;
            background-color: #0f0f0f;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.08);
        }

        .dish-visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .dish-visual-label {
            position: absolute;
            left: 0.85rem;
            top: 0.85rem;
            z-index: 2;
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(248, 155, 32, 0.45);
            color: #fff;
            font-size: 0.72rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            font-weight: 600;
            border-radius: 999px;
            padding: 0.25rem 0.65rem;
        }

        .dish-float .dish-visual {
            animation: dishFloat 4.2s ease-in-out infinite;
        }

        .dish-float-delay .dish-visual {
            animation: dishFloat 4.2s ease-in-out .9s infinite;
        }

        @keyframes dishFloat {
            0% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0); }
        }

        @media (prefers-reduced-motion: reduce) {
            .chef-spotlight__image {
                animation: none !important;
                transform: none !important;
            }

            .dish-float .dish-visual,
            .dish-float-delay .dish-visual {
                animation: none;
            }

            .dish-tile {
                transition: none;
            }
        }
    </style>
@endpush
