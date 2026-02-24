<section class="py-5 home-discovery-story" data-home-story-section data-no-reveal>
    <div class="container">
        <div class="home-discovery-story__intro text-center mb-4 mb-lg-5">
            <p class="home-discovery-story__eyebrow mb-2">Discover</p>
            <h2 class="home-discovery-story__headline mb-2">
                A welcoming halal dining experience shaped by flavour, hospitality, and occasion-led service.
            </h2>
            <p class="home-discovery-story__brand mb-0">Kashmir Grill House</p>
        </div>

        <div class="home-discovery-story__visual-shell mb-4 mb-lg-5" data-home-story-visual>
            <div class="home-discovery-story__disc home-discovery-story__disc--main" data-home-story-disc>
                <img src="{{ asset('assets/images/menu/main-course.jpg') }}" alt="Signature curry bowl" loading="lazy" decoding="async">
            </div>
            <div class="home-discovery-story__disc home-discovery-story__disc--left" data-home-story-disc-rev>
                <img src="{{ asset('assets/images/menu/seekh-kebab.jpg') }}" alt="Seekh kebab plate" loading="lazy" decoding="async">
            </div>
            <div class="home-discovery-story__disc home-discovery-story__disc--right" data-home-story-disc>
                <img src="{{ asset('assets/images/menu/veg-dishes.jpg') }}" alt="Vegetarian curry plate" loading="lazy" decoding="async">
            </div>
            <div class="home-discovery-story__ring home-discovery-story__ring--one" aria-hidden="true"></div>
            <div class="home-discovery-story__ring home-discovery-story__ring--two" aria-hidden="true"></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <article class="home-discovery-story__panel" data-home-story-panel>
                    <div class="row g-4 align-items-center">
                        <div class="col-12 col-lg-7">
                            <p class="home-discovery-story__eyebrow mb-2">About The Story</p>
                            <h3 class="h3 mb-3">Thoughtfully prepared food and service for everyday dining and special gatherings</h3>
                            <p class="text-secondary mb-3">
                                Our kitchen brings together charcoal grills, signature curries, aromatic rice dishes, and sharing platters in a setting designed for families, friends, and hosted occasions.
                            </p>
                            <p class="text-secondary mb-0">
                                We focus on consistent flavour, warm hospitality, and a professional service flow that makes dine-in visits and event hosting feel seamless.
                            </p>
                        </div>
                        <div class="col-12 col-lg-5">
                            <div class="home-discovery-story__cta-box">
                                <div class="home-discovery-story__icon" aria-hidden="true">KG</div>
                                <p class="mb-3 text-secondary">Explore the menu, reserve your table, or discuss event arrangements with our team for a tailored dining experience.</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('menu') }}" class="btn btn-brand btn-sm">View Menu</a>
                                    <a href="{{ route('events') }}" class="btn btn-brand-outline btn-sm">View Events</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
