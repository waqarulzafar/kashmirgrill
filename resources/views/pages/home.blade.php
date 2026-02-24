@extends('layouts.master')

@section('title', 'Home | Kashmir Grill House')
@section('meta_description', 'Discover Kashmir Grill House for premium South Asian dining, signature menu highlights, and effortless table reservations.')
@section('body_class', 'home-menu-theme')

@section('hero')
    @include('partials.home.hero')
@endsection

@section('content')
    <div class="home-premium-shell" data-home-experience data-no-reveal>
        <div class="home-scroll-progress" aria-hidden="true"><span data-home-progress></span></div>
        @include('partials.home.featured-selections-slider')
        @include('partials.home.experience-promise')
        @include('partials.home.booking-journey')
        @include('partials.home.menu-showcase')
        @include('partials.home.brand-marquee')
        @include('partials.home.discovery-story')
        @include('partials.home.events-grid')
        @include('partials.home.chef-spotlight')
        @include('partials.home.gallery')
        @include('partials.home.testimonials')
    </div>
@endsection

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rajdhani:wght@400;500;600;700&display=swap');

        body.home-menu-theme {
            background:
                radial-gradient(circle at 86% -12%, rgba(219, 29, 48, 0.22), transparent 42%),
                radial-gradient(circle at 12% 8%, rgba(255, 149, 44, 0.14), transparent 45%),
                linear-gradient(180deg, #050505 0%, #090909 34%, #0d0d0d 100%);
            color: #f2f2f2;
        }

        body.home-menu-theme main {
            background: transparent;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        body.home-menu-theme .hero-ready {
            padding-bottom: 0;
        }

        body.home-menu-theme .section-accent::before {
            background: linear-gradient(180deg, var(--brand-red), var(--brand-green));
        }

        .home-premium-shell {
            position: relative;
        }

        .home-marquee {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.07);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0)),
                rgba(12, 12, 12, 0.72);
            box-shadow: 0 14px 32px rgba(0, 0, 0, 0.18);
        }

        .home-featured-slider {
            position: relative;
            border-radius: 1.1rem;
        }

        .home-featured-slider__top {
            padding: .15rem .25rem 0;
        }

        .home-featured-slider__eyebrow {
            color: rgba(255, 255, 255, 0.62);
            font: 700 .78rem/1 'Rajdhani', sans-serif;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        .home-featured-slider__title {
            color: #fff;
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2rem, 4.2vw, 3.4rem);
            line-height: .95;
            letter-spacing: .02em;
            text-transform: uppercase;
            max-width: 14ch;
        }

        .home-featured-slider__indicators {
            gap: .55rem;
        }

        .home-featured-slider__indicators [data-bs-target] {
            width: .7rem;
            height: .7rem;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.28);
            background: rgba(255, 255, 255, 0.12);
            opacity: 1;
            margin: 0;
            transition: transform .2s ease, background-color .2s ease, border-color .2s ease;
        }

        .home-featured-slider__indicators [data-bs-target].active {
            transform: scale(1.12);
            border-color: rgba(255, 149, 44, .75);
            background: linear-gradient(180deg, rgba(219, 29, 48, .95), rgba(255, 149, 44, .92));
            box-shadow: 0 0 10px rgba(219, 29, 48, 0.3);
        }

        .home-featured-slider__card {
            position: relative;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.07);
            background:
                radial-gradient(circle at 86% 14%, rgba(255, 149, 44, 0.12), transparent 40%),
                radial-gradient(circle at 10% 92%, rgba(219, 29, 48, 0.12), transparent 42%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0)),
                rgba(10, 10, 10, 0.72);
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            padding: clamp(1rem, 2vw, 1.35rem);
        }

        .home-featured-slider__content {
            padding: .35rem;
        }

        .home-featured-slider__kicker,
        .home-featured-slider__badge {
            display: inline-flex;
            align-items: center;
            min-height: 32px;
            border-radius: 999px;
            padding: .35rem .75rem;
            font: 700 .72rem/1 'Rajdhani', sans-serif;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #fff;
        }

        .home-featured-slider__kicker {
            border: 1px solid rgba(255, 255, 255, .08);
            background: rgba(255, 255, 255, .04);
        }

        .home-featured-slider__badge {
            border: 1px solid rgba(255, 149, 44, .26);
            background: rgba(255, 149, 44, .1);
            color: rgba(255, 255, 255, 0.95);
        }

        .home-featured-slider__slide-title {
            color: #fff;
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            line-height: .95;
            letter-spacing: .02em;
            text-transform: uppercase;
            margin-bottom: .35rem;
        }

        .home-featured-slider__slide-subtitle {
            color: rgba(255, 255, 255, 0.72);
            font: 500 1rem/1.45 'Rajdhani', sans-serif;
            max-width: 40ch;
        }

        .home-featured-slider__price-list {
            display: grid;
            gap: .9rem;
        }

        .home-featured-slider__price-item {
            padding: .8rem .95rem;
            border-radius: .9rem;
            border: 1px solid rgba(255, 255, 255, .06);
            background: rgba(255, 255, 255, .02);
        }

        .home-featured-slider__price-item p {
            color: rgba(255, 255, 255, .62);
            font: 500 .92rem/1.35 'Rajdhani', sans-serif;
            margin-top: .35rem;
        }

        .home-featured-slider__price-head {
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: .55rem;
        }

        .home-featured-slider__dish-name {
            color: #fff;
            font: 700 1rem/1.1 'Rajdhani', sans-serif;
            letter-spacing: .02em;
        }

        .home-featured-slider__dish-line {
            height: 1px;
            background: linear-gradient(90deg, rgba(255, 255, 255, .06), rgba(255, 149, 44, .35), rgba(255, 255, 255, .06));
        }

        .home-featured-slider__dish-price {
            color: rgba(255, 149, 44, .95);
            font: 700 .95rem/1 'Rajdhani', sans-serif;
            letter-spacing: .04em;
            white-space: nowrap;
        }

        .home-featured-slider__visual {
            position: relative;
            min-height: 360px;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.07);
            background:
                radial-gradient(circle at 20% 80%, rgba(219, 29, 48, 0.18), transparent 50%),
                radial-gradient(circle at 86% 16%, rgba(255, 149, 44, 0.16), transparent 45%),
                rgba(8, 8, 8, 0.58);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.02), 0 18px 34px rgba(0,0,0,.22);
            overflow: hidden;
            backdrop-filter: blur(8px);
        }

        .home-featured-slider__visual::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(160deg, rgba(255,255,255,.04), transparent 55%);
            pointer-events: none;
        }

        .home-featured-slider__visual-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px dashed rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }

        .home-featured-slider__visual-ring--lg {
            width: 290px;
            height: 290px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .home-featured-slider__visual-ring--sm {
            width: 160px;
            height: 160px;
            top: 2rem;
            right: 1rem;
        }

        .home-featured-slider__visual-disc {
            position: absolute;
            width: min(290px, 72vw);
            height: min(290px, 72vw);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, .1);
            box-shadow: 0 20px 44px rgba(0, 0, 0, 0.28);
            will-change: transform;
        }

        .home-featured-slider__visual-disc img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transform: scale(1.04);
        }

        .home-featured-slider__visual-chip {
            position: absolute;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            min-height: 34px;
            padding: .35rem .75rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .08);
            background: rgba(8, 8, 8, .78);
            color: #fff;
            font: 700 .72rem/1 'Rajdhani', sans-serif;
            letter-spacing: .1em;
            text-transform: uppercase;
            box-shadow: 0 10px 18px rgba(0, 0, 0, 0.18);
            will-change: transform;
        }

        .home-featured-slider__visual-chip::before {
            content: '';
            width: .42rem;
            height: .42rem;
            border-radius: 50%;
            background: linear-gradient(180deg, var(--brand-red), var(--brand-orange));
            box-shadow: 0 0 8px rgba(219, 29, 48, 0.35);
        }

        .home-featured-slider__visual-chip--a {
            left: .95rem;
            bottom: 1rem;
        }

        .home-featured-slider__visual-chip--b {
            right: 1rem;
            top: 1rem;
        }

        .home-featured-slider__nav {
            width: 42px;
            height: 42px;
            top: calc(50% + .85rem);
            transform: translateY(-50%);
            opacity: 1;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(8, 8, 8, 0.74);
            box-shadow: 0 12px 22px rgba(0, 0, 0, 0.2);
        }

        .home-featured-slider__nav.carousel-control-prev {
            left: -8px;
        }

        .home-featured-slider__nav.carousel-control-next {
            right: -8px;
        }

        .home-featured-slider__nav .carousel-control-prev-icon,
        .home-featured-slider__nav .carousel-control-next-icon {
            width: 1.05rem;
            height: 1.05rem;
            filter: brightness(0) invert(1);
        }

        .home-marquee::before,
        .home-marquee::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 4rem;
            z-index: 2;
            pointer-events: none;
        }

        .home-marquee::before {
            left: 0;
            background: linear-gradient(90deg, rgba(12, 12, 12, 1), rgba(12, 12, 12, 0));
        }

        .home-marquee::after {
            right: 0;
            background: linear-gradient(270deg, rgba(12, 12, 12, 1), rgba(12, 12, 12, 0));
        }

        .home-marquee__track {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: max-content;
            padding: .9rem 1rem;
            animation: homeMarquee 26s linear infinite;
            will-change: transform;
        }

        .home-marquee__track span {
            display: inline-flex;
            align-items: center;
            gap: .7rem;
            white-space: nowrap;
            color: #fff;
            font: 700 .9rem/1 'Rajdhani', sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .home-marquee__track span::after {
            content: '';
            width: .45rem;
            height: .45rem;
            border-radius: 50%;
            background: linear-gradient(180deg, var(--brand-red), var(--brand-green));
            box-shadow: 0 0 10px rgba(219, 29, 48, 0.35);
        }

        @keyframes homeMarquee {
            from { transform: translateX(0); }
            to { transform: translateX(-33%); }
        }

        .home-discovery-story__intro {
            max-width: 60rem;
            margin-inline: auto;
        }

        .home-discovery-story__eyebrow {
            color: rgba(255, 255, 255, 0.62);
            font: 700 .78rem/1 'Rajdhani', sans-serif;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        .home-discovery-story__headline {
            color: #fff;
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2rem, 4.4vw, 3.6rem);
            line-height: .95;
            letter-spacing: .02em;
            text-transform: uppercase;
            margin-inline: auto;
            max-width: 19ch;
        }

        .home-discovery-story__brand {
            color: rgba(255, 149, 44, 0.92);
            font: 700 1rem/1 'Rajdhani', sans-serif;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        .home-discovery-story__visual-shell {
            position: relative;
            min-height: 320px;
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.07);
            background:
                radial-gradient(circle at 18% 82%, rgba(219, 29, 48, 0.16), transparent 45%),
                radial-gradient(circle at 84% 18%, rgba(255, 149, 44, 0.14), transparent 42%),
                rgba(10, 10, 10, 0.72);
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .home-discovery-story__ring {
            position: absolute;
            border-radius: 50%;
            border: 1px dashed rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }

        .home-discovery-story__ring--one {
            width: 280px;
            height: 280px;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .home-discovery-story__ring--two {
            width: 390px;
            height: 390px;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            opacity: .65;
        }

        .home-discovery-story__disc {
            position: absolute;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.1);
            background: #111;
            box-shadow: 0 18px 34px rgba(0, 0, 0, 0.28);
            will-change: transform;
            transform: translateZ(0);
        }

        .home-discovery-story__disc img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transform: scale(1.05);
        }

        .home-discovery-story__disc--main {
            width: 220px;
            height: 220px;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 3;
        }

        .home-discovery-story__disc--left {
            width: 120px;
            height: 120px;
            left: calc(50% - 180px);
            top: calc(50% + 25px);
            z-index: 2;
        }

        .home-discovery-story__disc--right {
            width: 126px;
            height: 126px;
            left: calc(50% + 62px);
            top: calc(50% - 126px);
            z-index: 2;
        }

        .home-discovery-story__panel {
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,0)),
                #121212;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.18);
            padding: 1rem;
        }

        .home-discovery-story__panel h3 {
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        .home-discovery-story__cta-box {
            height: 100%;
            border-radius: .95rem;
            border: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(9, 9, 9, 0.5);
            padding: 1rem;
        }

        .home-discovery-story__icon {
            width: 3rem;
            height: 3rem;
            border-radius: .8rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: .75rem;
            background: linear-gradient(145deg, rgba(219, 29, 48, .22), rgba(255, 149, 44, .18));
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #fff;
            font: 700 .95rem/1 'Rajdhani', sans-serif;
            letter-spacing: .08em;
        }

        .home-scroll-progress {
            position: fixed;
            top: var(--nav-height, 84px);
            left: 0;
            right: 0;
            height: 3px;
            z-index: 1040;
            pointer-events: none;
            background: rgba(255, 255, 255, 0.04);
        }

        .home-scroll-progress > span {
            display: block;
            width: 100%;
            height: 100%;
            transform: scaleX(0);
            transform-origin: 0 50%;
            background: linear-gradient(90deg, #db1d30, #ff952c);
            box-shadow: 0 0 14px rgba(219, 29, 48, 0.38);
        }

        body.home-menu-theme .hero-signature {
            min-height: clamp(560px, 78vh, 860px);
            background: #050505;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

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
            transform: translateZ(0);
            will-change: transform;
            backface-visibility: hidden;
        }

        .hero-signature__veil {
            background:
                radial-gradient(circle at 80% 18%, rgba(255, 149, 44, 0.24), transparent 44%),
                radial-gradient(circle at 16% 85%, rgba(219, 29, 48, 0.26), transparent 48%),
                linear-gradient(120deg, rgba(0, 0, 0, 0.84) 0%, rgba(0, 0, 0, 0.7) 42%, rgba(0, 0, 0, 0.46) 100%);
            z-index: 1;
        }

        .hero-signature__embers {
            position: absolute;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            overflow: hidden;
        }

        .hero-signature__embers span {
            position: absolute;
            bottom: -8%;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            opacity: .28;
            background: radial-gradient(circle, rgba(255, 196, 137, 0.95), rgba(219, 29, 48, 0.45), rgba(219, 29, 48, 0));
        }

        .hero-signature__embers span:nth-child(1) { left: 6%; width: 6px; height: 6px; }
        .hero-signature__embers span:nth-child(2) { left: 14%; width: 9px; height: 9px; }
        .hero-signature__embers span:nth-child(3) { left: 26%; width: 5px; height: 5px; }
        .hero-signature__embers span:nth-child(4) { left: 34%; width: 8px; height: 8px; }
        .hero-signature__embers span:nth-child(5) { left: 46%; width: 6px; height: 6px; }
        .hero-signature__embers span:nth-child(6) { left: 57%; width: 10px; height: 10px; }
        .hero-signature__embers span:nth-child(7) { left: 68%; width: 6px; height: 6px; }
        .hero-signature__embers span:nth-child(8) { left: 78%; width: 8px; height: 8px; }
        .hero-signature__embers span:nth-child(9) { left: 88%; width: 5px; height: 5px; }
        .hero-signature__embers span:nth-child(10) { left: 94%; width: 7px; height: 7px; }

        .hero-signature__content {
            z-index: 2;
            padding-top: clamp(2rem, 4vw, 3rem);
            padding-bottom: clamp(2rem, 4vw, 3rem);
            color: #fff;
        }

        .hero-signature__aside {
            display: grid;
            gap: 1rem;
            align-items: start;
        }

        .hero-signature__visual {
            position: relative;
            min-height: 320px;
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                radial-gradient(circle at 88% 16%, rgba(255, 149, 44, 0.16), transparent 42%),
                radial-gradient(circle at 14% 85%, rgba(219, 29, 48, 0.2), transparent 50%),
                rgba(8, 8, 8, 0.55);
            box-shadow: 0 20px 42px rgba(0, 0, 0, 0.28);
            overflow: hidden;
            backdrop-filter: blur(8px);
        }

        .hero-signature__visual::before,
        .hero-signature__visual::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            border: 1px dashed rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }

        .hero-signature__visual::before {
            width: 240px;
            height: 240px;
            right: 1.2rem;
            top: 1rem;
        }

        .hero-signature__visual::after {
            width: 120px;
            height: 120px;
            left: 1rem;
            bottom: 1rem;
        }

        .hero-signature__disc {
            position: absolute;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.28);
            will-change: transform;
            transform: translateZ(0);
        }

        .hero-signature__disc img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transform: scale(1.04);
        }

        .hero-signature__disc--main {
            width: min(270px, 64vw);
            height: min(270px, 64vw);
            right: 1.2rem;
            top: 1rem;
        }

        .hero-signature__disc--small {
            width: min(120px, 32vw);
            height: min(120px, 32vw);
            left: 1rem;
            bottom: 1.1rem;
        }

        .hero-signature__disc-ring {
            position: absolute;
            inset: 6px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.12);
            pointer-events: none;
        }

        .hero-signature__float-pill {
            position: absolute;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(10, 10, 10, 0.78);
            color: #fff;
            padding: .35rem .65rem;
            font: 700 .72rem/1 'Rajdhani', sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
            box-shadow: 0 10px 18px rgba(0, 0, 0, 0.2);
            will-change: transform;
        }

        .hero-signature__float-pill::before {
            content: '';
            width: .4rem;
            height: .4rem;
            border-radius: 50%;
            background: linear-gradient(180deg, var(--brand-red), var(--brand-orange));
            box-shadow: 0 0 8px rgba(255, 149, 44, 0.35);
        }

        .hero-signature__float-pill--one {
            right: 0.85rem;
            bottom: 1.05rem;
        }

        .hero-signature__float-pill--two {
            left: 0.95rem;
            top: 1rem;
        }

        body.home-menu-theme .hero-signature__content .badge-brand {
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 26px rgba(0, 0, 0, 0.28);
        }

        body.home-menu-theme .hero-signature h1 {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: .02em;
            line-height: .94;
            font-size: clamp(2.8rem, 7vw, 5.6rem);
            text-transform: uppercase;
            text-shadow: 0 10px 28px rgba(0, 0, 0, 0.35);
        }

        body.home-menu-theme .hero-signature .lead {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 500;
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: rgba(255, 255, 255, 0.82) !important;
            max-width: 46rem;
        }

        .hero-signature__panel {
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0.01)),
                rgba(8, 8, 8, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 44px rgba(0, 0, 0, 0.28);
            backdrop-filter: blur(10px);
            will-change: transform;
        }

        .hero-signature__panel h2 {
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .hero-signature__panel li {
            color: rgba(255, 255, 255, 0.88);
            position: relative;
            padding-left: 1.25rem;
            font-family: 'Rajdhani', sans-serif;
        }

        .hero-signature__panel li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0.5rem;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--brand-green);
        }

        body.home-menu-theme section:not(.hero-signature) {
            position: relative;
            background: transparent;
            color: #fff;
        }

        body.home-menu-theme section:not(.hero-signature) > .container {
            position: relative;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        body.home-menu-theme section:not(.hero-signature) > .container::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.06);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0)),
                rgba(15, 15, 15, 0.72);
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.2);
            pointer-events: none;
        }

        body.home-menu-theme section:not(.hero-signature) > .container > * {
            position: relative;
            z-index: 1;
        }

        body.home-menu-theme .badge-brand {
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        body.home-menu-theme .section-accent {
            color: #fff;
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: .03em;
            font-size: clamp(1.7rem, 2.8vw, 2.6rem);
            line-height: .96;
        }

        body.home-menu-theme .section-header-subtitle,
        body.home-menu-theme .text-secondary {
            color: rgba(255, 255, 255, 0.68) !important;
        }

        body.home-menu-theme .highlight-card,
        body.home-menu-theme [class*="highlight-card"] {
            --hx: 0deg;
            --hy: 0deg;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0)),
                #131313 !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #fff !important;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.18);
            transform: perspective(900px) rotateX(var(--hx)) rotateY(var(--hy));
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        }

        .home-stat-card {
            --hx: 0deg;
            --hy: 0deg;
            height: 100%;
            padding: 1rem;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                radial-gradient(circle at 100% 0%, rgba(255, 149, 44, 0.09), transparent 45%),
                radial-gradient(circle at 0% 100%, rgba(219, 29, 48, 0.1), transparent 45%),
                #131313;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.18);
            transform: perspective(900px) rotateX(var(--hx)) rotateY(var(--hy));
            transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease;
        }

        .home-stat-card:hover {
            border-color: rgba(255, 255, 255, 0.16);
            box-shadow: 0 18px 34px rgba(0, 0, 0, 0.24);
        }

        .home-stat-card__label {
            color: rgba(255, 255, 255, 0.64);
            font: 700 .78rem/1 'Rajdhani', sans-serif;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .home-stat-card__value {
            color: #fff;
            font: 700 1.25rem/1.05 'Rajdhani', sans-serif;
            letter-spacing: .02em;
            text-transform: uppercase;
            margin-bottom: .55rem;
        }

        .home-feature-panel {
            --hx: 0deg;
            --hy: 0deg;
            border-radius: 1rem;
            padding: .75rem;
            background:
                linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,0)),
                #121212;
            border: 1px solid rgba(255, 255, 255, 0.07);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.18);
            transform: perspective(900px) rotateX(var(--hx)) rotateY(var(--hy));
            transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease;
        }

        .home-feature-panel:hover {
            border-color: rgba(255,255,255,.14);
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.26);
        }

        .home-feature-panel__media {
            border-radius: .9rem;
            overflow: hidden;
            background: #0d0d0d;
            min-height: 220px;
            border: 1px solid rgba(255,255,255,.05);
        }

        .home-feature-panel__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transform: scale(1.02);
            transition: transform .35s ease;
            will-change: transform;
        }

        .home-feature-panel:hover .home-feature-panel__media img {
            transform: scale(1.06);
        }

        .home-feature-panel__body {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: .5rem .25rem;
        }

        .home-feature-panel__body h3 {
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .home-feature-panel__kicker {
            color: rgba(255,255,255,.62);
            font: 700 .75rem/1 'Rajdhani', sans-serif;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .home-journey-cta {
            padding: 1rem;
            border-radius: 1rem;
            border: 1px solid rgba(255,255,255,.06);
            background: rgba(12,12,12,.45);
        }

        .home-step-card {
            --hx: 0deg;
            --hy: 0deg;
            display: grid;
            grid-template-columns: auto 1fr;
            gap: .9rem;
            align-items: start;
            padding: 1rem;
            border-radius: 1rem;
            border: 1px solid rgba(255,255,255,.08);
            background:
                linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,0)),
                #131313;
            box-shadow: 0 14px 28px rgba(0,0,0,.18);
            transform: perspective(900px) rotateX(var(--hx)) rotateY(var(--hy));
            transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease;
        }

        .home-step-card:hover {
            border-color: rgba(255,255,255,.16);
            box-shadow: 0 18px 34px rgba(0,0,0,.24);
        }

        .home-step-card__index {
            min-width: 3.15rem;
            height: 3.15rem;
            border-radius: .9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, rgba(219, 29, 48, .22), rgba(255, 149, 44, .2));
            border: 1px solid rgba(255,255,255,.08);
            color: #fff;
            font: 700 1rem/1 'Rajdhani', sans-serif;
            letter-spacing: .12em;
        }

        .home-step-card h3 {
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .dishes-gallery__info-card {
            height: 100%;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                radial-gradient(circle at 92% 12%, rgba(255, 149, 44, 0.12), transparent 40%),
                radial-gradient(circle at 8% 90%, rgba(219, 29, 48, 0.12), transparent 45%),
                #121212;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.18);
            padding: 1rem;
        }

        .dishes-gallery__info-kicker,
        .dishes-gallery__service-kicker {
            color: rgba(255, 255, 255, 0.62);
            font: 700 .75rem/1 'Rajdhani', sans-serif;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .dishes-gallery__info-card ul li {
            color: rgba(255, 255, 255, 0.8);
            position: relative;
            padding-left: 1rem;
            margin-bottom: .45rem;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 500;
        }

        .dishes-gallery__info-card ul li:last-child {
            margin-bottom: 0;
        }

        .dishes-gallery__info-card ul li::before {
            content: '';
            position: absolute;
            left: 0;
            top: .48rem;
            width: .4rem;
            height: .4rem;
            border-radius: 50%;
            background: linear-gradient(180deg, var(--brand-red), var(--brand-green));
        }

        .dishes-gallery__chip {
            display: inline-flex;
            align-items: center;
            padding: .33rem .6rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.03);
            color: rgba(255, 255, 255, 0.88);
            font: 700 .72rem/1 'Rajdhani', sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        body.home-menu-theme .highlight-card h3,
        body.home-menu-theme .highlight-card .h5 {
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        body.home-menu-theme .highlight-card p {
            color: rgba(255, 255, 255, 0.72) !important;
        }

        .chef-spotlight {
            border: 1px solid rgba(219, 29, 48, 0.15);
            background: #fff;
            position: relative;
        }

        body.home-menu-theme .chef-spotlight {
            border-color: rgba(255, 255, 255, 0.07);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0)),
                #111;
            box-shadow: 0 18px 42px rgba(0, 0, 0, 0.22);
        }

        .chef-spotlight__content {
            background: linear-gradient(180deg, #fff 0%, #fff7ef 100%);
        }

        body.home-menu-theme .chef-spotlight__content {
            background:
                radial-gradient(circle at 95% 10%, rgba(255, 149, 44, 0.12), transparent 40%),
                radial-gradient(circle at 8% 88%, rgba(219, 29, 48, 0.14), transparent 45%),
                #131313;
            color: #fff;
        }

        body.home-menu-theme .chef-spotlight__content h2,
        body.home-menu-theme .chef-spotlight__content strong {
            color: #fff;
        }

        .chef-spotlight__line strong {
            font-weight: 600;
        }

        .chef-spotlight__line span {
            color: var(--brand-red);
            font-weight: 600;
        }

        body.home-menu-theme .chef-spotlight__line p {
            color: rgba(255, 255, 255, 0.7) !important;
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
            border: 1px solid rgba(255, 149, 44, 0.25);
            transition: box-shadow .25s ease, opacity .55s ease, filter .55s ease;
            will-change: transform;
        }

        body.home-menu-theme .dish-tile {
            --hx: 0deg;
            --hy: 0deg;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0)),
                #131313;
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #fff;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.18);
            transform: perspective(900px) rotateX(var(--hx)) rotateY(var(--hy));
        }

        body.home-menu-theme .dish-tile h3 {
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        body.home-menu-theme .dish-tile p {
            color: rgba(255, 255, 255, 0.72);
        }

        .dish-tile--featured {
            padding: 1.1rem !important;
        }

        .dish-tile--featured .dish-visual {
            height: 250px;
        }

        .dish-tile--featured h3 {
            font-size: 1.35rem;
        }

        .dish-tile--compact .dish-visual {
            height: 175px;
        }

        .dish-meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
        }

        .dish-meta-chip {
            display: inline-flex;
            align-items: center;
            padding: .3rem .52rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.03);
            color: rgba(255, 255, 255, 0.84);
            font: 700 .68rem/1 'Rajdhani', sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .dish-feature-points {
            display: flex;
            flex-wrap: wrap;
            gap: .45rem;
        }

        .dish-feature-points span {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            color: rgba(255, 255, 255, 0.78);
            font: 600 .82rem/1 'Rajdhani', sans-serif;
        }

        .dish-feature-points span::before {
            content: '';
            width: .35rem;
            height: .35rem;
            border-radius: 50%;
            background: var(--brand-green);
            box-shadow: 0 0 10px rgba(255, 149, 44, 0.28);
        }

        .dishes-gallery__service-strip {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                linear-gradient(90deg, rgba(219, 29, 48, 0.08), rgba(255, 149, 44, 0.08)),
                #121212;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.18);
            padding: 1rem;
        }

        .dishes-gallery__service-strip h3 {
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .dish-tile:hover {
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
        }

        body.home-menu-theme .dish-tile:hover {
            box-shadow: 0 1.25rem 2.25rem rgba(0, 0, 0, 0.28);
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
            border: 1px solid rgba(255, 149, 44, 0.45);
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

        body.home-menu-theme blockquote {
            --hx: 0deg;
            --hy: 0deg;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0)),
                #131313 !important;
            color: #fff !important;
            border-color: rgba(255, 149, 44, 0.25) !important;
            transform: perspective(900px) rotateX(var(--hx)) rotateY(var(--hy));
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        }

        body.home-menu-theme blockquote p {
            color: rgba(255, 255, 255, 0.86);
        }

        body.home-menu-theme blockquote footer {
            color: rgba(255, 255, 255, 0.62) !important;
        }

        body.home-menu-theme .btn-brand:hover,
        body.home-menu-theme .btn-brand:focus {
            box-shadow: 0 0 0 4px rgba(219, 29, 48, 0.16), 0 .8rem 1.35rem rgba(0, 0, 0, 0.18);
        }

        body.home-menu-theme .btn-brand-outline:hover,
        body.home-menu-theme .btn-brand-outline:focus {
            box-shadow: 0 0 0 4px rgba(255, 149, 44, 0.18), 0 .7rem 1.2rem rgba(0, 0, 0, 0.16);
        }

        @media (max-width: 991.98px) {
            body.home-menu-theme section:not(.hero-signature) > .container::before {
                border-radius: 1rem;
            }

            .home-featured-slider__top {
                padding-inline: 0;
            }

            .home-featured-slider__title {
                max-width: 100%;
            }

            .home-featured-slider__visual {
                min-height: 300px;
                backdrop-filter: blur(6px);
            }

            .home-featured-slider__visual-ring--lg {
                width: 230px;
                height: 230px;
            }

            .home-featured-slider__visual-disc {
                width: min(235px, 68vw);
                height: min(235px, 68vw);
            }

            .home-featured-slider__nav {
                display: none;
            }

            .home-feature-panel__media {
                min-height: 180px;
            }

            .dish-tile--featured .dish-visual {
                height: 220px;
            }

            .hero-signature__visual {
                min-height: 280px;
            }

            .hero-signature__disc--main {
                width: min(235px, 62vw);
                height: min(235px, 62vw);
            }

            .home-discovery-story__visual-shell {
                min-height: 280px;
            }

            .home-discovery-story__ring--one {
                width: 220px;
                height: 220px;
            }

            .home-discovery-story__ring--two {
                width: 305px;
                height: 305px;
            }

            .home-discovery-story__disc--main {
                width: 180px;
                height: 180px;
            }

            .home-discovery-story__disc--left {
                width: 100px;
                height: 100px;
                left: calc(50% - 145px);
                top: calc(50% + 10px);
            }

            .home-discovery-story__disc--right {
                width: 104px;
                height: 104px;
                left: calc(50% + 42px);
                top: calc(50% - 100px);
            }
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

            .home-feature-panel__media img {
                transition: none !important;
            }

            .home-marquee__track {
                animation: none;
            }

            .home-scroll-progress {
                display: none;
            }

            .hero-signature__float-pill {
                animation: none !important;
            }

            .home-discovery-story__disc {
                animation: none !important;
            }
        }

        body[data-performance-mode="lite"] .hero-signature__embers {
            display: none;
        }

        body[data-performance-mode="lite"] .hero-signature__float-pill {
            display: none;
        }

        body[data-performance-mode="lite"] .hero-signature__visual,
        body[data-performance-mode="lite"] .hero-signature__panel {
            backdrop-filter: none;
        }

        body[data-performance-mode="lite"] .home-featured-slider__visual {
            backdrop-filter: none;
        }

        body[data-performance-mode="lite"] .home-featured-slider__visual-ring {
            display: none;
        }

        body[data-performance-mode="lite"] .home-marquee__track,
        body[data-performance-mode="lite"] .chef-spotlight__image,
        body[data-performance-mode="lite"] .dish-float .dish-visual,
        body[data-performance-mode="lite"] .dish-float-delay .dish-visual {
            animation: none !important;
        }

        body[data-performance-mode="lite"] .home-scroll-progress {
            display: none;
        }

        body[data-performance-mode="lite"] .home-discovery-story__ring {
            display: none;
        }
    </style>
@endpush
