@extends('layouts.master')

@section('title', 'Contact Kashmir Grill House | Via Milano, Como')
@section('meta_description', 'Contact Kashmir Grill House at Via Milano, 253, 22100 Como, Italy for reservations, takeaway orders, social links, and Google directions.')
@section('meta_keywords', 'contact Kashmir Grill House, Via Milano 253 Como, halal restaurant phone Como, Kashmir Grill House directions')
@section('body_class', 'home-menu-theme')

@section('content')
    <div class="contact-premium-page py-5">
        <section class="container pb-4 pb-lg-5">
            <div class="contact-hero">
                <div class="row g-4 align-items-center">
                    <div class="col-12 col-lg-7">
                        <span class="badge badge-brand rounded-pill mb-3">Contact Kashmir Grill House</span>
                        <h1 class="contact-hero__title mb-3">Reservations, Directions, and Restaurant Information in One Place</h1>
                        <p class="contact-hero__copy mb-4">
                            Contact our team for table reservations, takeaway enquiries, event coordination, and direct directions to Kashmir Grill House in Como.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('book-now') }}" class="btn btn-brand">Book Now</a>
                            <a href="tel:+393511203141" class="btn btn-brand-outline">Call Restaurant</a>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5">
                        <div class="contact-hero__panel">
                            <div class="contact-hero__panel-item">
                                <span>Address</span>
                                <strong>Via Milano, 253, 22100 Como, Italy</strong>
                            </div>
                            <div class="contact-hero__panel-item">
                                <span>Phone</span>
                                <strong>+39 351 1203141</strong>
                            </div>
                            <div class="contact-hero__panel-item">
                                <span>Support</span>
                                <strong>Bookings, takeaway, and event enquiries</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container pb-4 pb-lg-5">
            <div class="contact-service-strip">
                <span>Table Reservations</span>
                <span>Takeaway Orders</span>
                <span>Event Enquiries</span>
                <span>Google Directions</span>
                <span>Social Channels</span>
            </div>
        </section>

        <section class="container pb-4 pb-lg-5">
            <div class="row g-4">
                <div class="col-12 col-lg-5">
                    <article class="contact-card h-100">
                        <p class="contact-card__eyebrow mb-2">Restaurant Details</p>
                        <h2 class="contact-card__title mb-4">Everything You Need Before You Visit</h2>

                        <dl class="contact-info-list mb-4">
                            <dt>Address</dt>
                            <dd>Via Milano, 253, 22100 Como, Italy</dd>

                            <dt>Phone</dt>
                            <dd><a href="tel:+393511203141">+39 351 1203141</a></dd>

                            <dt>Google Profile</dt>
                            <dd>
                                <a href="https://share.google/grft1lwOxyW4px1OV" target="_blank" rel="noopener noreferrer">
                                    Open profile, reviews, and directions
                                </a>
                            </dd>

                            <dt>Opening Hours</dt>
                            <dd>
                                Please check the
                                <a href="https://share.google/grft1lwOxyW4px1OV" target="_blank" rel="noopener noreferrer">Google Business Profile</a>
                                for today&apos;s hours.
                            </dd>
                        </dl>

                        <div class="contact-social-block">
                            <p class="contact-card__eyebrow mb-3">Follow Us</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a class="contact-social-circle" href="https://www.instagram.com/kashmirgrillhouse_?utm_source=qr&igsh=ZjJmZDhtZHQzZ2l6" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                                <a class="contact-social-circle" href="https://www.facebook.com/share/1CVDdWNQJy/" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
                                <a class="contact-social-circle" href="https://www.tiktok.com/@kashmirgrillhouse" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><i class="fa-brands fa-tiktok" aria-hidden="true"></i></a>
                                <a class="contact-social-circle" href="https://share.google/grft1lwOxyW4px1OV" target="_blank" rel="noopener noreferrer" aria-label="Google Business Profile"><i class="fa-brands fa-google" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-12 col-lg-7">
                    <article class="contact-map-card h-100 overflow-hidden" id="map-section">
                        <div class="contact-map-card__intro">
                            <p class="contact-card__eyebrow mb-2">Location & Directions</p>
                            <h2 class="contact-card__title mb-2">Find Us on the Map</h2>
                            <p class="contact-map-card__copy mb-0">The map loads only when needed to keep the page lighter and faster.</p>
                        </div>

                        <div class="map-shell" id="lazy-map-shell"
                             data-map-src="https://www.google.com/maps?q=Via+Milano,+253,+22100+Como,+Italy&output=embed">
                            <div class="map-placeholder" id="map-placeholder">
                                <p class="mb-3">Directions are available on demand.</p>
                                <button type="button" class="btn btn-brand btn-sm" id="load-map-btn">Load Map</button>
                            </div>
                        </div>

                        <noscript>
                            <iframe
                                title="Kashmir Grill House location"
                                src="https://www.google.com/maps?q=Via+Milano,+253,+22100+Como,+Italy&output=embed"
                                width="100%"
                                height="420"
                                style="border:0;display:block;"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </noscript>
                    </article>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        body.home-menu-theme {
            background:
                radial-gradient(circle at 86% -12%, rgba(219, 29, 48, 0.22), transparent 42%),
                radial-gradient(circle at 12% 8%, rgba(255, 149, 44, 0.14), transparent 45%),
                linear-gradient(180deg, #050505 0%, #090909 34%, #0d0d0d 100%);
            color: #f2f2f2;
        }

        body.home-menu-theme main {
            background: transparent;
        }

        .contact-premium-page {
            background:
                radial-gradient(circle at 84% 4%, rgba(219, 29, 48, 0.16), transparent 34%),
                radial-gradient(circle at 12% 14%, rgba(255, 149, 44, 0.08), transparent 34%),
                linear-gradient(180deg, rgba(5, 5, 5, 0.98) 0%, rgba(11, 11, 11, 0.94) 100%);
            color: #f5f5f5;
            min-height: calc(100vh - var(--nav-height, 84px));
        }

        .contact-hero,
        .contact-service-strip,
        .contact-card,
        .contact-map-card {
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0)),
                #121212;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.2);
        }

        .contact-hero {
            padding: clamp(1.2rem, 3vw, 1.8rem);
            background:
                radial-gradient(circle at 88% 18%, rgba(255, 149, 44, 0.12), transparent 34%),
                radial-gradient(circle at 8% 88%, rgba(219, 29, 48, 0.14), transparent 38%),
                linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,0)),
                #111;
        }

        .contact-hero__title,
        .contact-card__title {
            color: #fff;
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: .02em;
            text-transform: uppercase;
            line-height: .96;
        }

        .contact-hero__title {
            font-size: clamp(2.3rem, 5vw, 4.4rem);
        }

        .contact-hero__copy,
        .contact-map-card__copy,
        .contact-info-list dd {
            color: rgba(255, 255, 255, 0.76);
            font: 500 1rem/1.65 'Rajdhani', sans-serif;
        }

        .contact-hero__panel {
            display: grid;
            gap: .9rem;
        }

        .contact-hero__panel-item {
            padding: 1rem;
            border-radius: 1rem;
            border: 1px solid rgba(255,255,255,.08);
            background: rgba(255,255,255,.03);
        }

        .contact-hero__panel-item span,
        .contact-card__eyebrow {
            display: block;
            color: rgba(255, 255, 255, 0.58);
            font: 700 .78rem/1 'Rajdhani', sans-serif;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .contact-hero__panel-item strong {
            display: block;
            margin-top: .35rem;
            color: #fff;
            font: 700 1rem/1.3 'Rajdhani', sans-serif;
            text-transform: uppercase;
        }

        .contact-service-strip {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: .65rem;
            padding: .95rem 1rem;
        }

        .contact-service-strip span {
            display: inline-flex;
            align-items: center;
            padding: .42rem .7rem;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,.08);
            background: rgba(255,255,255,.04);
            color: rgba(255, 255, 255, 0.86);
            font: 700 .72rem/1 'Rajdhani', sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .contact-card,
        .contact-map-card {
            padding: 1.1rem;
        }

        .contact-info-list {
            margin: 0;
        }

        .contact-info-list dt {
            color: #fff;
            font: 700 .94rem/1.2 'Rajdhani', sans-serif;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: .35rem;
        }

        .contact-info-list dd {
            margin-bottom: 1.15rem;
        }

        .contact-info-list a {
            color: #fff;
            text-decoration-color: rgba(255, 255, 255, 0.28);
        }

        .contact-social-block {
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .contact-social-circle {
            width: 2.85rem;
            height: 2.85rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.08);
            background: rgba(255,255,255,.04);
            color: #fff;
            text-decoration: none;
            transition: transform .2s ease, border-color .2s ease, background-color .2s ease;
        }

        .contact-social-circle:hover,
        .contact-social-circle:focus {
            transform: translateY(-2px);
            color: #fff;
            border-color: rgba(255,255,255,.16);
            background: rgba(255,255,255,.08);
        }

        .contact-map-card__intro {
            padding-bottom: 1rem;
        }

        .map-shell {
            min-height: 420px;
            position: relative;
            border-radius: 1rem;
            overflow: hidden;
            background: linear-gradient(135deg, #111, #2a2a2a);
            border: 1px solid rgba(255,255,255,.08);
        }

        .map-placeholder {
            min-height: 420px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
            padding: 1.5rem;
            background:
                radial-gradient(circle at 20% 20%, rgba(255, 149, 44, 0.28), transparent 45%),
                radial-gradient(circle at 80% 80%, rgba(219, 29, 48, 0.24), transparent 50%);
        }

        .map-frame {
            width: 100%;
            height: 420px;
            border: 0;
            display: block;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const boot = () => {
                const shell = document.getElementById('lazy-map-shell');
                const loadBtn = document.getElementById('load-map-btn');

                if (!shell) {
                    return;
                }

                let loaded = false;
                const mapSrc = shell.dataset.mapSrc;

                const loadMap = () => {
                    if (loaded || !mapSrc) {
                        return;
                    }

                    loaded = true;
                    const iframe = document.createElement('iframe');
                    iframe.className = 'map-frame';
                    iframe.title = 'Kashmir Grill House location';
                    iframe.loading = 'lazy';
                    iframe.referrerPolicy = 'no-referrer-when-downgrade';
                    iframe.src = mapSrc;
                    shell.innerHTML = '';
                    shell.appendChild(iframe);
                };

                if (loadBtn) {
                    loadBtn.addEventListener('click', loadMap, { once: true });
                }

                if ('IntersectionObserver' in window) {
                    const observer = new IntersectionObserver((entries, obs) => {
                        entries.forEach((entry) => {
                            if (entry.isIntersecting) {
                                loadMap();
                                obs.disconnect();
                            }
                        });
                    }, { rootMargin: '250px 0px' });

                    observer.observe(shell);
                }
            };

            if ('requestIdleCallback' in window) {
                window.requestIdleCallback(boot, { timeout: 500 });
            } else {
                window.setTimeout(boot, 0);
            }
        });
    </script>
@endpush
