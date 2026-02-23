<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Kashmir Grill House'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --brand-red: #e01d30;
            --brand-black: #000000;
            --brand-orange: #f89b20;
            --brand-ivory: #fff8f2;
            --nav-height: 84px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #fff, var(--brand-ivory));
            color: #202020;
        }

        .navbar-premium {
            position: sticky;
            top: 0;
            z-index: 1030;
            min-height: var(--nav-height);
            background-color: rgba(0, 0, 0, 0.95);
            border-bottom: 1px solid rgba(248, 155, 32, 0.25);
            backdrop-filter: blur(8px);
        }

        .navbar-premium .navbar-brand,
        .navbar-premium .nav-link {
            color: #fff;
            font-weight: 500;
        }

        .navbar-premium .nav-link:hover,
        .navbar-premium .nav-link:focus {
            color: var(--brand-orange);
        }

        .hero-ready {
            padding-top: clamp(4.5rem, 9vw, 7rem);
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

        .btn-brand {
            --bs-btn-bg: var(--brand-red);
            --bs-btn-border-color: var(--brand-red);
            --bs-btn-hover-bg: #bc1222;
            --bs-btn-hover-border-color: #bc1222;
            --bs-btn-active-bg: #a80f1d;
            --bs-btn-active-border-color: #a80f1d;
            --bs-btn-color: #fff;
        }

        .btn-brand-outline {
            --bs-btn-color: var(--brand-orange);
            --bs-btn-border-color: var(--brand-orange);
            --bs-btn-hover-bg: var(--brand-orange);
            --bs-btn-hover-border-color: var(--brand-orange);
            --bs-btn-hover-color: var(--brand-black);
            --bs-btn-active-bg: #dc8618;
            --bs-btn-active-border-color: #dc8618;
            --bs-btn-active-color: var(--brand-black);
        }

        .badge-brand {
            background-color: var(--brand-orange);
            color: var(--brand-black);
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .text-highlight {
            color: var(--brand-red);
        }

        .highlight-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background-color: rgba(224, 29, 48, 0.1);
            color: var(--brand-red);
            border: 1px solid rgba(224, 29, 48, 0.25);
            border-radius: 999px;
            padding: 0.35rem 0.9rem;
            font-weight: 600;
            font-size: 0.8rem;
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
            border: 1px solid rgba(248, 155, 32, 0.55);
            color: var(--brand-orange);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all .2s ease;
        }

        .social-circle:hover,
        .social-circle:focus {
            background-color: var(--brand-orange);
            color: var(--brand-black);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app" class="d-flex flex-column min-vh-100">
        <nav class="navbar navbar-expand-lg navbar-dark navbar-premium">
            <div class="container">
                <a class="navbar-brand fw-semibold" href="{{ url('/') }}">
                    <img src="{{ asset('assets/images/logo.png') }}" style="height: 70px">
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#menu">Menu</a></li>
                        <li class="nav-item"><a class="nav-link" href="#events">Events</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    </ul>
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                        <li class="nav-item d-none d-lg-block">
                            <span class="badge rounded-pill badge-brand">Premium Dining</span>
                        </li>
                        <li class="nav-item">
                            <a href="#book-now" class="btn btn-brand btn-sm px-3">Book Now</a>
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

        <footer id="contact" class="site-footer mt-5 pt-5 pb-4">
            <div class="container">
                <div class="row g-4 align-items-start">
                    <div class="col-lg-4">
                        <h5 class="footer-title section-accent mb-3">Kashmir Grill House</h5>
                        <p class="mb-3">A premium South Asian dining experience with bold flavors, warm hospitality, and curated event hosting.</p>
                        <span class="highlight-chip">Open Daily • 12:00 PM - 11:00 PM</span>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <h6 class="footer-title mb-3">Contact</h6>
                        <p class="mb-2">123 Flavor Street, London, UK</p>
                        <p class="mb-2"><a class="footer-link" href="tel:+440123456789">+44 0123 456 789</a></p>
                        <p class="mb-0"><a class="footer-link" href="mailto:hello@kashmirgrillhouse.com">hello@kashmirgrillhouse.com</a></p>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <h6 class="footer-title mb-3">Follow Us</h6>
                        <div class="d-flex gap-2 mb-3">
                            <a class="social-circle" href="#" aria-label="Instagram">IG</a>
                            <a class="social-circle" href="#" aria-label="Facebook">FB</a>
                            <a class="social-circle" href="#" aria-label="TikTok">TT</a>
                        </div>
                        <a href="#book-now" class="btn btn-brand-outline btn-sm">Reserve Your Table</a>
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
