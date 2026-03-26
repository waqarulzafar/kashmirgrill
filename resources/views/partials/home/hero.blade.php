<section class="hero-signature position-relative overflow-hidden" data-home-hero data-no-reveal>
    <div class="hero-signature__media" aria-hidden="true" data-home-hero-backdrop></div>

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
                    Halal Pakistani &amp; Indian Dining in <span class="text-brand-accent">Como</span>
                </h1>
                <p class="lead text-white-50 mb-4" data-home-hero-copy>
                    Kashmir Grill House serves halal Pakistani and Indian favorites on Via Milano in Como, with dine-in, takeaway, and delivery-friendly options for everyday meals and family gatherings.
                </p>
                <div class="d-flex flex-wrap gap-2" data-home-hero-actions>
                    <a href="{{ route('book-now') }}" class="btn btn-brand px-4">Book Now</a>
                    <a href="{{ route('menu') }}" class="btn btn-brand-outline px-4">View Menu</a>
                </div>
            </div>
            <div class="col-12 col-xl-5">
                <div class="hero-signature__aside" data-home-hero-aside>
                    <div class="hero-signature__visual" data-home-hero-visual-stack>
                        <div class="hero-signature__orbital-shell" data-home-hero-orbital-shell>
                            <span class="hero-signature__orbital-glow" aria-hidden="true"></span>

                            <div class="hero-signature__orbit hero-signature__orbit--outer" data-home-hero-orbit data-orbit-direction="1">
                                <span class="hero-signature__planet" data-home-hero-planet>
                                    <span class="hero-signature__planet-core" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M6 12h12M12 6v12M8 8l8 8M16 8l-8 8" />
                                        </svg>
                                    </span>
                                    <span class="hero-signature__planet-label">Signature</span>
                                </span>
                            </div>

                            <div class="hero-signature__orbit hero-signature__orbit--middle" data-home-hero-orbit data-orbit-direction="-1">
                                <span class="hero-signature__planet" data-home-hero-planet>
                                    <span class="hero-signature__planet-core" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M12 3c2.4 2.2 3.6 4.2 3.6 6.2A3.6 3.6 0 1 1 8.4 9.2C8.4 7.2 9.6 5.2 12 3Z" />
                                            <path d="M12 13.2c-1.9 0-3.4 1.5-3.4 3.4S10.1 20 12 20s3.4-1.5 3.4-3.4" />
                                        </svg>
                                    </span>
                                    <span class="hero-signature__planet-label">Charcoal</span>
                                </span>
                            </div>

                            <div class="hero-signature__orbit hero-signature__orbit--inner" data-home-hero-orbit data-orbit-direction="1">
                                <span class="hero-signature__planet" data-home-hero-planet>
                                    <span class="hero-signature__planet-core" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M12 4.5a4 4 0 0 0-4 4V10a4 4 0 0 0 8 0V8.5a4 4 0 0 0-4-4Z" />
                                            <path d="M6.5 12.5c1.8 4.7 9.2 4.7 11 0" />
                                            <path d="M8.4 17.3 7 20m8.6-2.7L17 20" />
                                        </svg>
                                    </span>
                                    <span class="hero-signature__planet-label">Halal</span>
                                </span>
                            </div>

                            <div class="hero-signature__image-shell" data-home-hero-image-shell>
                                <img
                                    class="hero-signature__hero-image"
                                    src="{{ asset('images/hero-right-image.png') }}"
                                    alt="Kashmir Grill House mixed grill platter"
                                    width="686"
                                    height="686"
                                    loading="eager"
                                    decoding="async"
                                    data-home-hero-image
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
