<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $defaultSite = 'Kashmir Grill House';
        $businessPhone = '+39 351 1203141';
        $businessPhoneHref = '+393511203141';
        $businessAddressLine = 'Via Milano, 253, 22100 Como CO, Italy';
        $businessMapUrl = 'https://www.google.com/maps/search/?api=1&query=Via+Milano,+253,+22100+Como,+Italy';
        $instagramUrl = 'https://www.instagram.com/kashmirgrillhouse_?utm_source=qr&igsh=ZjJmZDhtZHQzZ2l6';
        $facebookUrl = 'https://www.facebook.com/share/1CVDdWNQJy/';
        $tiktokUrl = 'https://www.tiktok.com/@kashmirgrillhouse';
        $googleBusinessUrl = 'https://share.google/grft1lwOxyW4px1OV';
        $defaultTitle = 'Kashmir Grill House | Halal Pakistani & Indian Restaurant in Como, Italy';
        $pageTitle = trim($__env->yieldContent('meta_title', $__env->yieldContent('title', $defaultTitle)));
        $pageDescription = trim($__env->yieldContent('meta_description', 'Kashmir Grill House is a halal Pakistani and Indian restaurant in Como, Italy offering dine-in, takeaway, delivery-friendly service, and table reservations.'));
        $pageKeywords = trim($__env->yieldContent('meta_keywords', 'Kashmir Grill House, halal restaurant Como, Pakistani restaurant Como, Indian restaurant Como, Via Milano 253 Como, dine-in Como, takeaway Como, delivery Como, grilled food Como, curry Como'));
        $pageRobots = trim($__env->yieldContent('meta_robots', 'index,follow,max-image-preview:large'));
        $ogImage = trim($__env->yieldContent('og_image', asset('assets/images/logo.png')));
        $ogType = trim($__env->yieldContent('og_type', 'website'));
        $canonicalUrl = trim($__env->yieldContent('canonical_url', url()->current()));
        $restaurantSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => 'Kashmir Grill House',
            '@id' => url('/') . '#restaurant',
            'url' => url('/'),
            'image' => [$ogImage],
            'telephone' => $businessPhone,
            'servesCuisine' => ['Pakistani', 'Indian', 'South Asian', 'Halal', 'Grill'],
            'priceRange' => '€€',
            'currenciesAccepted' => 'EUR',
            'menu' => route('menu'),
            'acceptsReservations' => true,
            'hasMap' => $businessMapUrl,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Via Milano, 253',
                'postalCode' => '22100',
                'addressLocality' => 'Como',
                'addressRegion' => 'CO',
                'addressCountry' => 'IT',
            ],
            'contactPoint' => [[
                '@type' => 'ContactPoint',
                'telephone' => $businessPhone,
                'contactType' => 'reservations',
                'areaServed' => 'IT',
                'availableLanguage' => ['English', 'Italian'],
            ]],
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
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta name="robots" content="{{ $pageRobots }}">
    <meta name="author" content="{{ $defaultSite }}">
    <meta name="application-name" content="{{ $defaultSite }}">
    <meta name="apple-mobile-web-app-title" content="{{ $defaultSite }}">
    <meta name="geo.region" content="IT-CO">
    <meta name="geo.placename" content="Como">
    <meta name="format-detection" content="telephone=yes">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ $defaultSite }}">
    <meta property="og:locale" content="it_IT">
    <meta property="og:locale:alternate" content="en_US">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:alt" content="Kashmir Grill House in Como, Italy">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <link id="favicon" rel="icon" type="image/gif" href="{{ asset('assets/images/preloader/kashmir-loader.gif') }}">
    <link rel="shortcut icon" type="image/gif" href="{{ asset('assets/images/preloader/kashmir-loader.gif') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/preloader/kashmir-loader.gif') }}">
    <meta name="theme-color" content="#000000">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.2.0/css/all.min.css">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <script type="application/ld+json">{!! json_encode($restaurantSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <style>
        :root {
            --nav-height: 84px;
            --accent-primary: var(--brand-red, #db1d30);
            --accent-secondary: var(--brand-green, #ff952c);
        }

        #smooth-wrapper {
            min-height: 100%;
        }

        #smooth-content {
            min-height: 100vh;
            padding-top: var(--nav-height);
        }

        body {
            background: linear-gradient(180deg, #fff, var(--brand-ivory));
            color: #202020;
        }

        .navbar-premium {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            width: 100%;
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
            height: auto;
            display: block;
            filter: drop-shadow(0 12px 24px rgba(0, 0, 0, 0.35));
            animation: preloader-pop 1.5s ease-in-out infinite;
            image-rendering: auto;
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

        @keyframes preloader-pop {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }

        @keyframes preloader-dot {
            0%, 80%, 100% { opacity: .35; transform: translateY(0); }
            40% { opacity: 1; transform: translateY(-2px); }
        }

        .hero-transfer-banner-shell {
            position: relative;
            z-index: 5;
            margin-top: clamp(1.8rem, 3.2vw, 2.8rem);
            margin-bottom: clamp(1.6rem, 3vw, 2.6rem);
        }

        .footer-transfer-banner {
            position: relative;
            margin: 0;
            border-radius: 1.15rem;
            overflow: hidden;
            background:
                radial-gradient(circle at 12% 18%, rgba(255, 255, 255, 0.05), transparent 40%),
                radial-gradient(circle at 84% 18%, rgba(219, 29, 48, 0.2), transparent 46%),
                radial-gradient(circle at 62% 82%, rgba(255, 149, 44, 0.16), transparent 46%),
                linear-gradient(180deg, #0d0d0d 0%, #060606 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 24px 52px rgba(0, 0, 0, 0.3);
        }

        .footer-transfer-banner::before,
        .footer-transfer-banner::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: clamp(12px, 1.7vw, 20px);
            background: linear-gradient(180deg, #f02c31, #db1d30);
            z-index: 1;
        }

        .footer-transfer-banner::before { left: 0; }
        .footer-transfer-banner::after { right: 0; }

        .footer-transfer-banner__inner {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr) auto;
            gap: clamp(.9rem, 1.8vw, 1.4rem);
            align-items: center;
            padding: clamp(1.1rem, 2vw, 1.55rem) clamp(1.35rem, 3.2vw, 2.6rem);
            padding-left: clamp(2.05rem, 4.2vw, 3.4rem);
            padding-right: clamp(2.05rem, 4.2vw, 3.4rem);
        }

        .footer-transfer-banner__title-top {
            margin: 0 0 .32rem;
            color: rgba(255, 255, 255, 0.9);
            font: 600 clamp(.98rem, 1.8vw, 1.15rem)/1.2 'Poppins', sans-serif;
        }

        .footer-transfer-banner__title-main {
            margin: 0 0 .72rem;
            color: #fff;
            font: 800 clamp(1.65rem, 3.1vw, 2.3rem)/.95 'Poppins', sans-serif;
            letter-spacing: -0.03em;
            text-wrap: balance;
        }

        .footer-transfer-banner__offer-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .55rem .7rem;
            margin-bottom: .82rem;
        }

        .footer-transfer-banner__offer-label {
            margin: 0;
            color: rgba(255, 255, 255, 0.96);
            font: 700 clamp(1.22rem, 2.6vw, 1.65rem)/1 'Poppins', sans-serif;
            letter-spacing: -0.02em;
        }

        .footer-transfer-banner__offer-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: .6rem 1.18rem;
            border-radius: .9rem;
            background: linear-gradient(180deg, #ff343f, #df1d27);
            color: #fff;
            font: 800 clamp(.95rem, 1.65vw, 1.2rem)/1 'Poppins', sans-serif;
            letter-spacing: .01em;
            box-shadow: 0 10px 24px rgba(219, 29, 48, 0.3);
            white-space: nowrap;
        }

        .footer-transfer-banner__actions {
            display: flex;
            flex-wrap: wrap;
            gap: .55rem;
        }

        .footer-transfer-banner__cta {
            min-width: 136px;
            font-weight: 700;
            letter-spacing: .01em;
        }

        .footer-transfer-banner__road-wrap {
            display: grid;
            gap: .78rem;
        }

        .footer-transfer-banner__road {
            position: relative;
            height: 60px;
            border-radius: 999px;
            overflow: hidden;
            background: linear-gradient(180deg, rgba(255,255,255,.03), rgba(0,0,0,.28));
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .footer-transfer-banner__road-line {
            position: absolute;
            left: 14px;
            right: 14px;
            top: 50%;
            height: 5px;
            transform: translateY(-50%);
            border-radius: 999px;
            background:
                linear-gradient(90deg,
                rgba(255, 255, 255, 0.12) 0%,
                rgba(255, 255, 255, 0.85) 24%,
                rgba(255, 149, 44, 0.95) 65%,
                rgba(219, 29, 48, 0.95) 100%);
        }

        .footer-transfer-banner__car-shell {
            position: absolute;
            top: 50%;
            left: 8px;
            width: 148px;
            height: 42px;
            transform: translate(-112%, -50%);
            animation: footer-car-run 7.2s linear infinite;
            will-change: transform;
        }

        .footer-transfer-banner__car-body {
            position: absolute;
            inset: 7px 3px 5px;
            border-radius: 16px 18px 14px 14px;
            background: linear-gradient(180deg, #ff474f 0%, #d41420 100%);
            border: 1px solid rgba(255, 255, 255, 0.16);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .footer-transfer-banner__car-body::before {
            content: '';
            position: absolute;
            left: 20px;
            right: 21px;
            top: -10px;
            height: 18px;
            border-radius: 15px 22px 6px 6px;
            background: linear-gradient(180deg, #ff5f63 0%, #e3212c 100%);
            border: 1px solid rgba(255, 255, 255, 0.16);
        }

        .footer-transfer-banner__car-window {
            position: absolute;
            top: 5px;
            height: 9px;
            border-radius: 4px;
            background: linear-gradient(180deg, rgba(255,255,255,.92), rgba(210,230,255,.62));
            opacity: .9;
            z-index: 1;
        }

        .footer-transfer-banner__car-window--front { left: 48px; width: 26px; }
        .footer-transfer-banner__car-window--rear { left: 77px; width: 21px; }

        .footer-transfer-banner__car-wheel {
            position: absolute;
            bottom: 0;
            width: 19px;
            height: 19px;
            border-radius: 50%;
            background: #111;
            border: 2px solid #333;
            box-shadow: inset 0 0 0 3px #8d8d8d;
        }

        .footer-transfer-banner__car-wheel::before {
            content: '';
            position: absolute;
            inset: 4px;
            border-radius: 50%;
            background: #ddd;
            opacity: .95;
        }

        .footer-transfer-banner__car-wheel--front { left: 28px; }
        .footer-transfer-banner__car-wheel--rear { right: 22px; }

        .footer-transfer-banner__car-logo {
            position: absolute;
            left: 56px;
            top: 18px;
            color: rgba(255, 255, 255, 0.92);
            font: 700 .5rem/1 'Poppins', sans-serif;
            letter-spacing: .04em;
            text-transform: lowercase;
        }

        .footer-transfer-banner__copy {
            margin: 0;
            color: rgba(255, 255, 255, 0.94);
            font: 500 clamp(.92rem, 1.8vw, 1.12rem)/1.25 'Poppins', sans-serif;
            max-width: 34ch;
        }

        .footer-transfer-banner__copy strong {
            color: #fff;
            font-weight: 700;
        }

        .footer-transfer-banner__qr {
            width: clamp(114px, 10vw, 142px);
            border-radius: .9rem;
            padding: .4rem .4rem .32rem;
            background: linear-gradient(180deg, #ffffff 0%, #f7f7f7 100%);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.26);
            border: 2px solid #ef2528;
            text-align: center;
            flex-shrink: 0;
        }

        .footer-transfer-banner__qr img {
            display: block;
            width: 100%;
            height: auto;
            border-radius: .4rem;
            background: #fff;
        }

        .footer-transfer-banner__qr-label {
            margin-top: .35rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            width: 100%;
            border-radius: .5rem;
            background: linear-gradient(180deg, #ff2c35, #e61d24);
            color: #fff;
            font: 800 clamp(.78rem, 1.1vw, .95rem)/1 'Poppins', sans-serif;
            letter-spacing: 0;
        }

        @keyframes footer-car-run {
            from { transform: translate(-112%, -50%); }
            to { transform: translate(calc(100% + 64px), -50%); }
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

        .social-circle i {
            font-size: 0.95rem;
            line-height: 1;
        }

        .floating-cart {
            position: fixed;
            right: 1rem;
            bottom: 1rem;
            z-index: 1080;
        }

        .floating-cart.is-pending {
            opacity: .82;
        }

        body.cart-drawer-open {
            overflow: hidden;
        }

        .floating-cart__toggle {
            border: 1px solid rgba(255, 149, 44, 0.42);
            border-radius: 999px;
            min-height: 3.2rem;
            padding: .45rem .75rem;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background:
                linear-gradient(180deg, rgba(255,149,44,.2), rgba(219,29,48,.18)),
                #101010;
            color: #fff;
            font: 700 .86rem/1 'Rajdhani', sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.28);
        }

        .floating-cart__toggle-count {
            min-width: 1.55rem;
            height: 1.55rem;
            border-radius: 999px;
            padding: 0 .4rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #111;
            font: 800 .75rem/1 'Poppins', sans-serif;
        }

        .floating-cart__backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.42);
            backdrop-filter: blur(2px);
        }

        .floating-cart__drawer {
            position: fixed;
            right: .85rem;
            bottom: 4.85rem;
            width: min(420px, calc(100vw - 1.7rem));
            max-height: min(78vh, 640px);
            border-radius: 1rem;
            border: 1px solid rgba(255,255,255,.1);
            background:
                radial-gradient(circle at 82% 10%, rgba(255,149,44,.14), transparent 42%),
                linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,0)),
                #111;
            color: #fff;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .floating-cart__head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            padding: .9rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .floating-cart__kicker {
            color: rgba(255,255,255,.58);
            font: 700 .72rem/1 'Rajdhani', sans-serif;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .floating-cart__title {
            color: #fff;
            font: 700 1.1rem/1 'Rajdhani', sans-serif;
            letter-spacing: .03em;
            text-transform: uppercase;
        }

        .floating-cart__close {
            width: 2rem;
            height: 2rem;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,.16);
            background: rgba(255,255,255,.04);
            color: #fff;
        }

        .floating-cart__body {
            padding: .9rem 1rem 1rem;
            overflow-y: auto;
        }

        .floating-cart__empty {
            border-radius: .85rem;
            border: 1px dashed rgba(255,255,255,.14);
            padding: .95rem;
            color: rgba(255,255,255,.88);
            background: rgba(255,255,255,.02);
        }

        .floating-cart__items {
            display: grid;
            gap: .72rem;
        }

        .floating-cart__item {
            display: grid;
            grid-template-columns: 74px 1fr;
            gap: .72rem;
            border-radius: .85rem;
            border: 1px solid rgba(255,255,255,.08);
            background: rgba(255,255,255,.02);
            padding: .55rem;
        }

        .floating-cart__item-image {
            width: 74px;
            height: 74px;
            border-radius: .6rem;
            object-fit: cover;
            border: 1px solid rgba(255,255,255,.09);
        }

        .floating-cart__item-title {
            margin: 0 0 .15rem;
            color: #fff;
            font: 700 .95rem/1.2 'Rajdhani', sans-serif;
        }

        .floating-cart__item-category {
            margin: 0 0 .25rem;
            color: rgba(255,255,255,.62);
            font: 600 .72rem/1.2 'Rajdhani', sans-serif;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .floating-cart__item-meta {
            display: flex;
            gap: .45rem;
            flex-wrap: wrap;
            margin-bottom: .35rem;
        }

        .floating-cart__item-price,
        .floating-cart__item-line {
            color: rgba(255,255,255,.88);
            font: 700 .72rem/1 'Rajdhani', sans-serif;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .floating-cart__qty {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            flex-wrap: wrap;
        }

        .floating-cart__qty button {
            min-width: 1.6rem;
            height: 1.6rem;
            border-radius: .45rem;
            border: 1px solid rgba(255,255,255,.14);
            background: rgba(255,255,255,.04);
            color: #fff;
            font: 700 .82rem/1 'Rajdhani', sans-serif;
        }

        .floating-cart__qty span {
            min-width: 1.3rem;
            text-align: center;
            color: #fff;
            font: 700 .8rem/1 'Rajdhani', sans-serif;
        }

        .floating-cart__qty .floating-cart__remove {
            min-width: auto;
            padding: 0 .5rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            font-size: .66rem;
        }

        .floating-cart__footer {
            margin-top: .8rem;
            padding-top: .8rem;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .floating-cart__totals {
            color: rgba(255,255,255,.88);
            font: 600 .88rem/1.4 'Rajdhani', sans-serif;
            margin-bottom: .7rem;
        }

        .floating-cart__actions {
            display: flex;
            gap: .55rem;
        }

        .floating-cart__actions .btn {
            flex: 1;
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
            .hero-transfer-banner-shell {
                margin-top: clamp(1.5rem, 2.8vw, 2.2rem);
            }

            .footer-transfer-banner__inner {
                grid-template-columns: minmax(0, 1.2fr) minmax(0, .95fr) auto;
                gap: .82rem;
            }

            .footer-transfer-banner__car-shell {
                width: 128px;
                height: 40px;
            }

            .footer-transfer-banner__cta {
                min-width: 128px;
            }
        }

        @media (max-width: 991.98px) {
            .hero-transfer-banner-shell {
                margin-top: 1.2rem;
                margin-bottom: 1.6rem;
            }

            .footer-transfer-banner__inner {
                grid-template-columns: 1fr;
                gap: .95rem;
                padding-top: 1.05rem;
                padding-bottom: 1.05rem;
            }

            .footer-transfer-banner__qr {
                width: min(146px, 46vw);
                justify-self: start;
            }

            .footer-transfer-banner__road-wrap {
                order: 2;
            }

            .footer-transfer-banner__copy {
                max-width: none;
            }

            .footer-transfer-banner__actions {
                margin-top: .2rem;
            }
        }

        @media (max-width: 575.98px) {
            .hero-transfer-banner-shell {
                margin-top: 1rem;
            }

            .footer-transfer-banner__inner {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }

            .footer-transfer-banner__offer-pill {
                width: 100%;
                justify-content: center;
            }

            .footer-transfer-banner__cta {
                width: 100%;
                min-width: 0;
            }

            .floating-cart {
                right: .65rem;
                bottom: .65rem;
            }

            .floating-cart__toggle {
                min-height: 2.95rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="@yield('body_class')">
    <div id="sitePreloader" class="site-preloader" role="status" aria-live="polite" aria-label="Page loading">
        <div class="site-preloader__inner">
            <img
                class="site-preloader__icon"
                src="{{ asset('assets/images/preloader/kashmir-loader.gif') }}"
                alt=""
                aria-hidden="true"
                loading="eager"
                decoding="async"
            >
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
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('events*') ? 'active' : '' }}" href="{{ route('events') }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Events
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('events') }}">All Events</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('events.show', 'ceremonies') }}">Ceremonies</a></li>
                                <li><a class="dropdown-item" href="{{ route('events.show', 'get-together') }}">Get Together</a></li>
                                <li><a class="dropdown-item" href="{{ route('events.show', 'meetings') }}">Meetings</a></li>
                                <li><a class="dropdown-item" href="{{ route('events.show', 'conferences') }}">Conferences</a></li>
                                <li><a class="dropdown-item" href="{{ route('events.show', 'valentines-day') }}">Valentine's Day</a></li>
                                <li><a class="dropdown-item" href="{{ route('events.show', 'festivals') }}">Festivals</a></li>
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

        <div id="smooth-wrapper">
            <div id="smooth-content">
                @hasSection('hero')
                    <header class="hero-ready">
                        @yield('hero')
                    </header>
                @endif

                @php
                    $fullBleedMain = request()->routeIs('home', 'menu', 'menu.*', 'events', 'events.*', 'contact');
                @endphp

                <main class="flex-grow-1 {{ $fullBleedMain ? 'py-0' : 'py-4' }}">
                    @yield('content')
                </main>

                @if (request()->routeIs('home'))
                    <section class="hero-transfer-banner-shell" aria-label="Airport transfer offer">
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
                                        <div class="footer-transfer-banner__actions">
                                            <a href="{{ route('book-now') }}" class="btn btn-brand btn-sm footer-transfer-banner__cta">Reserve Table</a>
                                            <a href="tel:{{ $businessPhoneHref }}" class="btn btn-brand-outline btn-sm footer-transfer-banner__cta">Call {{ $businessPhone }}</a>
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
                                            Reserve your table now for <strong>free airport transfers</strong> to our restaurant.
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
                        </div>
                    </section>
                @endif

                <footer id="contact" class="site-footer bg-brand-dark mt-5 pt-5 pb-4">
                    <div class="container">
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
                            <a class="social-circle" href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                            <a class="social-circle" href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
                            <a class="social-circle" href="{{ $tiktokUrl }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><i class="fa-brands fa-tiktok" aria-hidden="true"></i></a>
                            <a class="social-circle" href="{{ $googleBusinessUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Google Business Profile"><i class="fa-brands fa-google" aria-hidden="true"></i></a>
                        </div>
                        <a href="{{ route('book-now') }}" class="btn btn-brand-outline btn-sm">Reserve Your Table</a>
                    </div>
                </div>
                <hr class="border-secondary border-opacity-25 my-4">
                <p class="mb-0 small text-white-50">&copy; {{ date('Y') }} Kashmir Grill House. All rights reserved.</p>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    @if(!request()->routeIs('admin.*'))
        @include('partials.cart.floating-cart', ['cart' => $globalCart ?? ['items' => [], 'count' => 0, 'subtotal' => '0.00']])
    @endif

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

</body>
</html>
