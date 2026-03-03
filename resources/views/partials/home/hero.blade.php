<section class="hero-signature position-relative overflow-hidden" data-home-hero data-no-reveal>
    <div class="hero-signature__media" aria-hidden="true">
        <video
            class="hero-signature__video"
            data-home-hero-video
            data-home-hero-video-desktop="{{ asset('assets/videos/kashmir-hero-desktop.mp4') }}"
            data-home-hero-video-mobile="{{ asset('assets/videos/kashmir-hero-mobile.mp4') }}"
            autoplay
            muted
            loop
            playsinline
            preload="metadata"
            poster="{{ asset('assets/images/hero/kashmir-hero-poster.jpg') }}"
        ></video>
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
                        <div class="hero-signature__platter-shell" data-home-hero-platter-shell>
                            <span class="hero-signature__platter-shadow" aria-hidden="true" data-home-hero-platter-shadow></span>
                            <span class="hero-signature__platter-glow" aria-hidden="true" data-home-hero-platter-glow></span>
                            <div class="hero-signature__platter-stage" data-home-hero-platter-stage>
                                <canvas
                                    class="hero-signature__platter-frame"
                                    width="620"
                                    height="620"
                                    role="img"
                                    aria-label="Kashmir Grill House menu platter selection"
                                    data-home-hero-platter
                                    data-home-hero-frames-base="{{ asset('assets/images/hero/pranzo-scroll-frames') }}"
                                    data-home-hero-frames-count="60"
                                ></canvas>
                            </div>
                        </div>
                        <span class="hero-signature__float-pill hero-signature__float-pill--one" data-home-float-pill>Chef Special</span>
                        <span class="hero-signature__float-pill hero-signature__float-pill--two" data-home-float-pill>Fresh Tandoor</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
