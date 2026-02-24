<section class="hero-signature position-relative overflow-hidden" data-home-hero data-no-reveal>
    <div class="hero-signature__media" aria-hidden="true">
        <video class="hero-signature__video d-none d-md-block" data-home-hero-video autoplay muted loop playsinline preload="metadata" poster="{{ asset('assets/images/menu/main-course.jpg') }}">
            <source src="{{ asset('assets/videos/pyramid-desktop.mp4') }}" type="video/mp4">
        </video>
        <video class="hero-signature__video d-md-none" data-home-hero-video autoplay muted loop playsinline preload="metadata" poster="{{ asset('assets/images/menu/main-course.jpg') }}">
            <source src="{{ asset('assets/videos/pyramid-mobile.mp4') }}" type="video/mp4">
        </video>
    </div>

    <div class="hero-signature__veil" data-home-hero-veil></div>
    <div class="hero-signature__embers" aria-hidden="true">
        @for($i = 0; $i < 10; $i++)
            <span></span>
        @endfor
    </div>

    <div class="container position-relative hero-signature__content">
        <div class="row g-4 align-items-end">
            <div class="col-12 col-xl-7">
                <span class="badge badge-brand rounded-pill mb-3" data-home-hero-kicker>Kashmir Grill House</span>
                <h1 class="display-4 fw-semibold mb-3 js-hero-headline" data-home-hero-title>
                    Premium Halal Grill Dining with <span class="text-brand-accent">Refined Hospitality</span>
                </h1>
                <p class="lead text-white-50 mb-4" data-home-hero-copy>
                    Kashmir Grill House offers 100% halal grill cuisine, comfortable dine-in service, advance table reservations with menu preferences, and reliable delivery for everyday dining and special occasions.
                </p>
                <div class="d-flex flex-wrap gap-2" data-home-hero-actions>
                    <a href="{{ route('book-now') }}" class="btn btn-brand px-4">Book Now</a>
                    <a href="{{ route('menu') }}" class="btn btn-brand-outline px-4">View Menu</a>
                </div>
            </div>
            <div class="col-12 col-xl-5">
                <div class="hero-signature__aside" data-home-hero-aside>
                    <div class="hero-signature__visual" data-home-hero-visual-stack>
                        <div class="hero-signature__disc hero-signature__disc--main" data-home-rotate-disc>
                            <img src="{{ asset('assets/images/menu/mix-platter.jpg') }}" alt="Signature mixed platter" loading="lazy" decoding="async">
                            <div class="hero-signature__disc-ring" aria-hidden="true"></div>
                        </div>
                        <div class="hero-signature__disc hero-signature__disc--small" data-home-rotate-disc-reverse>
                            <img src="{{ asset('assets/images/menu/butter-chicken.jpg') }}" alt="Butter chicken bowl" loading="lazy" decoding="async">
                        </div>
                        <span class="hero-signature__float-pill hero-signature__float-pill--one" data-home-float-pill>Chef Special</span>
                        <span class="hero-signature__float-pill hero-signature__float-pill--two" data-home-float-pill>Fresh Tandoor</span>
                    </div>

                    <div class="hero-signature__panel rounded-4 p-4 p-lg-5" data-home-hero-panel>
                        <h2 class="h5 mb-3">Service Highlights</h2>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2">100% halal charcoal grill specialties and signature curries</li>
                            <li class="mb-2">Dine-in, delivery, and family-friendly table service</li>
                            <li class="mb-0">Advance reservations with pre-arrival menu preferences</li>
                        </ul>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="highlight-chip">100% Halal</span>
                            <span class="highlight-chip">Open Daily</span>
                            <span class="highlight-chip">12:00 PM - 11:00 PM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
