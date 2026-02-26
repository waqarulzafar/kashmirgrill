<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $defaultSite = 'Kashmir Grill House';
        $businessPhone = '+39 351 1203141';
        $businessPhoneHref = '+393511203141';
        $businessAddressLine = 'Via Milano, 253, 22100 Como, Italy';
        $instagramUrl = 'https://www.instagram.com/kashmirgrillhouse_?utm_source=qr&igsh=ZjJmZDhtZHQzZ2l6';
        $facebookUrl = 'https://www.facebook.com/share/1CVDdWNQJy/';
        $tiktokUrl = 'https://www.tiktok.com/@kashmirgrillhouse';
        $googleBusinessUrl = 'https://share.google/grft1lwOxyW4px1OV';
        $pageTitle = trim($__env->yieldContent('meta_title', $__env->yieldContent('title', $defaultSite)));
        $pageDescription = trim($__env->yieldContent('meta_description', 'Kashmir Grill House in Como serves halal Pakistani and Indian specialties with dine-in, takeaway, and delivery options.'));
        $ogImage = trim($__env->yieldContent('og_image', asset('assets/images/logo.png')));
        $ogType = trim($__env->yieldContent('og_type', 'website'));
        $canonicalUrl = trim($__env->yieldContent('canonical_url', url()->current()));
        $restaurantSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => 'Kashmir Grill House',
            'url' => url('/'),
            'image' => [$ogImage],
            'telephone' => $businessPhone,
            'servesCuisine' => ['Pakistani', 'Indian', 'South Asian', 'Halal', 'Grill'],
            'priceRange' => '€€',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Via Milano, 253',
                'postalCode' => '22100',
                'addressLocality' => 'Como',
                'addressRegion' => 'CO',
                'addressCountry' => 'IT',
            ],
            'sameAs' => [
                $instagramUrl,
                $facebookUrl,
                $tiktokUrl,
                $googleBusinessUrl,
            ],
        ];
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ $defaultSite }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <link id="favicon" rel="icon" type="image/png" href="{{ asset('favicon/favicon-32x32.png') }}">
    <meta name="theme-color" content="#000000">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <script type="application/ld+json">{!! json_encode($restaurantSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <style>
        :root {
            --nav-height: 84px;
            --accent-primary: var(--brand-red, #db1d30);
            --accent-secondary: var(--brand-green, #ff952c);
        }

        html.lenis,
        html.lenis body {
            height: auto;
        }

        .lenis.lenis-smooth {
            scroll-behavior: auto !important;
        }

        .lenis.lenis-stopped {
            overflow: hidden;
        }

        .lenis.lenis-smooth [data-lenis-prevent] {
            overscroll-behavior: contain;
        }

        body {
            background: linear-gradient(180deg, #fff, var(--brand-ivory));
            color: #202020;
        }

        .navbar-premium {
            position: sticky;
            top: 0;
            z-index: 1030;
            min-height: var(--nav-height);
            background:
                linear-gradient(180deg, rgba(0, 0, 0, 0.96), rgba(7, 7, 7, 0.92));
            border-bottom: 1px solid rgba(219, 29, 48, 0.3);
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.22);
        }

        .navbar-premium .navbar-brand,
        .navbar-premium .nav-link {
            color: #fff;
            font-weight: 500;
        }

        .navbar-premium .nav-link {
            position: relative;
            padding: 0.78rem 0.92rem;
            font-size: 0.98rem;
            letter-spacing: 0.02em;
        }

        .navbar-premium .nav-link::after {
            content: '';
            position: absolute;
            left: 0.75rem;
            right: 0.75rem;
            bottom: 0.2rem;
            height: 2px;
            background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
            transform: scaleX(0);
            transform-origin: left center;
            transition: transform .25s ease;
        }

        .navbar-premium .nav-link:hover,
        .navbar-premium .nav-link:focus {
            color: #fff;
        }

        .navbar-premium .nav-link:hover::after,
        .navbar-premium .nav-link:focus::after,
        .navbar-premium .nav-link.active::after {
            transform: scaleX(1);
        }

        .navbar-premium .navbar-toggler {
            box-shadow: none;
        }

        .navbar-premium .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(255, 149, 44, 0.22);
        }

        .navbar-premium .btn.btn-sm {
            --bs-btn-padding-y: 0.55rem;
            --bs-btn-padding-x: 1rem;
            --bs-btn-font-size: 0.92rem;
            --bs-btn-border-radius: 0.75rem;
        }

        .navbar-premium .badge-brand {
            padding: 0.5rem 0.86rem;
            font-size: 0.76rem;
            letter-spacing: 0.08em;
        }

        .navbar-premium .dropdown-toggle::before {
            content: '';
            position: absolute;
            left: 0.75rem;
            right: 0.75rem;
            top: 0.4rem;
            height: 1px;
            background: rgba(255, 255, 255, 0.06);
            opacity: 0;
            transition: opacity .2s ease;
        }

        .navbar-premium .dropdown-toggle:hover::before,
        .navbar-premium .dropdown-toggle:focus::before,
        .navbar-premium .dropdown.show .dropdown-toggle::before {
            opacity: 1;
        }

        .navbar-premium .dropdown-menu {
            border-radius: 0.95rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: linear-gradient(180deg, rgba(20, 20, 20, 0.98), rgba(10, 10, 10, 0.98));
            box-shadow: 0 18px 34px rgba(0, 0, 0, 0.35);
            padding: 0.4rem;
            min-width: 15rem;
        }

        @media (min-width: 992px) {
            .navbar-premium .nav-item.dropdown .dropdown-menu {
                top: 62px !important;
                margin-top: 0;
                display: block;
                opacity: 0;
                visibility: hidden;
                transition: opacity .2s ease, visibility .2s ease;
            }

            .navbar-premium .nav-item.dropdown:hover > .dropdown-menu,
            .navbar-premium .nav-item.dropdown:focus-within > .dropdown-menu {
                opacity: 1;
                visibility: visible;
            }

            .navbar-premium .nav-item.dropdown:hover > .nav-link,
            .navbar-premium .nav-item.dropdown:focus-within > .nav-link {
                color: #fff;
            }
        }

        .navbar-premium .dropdown-item {
            color: rgba(255, 255, 255, 0.88);
            border-radius: 0.65rem;
            padding: 0.55rem 0.8rem;
            font-weight: 500;
        }

        .navbar-premium .dropdown-item:hover,
        .navbar-premium .dropdown-item:focus {
            color: #fff;
            background: linear-gradient(90deg, rgba(219, 29, 48, 0.18), rgba(255, 149, 44, 0.18));
        }

        .navbar-premium .dropdown-divider {
            border-color: rgba(255, 255, 255, 0.08);
            margin: 0.35rem 0;
        }

        .navbar-premium .nav-link.active,
        .navbar-premium .dropdown.show .dropdown-toggle,
        .navbar-premium .nav-link.active:hover {
            color: #fff;
        }

        .hero-ready {
            padding-top: 0;
            padding-bottom: clamp(3rem, 6vw, 5rem);
        }

        .section-accent {
            position: relative;
            padding-left: 1rem;
        }

        .section-accent::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.2em;
            bottom: 0.2em;
            width: 4px;
            border-radius: 6px;
            background: linear-gradient(180deg, var(--brand-red), var(--brand-orange));
        }

        .btn-brand,
        .btn-brand-outline {
            transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
        }

        .btn-brand:hover,
        .btn-brand:focus {
            transform: translateY(-1px);
            box-shadow: 0 0 0 4px rgba(219, 29, 48, 0.18), 0 .7rem 1.2rem rgba(0, 0, 0, 0.15);
        }

        .btn-brand-outline:hover,
        .btn-brand-outline:focus {
            transform: translateY(-1px);
            box-shadow: 0 0 0 4px rgba(255, 149, 44, 0.2), 0 .55rem 1.1rem rgba(0, 0, 0, 0.12);
        }

        .site-footer {
            background-color: var(--brand-black);
            color: rgba(255, 255, 255, 0.86);
        }

        .site-preloader {
            position: fixed;
            inset: 0;
            z-index: 3000;
            display: grid;
            place-items: center;
            background:
                radial-gradient(circle at 50% 30%, rgba(219, 29, 48, 0.18), transparent 42%),
                radial-gradient(circle at 35% 70%, rgba(255, 149, 44, 0.08), transparent 45%),
                rgba(0, 0, 0, 0.94);
            transition: opacity .35s ease, visibility .35s ease;
        }

        .site-preloader.is-hiding {
            opacity: 0;
            visibility: hidden;
        }

        body.preloader-active {
            overflow: hidden;
        }

        .site-preloader__inner {
            display: grid;
            justify-items: center;
            gap: .9rem;
            padding: 1rem;
            text-align: center;
        }

        .site-preloader__icon {
            width: min(180px, 34vw);
            filter: drop-shadow(0 12px 24px rgba(0, 0, 0, 0.35));
            transform-origin: 50% 60%;
            animation: preloader-pop 1.5s ease-in-out infinite;
        }

        .site-preloader__icon .preloader-flame-main {
            transform-origin: 50% 34%;
            animation: preloader-flame 1s ease-in-out infinite;
        }

        .site-preloader__icon .preloader-flame-accent {
            transform-origin: 50% 40%;
            animation: preloader-flame 1.1s ease-in-out infinite reverse;
        }

        .site-preloader__text {
            color: #fff;
            font: 700 clamp(.95rem, 1.6vw, 1.1rem)/1.2 'Poppins', sans-serif;
            letter-spacing: .02em;
        }

        .site-preloader__text strong {
            color: #ff3a3f;
            font-weight: 800;
        }

        .site-preloader__dots {
            display: inline-flex;
            gap: .14rem;
            margin-left: .2rem;
        }

        .site-preloader__dots span {
            width: .24rem;
            height: .24rem;
            border-radius: 50%;
            background: rgba(255,255,255,.95);
            animation: preloader-dot 1s ease-in-out infinite;
        }

        .site-preloader__dots span:nth-child(2) { animation-delay: .14s; }
        .site-preloader__dots span:nth-child(3) { animation-delay: .28s; }

        @keyframes preloader-flame {
            0%, 100% { transform: rotate(-2deg) scale(0.98); }
            50% { transform: rotate(2deg) scale(1.03); }
        }

        @keyframes preloader-pop {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }

        @keyframes preloader-dot {
            0%, 80%, 100% { opacity: .35; transform: translateY(0); }
            40% { opacity: 1; transform: translateY(-2px); }
        }

        .footer-transfer-banner {
            position: relative;
            margin-bottom: 2rem;
            border-radius: 1.1rem;
            overflow: hidden;
            background:
                radial-gradient(circle at 18% 22%, rgba(255, 255, 255, 0.04), transparent 38%),
                radial-gradient(circle at 78% 28%, rgba(219, 29, 48, 0.14), transparent 44%),
                radial-gradient(circle at 65% 84%, rgba(255, 149, 44, 0.12), transparent 46%),
                linear-gradient(180deg, #0b0b0b 0%, #070707 100%);
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.28);
        }

        .footer-transfer-banner::before,
        .footer-transfer-banner::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: clamp(14px, 1.8vw, 24px);
            background: linear-gradient(180deg, #ef2528, #d8161b);
            z-index: 1;
        }

        .footer-transfer-banner::before { left: 0; }
        .footer-transfer-banner::after { right: 0; }

        .footer-transfer-banner__inner {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1.35fr 1fr auto;
            gap: 1rem;
            align-items: center;
            padding: 1.35rem clamp(1.35rem, 2.2vw, 2.6rem);
            padding-left: clamp(2.25rem, 4vw, 3.8rem);
            padding-right: clamp(2.25rem, 4vw, 3.8rem);
        }

        .footer-transfer-banner__title-top {
            color: rgba(255, 255, 255, 0.94);
            font: 600 clamp(1rem, 2.2vw, 1.25rem)/1.2 'Poppins', sans-serif;
            margin: 0 0 .15rem;
        }

        .footer-transfer-banner__title-main {
            color: #fff;
            font: 800 clamp(1.55rem, 3.4vw, 2.3rem)/.95 'Poppins', sans-serif;
            letter-spacing: -0.02em;
            margin: 0 0 .7rem;
        }

        .footer-transfer-banner__offer-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .65rem .75rem;
        }

        .footer-transfer-banner__offer-label {
            color: #fff;
            font: 800 clamp(1.55rem, 3.6vw, 2.45rem)/1 'Poppins', sans-serif;
            letter-spacing: -0.03em;
            margin: 0;
        }

        .footer-transfer-banner__offer-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 54px;
            padding: .7rem 1.5rem;
            border-radius: 1.1rem;
            background: linear-gradient(180deg, #ff2c35, #e61d24);
            color: #fff;
            font: 800 clamp(1.1rem, 2.15vw, 1.6rem)/1 'Poppins', sans-serif;
            letter-spacing: -0.02em;
            box-shadow: 0 10px 24px rgba(230, 29, 36, 0.25);
            white-space: nowrap;
        }

        .footer-transfer-banner__road-wrap {
            display: grid;
            gap: .75rem;
        }

        .footer-transfer-banner__road {
            position: relative;
            height: 58px;
            overflow: hidden;
        }

        .footer-transfer-banner__road-line {
            position: absolute;
            left: 0;
            right: 0;
            top: 50%;
            height: 6px;
            transform: translateY(-50%);
            border-radius: 999px;
            background: linear-gradient(90deg, #ef2528, #ff4247);
        }

        .footer-transfer-banner__car-shell {
            position: absolute;
            top: 50%;
            left: 0;
            width: 150px;
            height: 44px;
            transform: translate(-105%, -50%);
            animation: footer-car-run 8.5s linear infinite;
            will-change: transform;
        }

        .footer-transfer-banner__car-body {
            position: absolute;
            inset: 8px 2px 6px;
            border-radius: 16px 18px 14px 14px;
            background: linear-gradient(180deg, #ff4a4f 0%, #d5151f 100%);
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.32);
        }

        .footer-transfer-banner__car-body::before {
            content: '';
            position: absolute;
            left: 20px;
            right: 22px;
            top: -10px;
            height: 18px;
            border-radius: 16px 22px 6px 6px;
            background: linear-gradient(180deg, #ff6165 0%, #ea232c 100%);
            border: 1px solid rgba(255, 255, 255, 0.14);
        }

        .footer-transfer-banner__car-window {
            position: absolute;
            top: 5px;
            height: 9px;
            border-radius: 4px;
            background: linear-gradient(180deg, rgba(255,255,255,.9), rgba(210,230,255,.65));
            opacity: .88;
            z-index: 1;
        }

        .footer-transfer-banner__car-window--front { left: 48px; width: 26px; }
        .footer-transfer-banner__car-window--rear { left: 77px; width: 21px; }

        .footer-transfer-banner__car-wheel {
            position: absolute;
            bottom: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #0f0f0f;
            border: 2px solid #2f2f2f;
            box-shadow: inset 0 0 0 3px #8f8f8f;
        }

        .footer-transfer-banner__car-wheel::before {
            content: '';
            position: absolute;
            inset: 5px;
            border-radius: 50%;
            background: #ddd;
            opacity: .9;
        }

        .footer-transfer-banner__car-wheel--front { left: 28px; }
        .footer-transfer-banner__car-wheel--rear { right: 22px; }

        .footer-transfer-banner__car-logo {
            position: absolute;
            left: 56px;
            top: 18px;
            color: rgba(255, 255, 255, 0.9);
            font: 700 .5rem/1 'Poppins', sans-serif;
            letter-spacing: .04em;
            text-transform: lowercase;
        }

        .footer-transfer-banner__copy {
            margin: 0;
            color: rgba(255, 255, 255, 0.96);
            font: 500 clamp(.95rem, 1.9vw, 1.2rem)/1.2 'Poppins', sans-serif;
            max-width: 31ch;
        }

        .footer-transfer-banner__qr {
            width: clamp(122px, 11vw, 150px);
            border-radius: 1rem;
            padding: .45rem .45rem .35rem;
            background: linear-gradient(180deg, #ffffff 0%, #f8f8f8 100%);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.22);
            border: 2px solid #ef2528;
            text-align: center;
            flex-shrink: 0;
        }

        .footer-transfer-banner__qr img {
            display: block;
            width: 100%;
            height: auto;
            border-radius: .45rem;
            background: #fff;
        }

        .footer-transfer-banner__qr-label {
            margin-top: .4rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 32px;
            width: 100%;
            border-radius: .65rem;
            background: linear-gradient(180deg, #ff2c35, #e61d24);
            color: #fff;
            font: 800 clamp(.82rem, 1.3vw, 1.05rem)/1 'Poppins', sans-serif;
            letter-spacing: -0.01em;
        }

        @keyframes footer-car-run {
            from { transform: translate(-105%, -50%); }
            to { transform: translate(calc(100% + 42px), -50%); }
        }

        .footer-title {
            color: #fff;
            font-weight: 600;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.86);
            text-decoration: none;
        }

        .footer-link:hover,
        .footer-link:focus {
            color: var(--brand-orange);
        }

        .social-circle {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            border: 1px solid rgba(255, 149, 44, 0.55);
            color: var(--brand-green, var(--brand-orange));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all .2s ease;
        }

        .social-circle:hover,
        .social-circle:focus {
            background-color: var(--brand-green, var(--brand-orange));
            color: var(--brand-black);
        }

        .card,
        .event-card,
        .dish-tile,
        .menu-card,
        .map-shell {
            transition: transform .24s ease, box-shadow .24s ease;
        }

        .card:hover,
        .event-card:hover,
        .dish-tile:hover,
        .menu-card:hover,
        .map-shell:hover {
            transform: translateY(-4px);
        }

        .card img,
        .menu-card-image,
        .event-banner svg {
            transition: transform .35s ease;
        }

        .card:hover img,
        .menu-card:hover .menu-card-image,
        .event-card:hover .event-banner svg {
            transform: scale(1.04);
        }

        .dish-tile:hover .dish-visual {
            filter: saturate(1.05) contrast(1.03);
        }

        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity .55s ease, transform .55s ease;
            transition-delay: var(--reveal-delay, 0ms);
        }

        .reveal-on-scroll.is-revealed {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-ready .hero-ready .reveal-on-scroll {
            opacity: 0;
            transform: translateY(16px);
        }

        .reveal-ready .hero-ready .reveal-on-scroll.is-revealed {
            opacity: 1;
            transform: translateY(0);
        }

        @media (prefers-reduced-motion: reduce) {
            .site-preloader,
            .footer-transfer-banner__car-shell,
            .navbar-premium .nav-link::after,
            .btn-brand,
            .btn-brand-outline,
            .card,
            .event-card,
            .dish-tile,
            .menu-card,
            .map-shell,
            .card img,
            .menu-card-image,
            .event-banner svg,
            .dish-visual,
            .reveal-on-scroll {
                transition: none !important;
                animation: none !important;
            }

            .card:hover,
            .event-card:hover,
            .dish-tile:hover,
            .menu-card:hover,
            .map-shell:hover,
            .btn-brand:hover,
            .btn-brand-outline:hover {
                transform: none !important;
                box-shadow: none !important;
            }

            .reveal-on-scroll {
                opacity: 1 !important;
                transform: none !important;
            }

            .site-preloader {
                transition: none !important;
            }
        }

        @media (max-width: 1199.98px) {
            .footer-transfer-banner__inner {
                grid-template-columns: 1.15fr .95fr auto;
                gap: .9rem;
            }

            .footer-transfer-banner__car-shell {
                width: 132px;
                height: 40px;
            }
        }

        @media (max-width: 991.98px) {
            .footer-transfer-banner__inner {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding-top: 1rem;
                padding-bottom: 1rem;
            }

            .footer-transfer-banner__qr {
                width: min(150px, 46vw);
                justify-self: start;
            }

            .footer-transfer-banner__road-wrap {
                order: 3;
            }

            .footer-transfer-banner__copy {
                max-width: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="@yield('body_class')">
    <div id="sitePreloader" class="site-preloader" role="status" aria-live="polite" aria-label="Page loading">
        <div class="site-preloader__inner">
            <svg class="site-preloader__icon" viewBox="0 0 180 220" aria-hidden="true">
                <g class="preloader-flame-main">
                    <path d="M91 14c7 14 6 23-2 34-7 10-4 18 6 25 12 8 16 17 15 29-2 25-22 42-48 42s-45-19-45-43c0-17 10-27 23-37 9-7 16-15 18-29 2-10 0-17-4-25 14 6 22 16 26 29 13-14 18-31 11-54z" fill="#ff2a2f"/>
                </g>
                <g class="preloader-flame-accent">
                    <path d="M67 49c8 5 12 11 12 18 0 4-2 8-5 11 11-1 20 5 22 16 2 12-9 24-24 24-14 0-25-10-25-23 0-10 6-16 13-21 6-4 10-9 11-16 1-3 0-6-1-9 4 0 7 0 9 0-3 0-7 0-12 0z" fill="#ff7577" opacity=".9"/>
                </g>
                <g fill="#fff">
                    <path d="M34 107h112v18c0 20-12 37-30 45l17 36c2 4 0 8-4 9-4 2-8 0-10-4l-18-38c-4 1-8 2-11 2l2 32c0 5-3 8-8 8s-8-4-8-8l2-32c-4 0-8-1-11-2l-18 38c-2 4-6 6-10 4-4-1-6-5-4-9l17-36c-18-8-30-25-30-45v-18z"/>
                    <path d="M55 174l72-14c4-1 8 2 8 6 1 4-2 8-6 8l-72 14c-5 1-8-2-9-6 0-4 2-8 7-8z"/>
                </g>
            </svg>
            <div class="site-preloader__text">
                <strong>Kashmir Grill</strong> Loading
                <span class="site-preloader__dots" aria-hidden="true"><span></span><span></span><span></span></span>
            </div>
        </div>
    </div>
    <div id="app" class="d-flex flex-column min-vh-100">
        <nav class="navbar navbar-expand-lg navbar-dark navbar-premium">
            <div class="container">
                <a class="navbar-brand brand-logo fw-semibold" href="{{ url('/') }}">
                    <img src="{{ asset('assets/images/logo.png') }}" style="">

                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto ms-md-3 mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('events') ? 'active' : '' }}" href="{{ route('events') }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Events
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('events') }}">All Events</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('events') }}#event-ceremonies">Ceremonies</a></li>
                                <li><a class="dropdown-item" href="{{ route('events') }}#event-get-together">Get Together</a></li>
                                <li><a class="dropdown-item" href="{{ route('events') }}#event-meetings">Meetings</a></li>
                                <li><a class="dropdown-item" href="{{ route('events') }}#event-conferences">Conferences</a></li>
                                <li><a class="dropdown-item" href="{{ route('events') }}#event-valentines-day">Valentine's Day</a></li>
                                <li><a class="dropdown-item" href="{{ route('events') }}#event-festivals-eid-ramadan-easter-christmas">Festivals</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('menu') ? 'active' : '' }}" href="{{ route('menu') }}">Menu</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('book-now') ? 'active' : '' }}" href="{{ route('book-now') }}">Book Now</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                        @auth
                            <li class="nav-item">
                                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">Admin</a>
                            </li>
                        @endauth
                        <li class="nav-item d-none d-lg-block">
                            <span class="badge rounded-pill badge-brand">Premium Dining</span>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('book-now') }}" class="btn btn-brand btn-sm px-3">Book Now</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        @hasSection('hero')
            <header class="hero-ready">
                @yield('hero')
            </header>
        @endif

        <main class="flex-grow-1 py-4">
            @yield('content')
        </main>

        <footer id="contact" class="site-footer bg-brand-dark mt-5 pt-5 pb-4">
            <div class="container">
                <section class="footer-transfer-banner" aria-label="Free pick and drop offer">
                    <div class="footer-transfer-banner__inner">
                        <div>
                            <p class="footer-transfer-banner__title-top">Enjoy a seamless journey with</p>
                            <h2 class="footer-transfer-banner__title-main">Kashmir Grill House in Como</h2>
                            <div class="footer-transfer-banner__offer-row">
                                <p class="footer-transfer-banner__offer-label">We provide</p>
                                <span class="footer-transfer-banner__offer-pill">FREE PICK &amp; DROP</span>
                            </div>
                        </div>

                        <div class="footer-transfer-banner__road-wrap">
                            <div class="footer-transfer-banner__road" aria-hidden="true">
                                <div class="footer-transfer-banner__road-line"></div>
                                <div class="footer-transfer-banner__car-shell">
                                    <div class="footer-transfer-banner__car-body">
                                        <span class="footer-transfer-banner__car-window footer-transfer-banner__car-window--front"></span>
                                        <span class="footer-transfer-banner__car-window footer-transfer-banner__car-window--rear"></span>
                                        <span class="footer-transfer-banner__car-logo">kashmir</span>
                                    </div>
                                    <span class="footer-transfer-banner__car-wheel footer-transfer-banner__car-wheel--front"></span>
                                    <span class="footer-transfer-banner__car-wheel footer-transfer-banner__car-wheel--rear"></span>
                                </div>
                            </div>
                            <p class="footer-transfer-banner__copy">
                                Reserve your table now for free airport transfers to our restaurant!
                            </p>
                        </div>

                        <a class="footer-transfer-banner__qr" href="{{ $googleBusinessUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Scan QR to open Kashmir Grill House Google Business Profile">
                            <img
                                src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($googleBusinessUrl) }}"
                                alt="QR code for Kashmir Grill House Google Business Profile"
                                loading="lazy"
                                decoding="async"
                            >
                            <span class="footer-transfer-banner__qr-label">Scan Me!</span>
                        </a>
                    </div>
                </section>

                <div class="row g-4 align-items-start">
                    <div class="col-lg-4">
                        <h5 class="footer-title section-accent mb-3">Kashmir Grill House</h5>
                        <p class="mb-3">Halal Pakistani and Indian cuisine in Como with dine-in, takeaway, and delivery-friendly service.</p>
                        <span class="highlight-chip">Check Google for today&apos;s opening hours</span>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <h6 class="footer-title mb-3">Contact</h6>
                        <p class="mb-2">{{ $businessAddressLine }}</p>
                        <p class="mb-2"><a class="footer-link" href="tel:{{ $businessPhoneHref }}">{{ $businessPhone }}</a></p>
                        <p class="mb-0"><a class="footer-link" href="{{ $googleBusinessUrl }}" target="_blank" rel="noopener noreferrer">Google Business Profile &amp; Directions</a></p>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <h6 class="footer-title mb-3">Follow Us</h6>
                        <div class="d-flex gap-2 mb-3">
                            <a class="social-circle" href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram">IG</a>
                            <a class="social-circle" href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook">FB</a>
                            <a class="social-circle" href="{{ $tiktokUrl }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok">TT</a>
                            <a class="social-circle" href="{{ $googleBusinessUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Google Business Profile">GB</a>
                        </div>
                        <a href="{{ route('book-now') }}" class="btn btn-brand-outline btn-sm">Reserve Your Table</a>
                    </div>
                </div>
                <hr class="border-secondary border-opacity-25 my-4">
                <p class="mb-0 small text-white-50">&copy; {{ date('Y') }} Kashmir Grill House. All rights reserved.</p>
            </div>
        </footer>
    </div>
    @stack('scripts')

    <script>
        (() => {
            const preloader = document.getElementById('sitePreloader');
            if (!preloader) {
                return;
            }

            document.body.classList.add('preloader-active');
            let hidden = false;

            const hidePreloader = () => {
                if (hidden) {
                    return;
                }

                hidden = true;
                preloader.classList.add('is-hiding');
                document.body.classList.remove('preloader-active');

                window.setTimeout(() => {
                    preloader.remove();
                }, 420);
            };

            window.addEventListener('load', () => {
                window.setTimeout(hidePreloader, 120);
            }, { once: true });

            window.addEventListener('pageshow', (event) => {
                if (event.persisted) {
                    hidePreloader();
                }
            });

            window.setTimeout(hidePreloader, 4500);
        })();
    </script>

    <script>
        (function () {
            const frames = [
                @for($i=1;$i<=24;$i++)
                    "{{ asset('favicon/frames-32/frame-' . sprintf('%02d',$i) . '.png') }}",
                @endfor
            ];

            const faviconEl = document.getElementById('favicon');
            let i = 0;

            // lower = faster (ms)
            const speed = 60;

            setInterval(() => {
                faviconEl.href = frames[i] + "?f=" + i; // cache-bust so it updates in Chrome/Edge
                i = (i + 1) % frames.length;
            }, speed);
        })();
    </script>
</body>
</html>
