@extends('layouts.master')

@section('title', 'Contact | Kashmir Grill House')
@section('meta_description', 'Contact Kashmir Grill House for reservations, event planning, opening hours, social channels, and directions.')

@section('hero')
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <h1 class="display-6 fw-bold mb-3">Contact Us</h1>
                <p class="lead mb-0">Get in touch for reservations, private events, and quick directions to Kashmir Grill House.</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="container py-5">
        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <article class="p-4 p-md-5 bg-white rounded-4 shadow-sm h-100">
                    <h2 class="h4 section-accent mb-4">Restaurant Details</h2>

                    <dl class="mb-4">
                        <dt class="fw-semibold">Address</dt>
                        <dd class="mb-3 text-secondary">123 Flavor Street, London, UK</dd>

                        <dt class="fw-semibold">Phone</dt>
                        <dd class="mb-3"><a href="tel:+440123456789">+44 0123 456 789</a></dd>

                        <dt class="fw-semibold">Email</dt>
                        <dd class="mb-3"><a href="mailto:hello@kashmirgrillhouse.com">hello@kashmirgrillhouse.com</a></dd>

                        <dt class="fw-semibold">Opening Hours</dt>
                        <dd class="mb-0 text-secondary">Daily: 12:00 PM - 11:00 PM</dd>
                    </dl>

                    <h3 class="h6 text-uppercase text-secondary mb-3">Follow Us</h3>
                    <div class="d-flex gap-2">
                        <a class="social-circle" href="#" aria-label="Instagram">IG</a>
                        <a class="social-circle" href="#" aria-label="Facebook">FB</a>
                        <a class="social-circle" href="#" aria-label="TikTok">TT</a>
                        <a class="social-circle" href="#" aria-label="X">X</a>
                    </div>
                </article>
            </div>

            <div class="col-12 col-lg-7">
                <article class="p-0 bg-white rounded-4 shadow-sm h-100 overflow-hidden" id="map-section">
                    <div class="p-4 p-md-5 pb-3">
                        <h2 class="h4 section-accent mb-2">Find Us on Map</h2>
                        <p class="text-secondary mb-0">Map loads only when needed for better page performance.</p>
                    </div>

                    <div class="map-shell" id="lazy-map-shell"
                         data-map-src="https://www.google.com/maps?q=123+Flavor+Street,+London,+UK&output=embed">
                        <div class="map-placeholder" id="map-placeholder">
                            <p class="mb-3">Directions are available on demand.</p>
                            <button type="button" class="btn btn-brand btn-sm" id="load-map-btn">Load Map</button>
                        </div>
                    </div>

                    <noscript>
                        <iframe
                            title="Kashmir Grill House location"
                            src="https://www.google.com/maps?q=123+Flavor+Street,+London,+UK&output=embed"
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
@endsection

@push('styles')
    <style>
        .map-shell {
            min-height: 420px;
            position: relative;
            background: linear-gradient(135deg, #111, #2a2a2a);
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
            background: radial-gradient(circle at 20% 20%, rgba(255, 149, 44, 0.28), transparent 45%),
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
