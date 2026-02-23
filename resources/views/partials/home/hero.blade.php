<section class="hero-signature position-relative overflow-hidden">
    <div class="hero-signature__media" aria-hidden="true">
        <video class="hero-signature__video d-none d-md-block" autoplay muted loop playsinline preload="metadata" poster="{{ asset('assets/images/menu/main-course.jpg') }}">
            <source src="{{ asset('assets/videos/pyramid-desktop.mp4') }}" type="video/mp4">
        </video>
        <video class="hero-signature__video d-md-none" autoplay muted loop playsinline preload="metadata" poster="{{ asset('assets/images/menu/main-course.jpg') }}">
            <source src="{{ asset('assets/videos/pyramid-mobile.mp4') }}" type="video/mp4">
        </video>
    </div>

    <div class="hero-signature__veil"></div>

    <div class="container position-relative hero-signature__content">
        <div class="row g-4 align-items-end">
            <div class="col-12 col-xl-7">
                <span class="badge badge-brand rounded-pill mb-3">Kashmir Grill House</span>
                <h1 class="display-4 fw-semibold mb-3 js-hero-headline">
                    Fire-Kissed Flavours, Refined Evenings, <span class="text-brand-accent">Unforgettable Hosting</span>
                </h1>
                <p class="lead text-white-50 mb-4">
                    Step into a cinematic dining experience where handcrafted grills, rich curries, and elevated hospitality come together.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('book-now') }}" class="btn btn-brand px-4">Book Now</a>
                    <a href="{{ route('menu') }}" class="btn btn-brand-outline px-4">View Menu</a>
                </div>
            </div>
            <div class="col-12 col-xl-5">
                <div class="hero-signature__panel rounded-4 p-4 p-lg-5">
                    <h2 class="h5 mb-3">Tonight at Kashmir Grill House</h2>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">Live charcoal grill window</li>
                        <li class="mb-2">Curated tasting platters for groups</li>
                        <li class="mb-0">Private occasion support on request</li>
                    </ul>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="highlight-chip">Open Daily</span>
                        <span class="highlight-chip">12:00 PM - 11:00 PM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
