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
        }
    </style>
    @stack('styles')
</head>
<body class="@yield('body_class')">
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
</body>
</html>
