<section id="dishes" class="py-5 dishes-gallery" data-dish-parallax="true" data-gsap-stagger>
    <div class="container">
        <div class="row g-4 align-items-stretch dishes-gallery__head">
            <div class="col-12 col-lg-7" data-gsap-item>
                <x-section-header
                    badge="Food Gallery"
                    title="Signature Dishes Spotlight"
                    subtitle="A visual overview of key menu favourites, from grill selections and curry classics to rice-based signature dishes."
                />
            </div>
            <div class="col-12 col-lg-5" data-gsap-item>
                <aside class="dishes-gallery__info-card h-100">
                    <p class="dishes-gallery__info-kicker mb-2">Kitchen Highlights</p>
                    <ul class="list-unstyled mb-3">
                        <li>Charcoal-grilled meats and tandoor specialities</li>
                        <li>Signature curries with balanced spice profiles</li>
                        <li>Biryani, rice dishes, and platters for sharing</li>
                    </ul>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="dishes-gallery__chip">Chef Selection</span>
                        <span class="dishes-gallery__chip">House Favorite</span>
                        <span class="dishes-gallery__chip">Signature Rice</span>
                    </div>
                    <a href="{{ route('menu') }}" class="btn btn-brand btn-sm">View Full Menu</a>
                </aside>
            </div>
        </div>

        <div class="row g-4 dishes-gallery__grid">
            <div class="col-12 col-lg-5" data-gsap-item>
                <figure class="dish-tile dish-tile--featured dish-float js-dish-card rounded-4 p-4 h-100 shadow-sm" data-parallax-speed="0.08">
                    <div class="dish-visual mb-3" data-gsap-parallax data-parallax-factor="0.11">
                        <img src="{{ asset('assets/images/menu/griglia/beef-seekh-kebab.jpg') }}" alt="Smoky seekh kebab plated with herbs" loading="lazy" decoding="async" fetchpriority="low" sizes="(max-width: 991px) 100vw, 40vw">
                        <span class="dish-visual-label">Chef Selection</span>
                    </div>
                    <div class="dish-meta-row mb-2">
                        <span class="dish-meta-chip">Grill</span>
                        <span class="dish-meta-chip">Smoky</span>
                        <span class="dish-meta-chip">Popular</span>
                    </div>
                    <h3 class="h4 mb-2">Smoky Seekh Kebab</h3>
                    <p class="mb-3">Flame-finished seekh kebabs served with mint chutney and fresh garnish for a bold, smoky introduction to the grill menu.</p>
                    <div class="dish-feature-points">
                        <span>Charcoal-finished</span>
                        <span>Served with chutney</span>
                        <span>Great for sharing</span>
                    </div>
                </figure>
            </div>

            <div class="col-12 col-lg-7">
                <div class="row g-4">
                    <div class="col-12 col-md-6" data-gsap-item>
                        <figure class="dish-tile dish-tile--compact dish-float-delay js-dish-card rounded-4 p-4 h-100 shadow-sm" data-parallax-speed="0.12">
                            <div class="dish-visual mb-3" data-gsap-parallax data-parallax-factor="0.14">
                                <img src="{{ asset('assets/images/menu/primi-piati/butter-chicken.jpg') }}" alt="Butter chicken served in copper bowl" loading="lazy" decoding="async" fetchpriority="low" sizes="(max-width: 991px) 100vw, 33vw">
                                <span class="dish-visual-label">House Favorite</span>
                            </div>
                            <div class="dish-meta-row mb-2">
                                <span class="dish-meta-chip">Curry</span>
                                <span class="dish-meta-chip">Creamy</span>
                            </div>
                            <h3 class="h5 mb-2">House Butter Chicken</h3>
                            <p class="mb-0">A creamy tomato-based curry with warm spices and tandoor-finished chicken.</p>
                        </figure>
                    </div>

                    <div class="col-12 col-md-6" data-gsap-item>
                        <figure class="dish-tile dish-tile--compact dish-float js-dish-card rounded-4 p-4 h-100 shadow-sm" data-parallax-speed="0.06">
                            <div class="dish-visual mb-3" data-gsap-parallax data-parallax-factor="0.1">
                                <img src="{{ asset('assets/images/menu/primi-piati/mutton-korma.jpg') }}" alt="Rich mutton korma served hot" loading="lazy" decoding="async" fetchpriority="low" sizes="(max-width: 991px) 100vw, 33vw">
                                <span class="dish-visual-label">Signature</span>
                            </div>
                            <div class="dish-meta-row mb-2">
                                <span class="dish-meta-chip">Curry</span>
                                <span class="dish-meta-chip">Rich</span>
                            </div>
                            <h3 class="h5 mb-2">Royal Mutton Korma</h3>
                            <p class="mb-0">A rich, slow-cooked korma with tender mutton and deep house spice flavor.</p>
                        </figure>
                    </div>

                    <div class="col-12" data-gsap-item>
                        <article class="dishes-gallery__service-strip h-100">
                            <div>
                                <p class="dishes-gallery__service-kicker mb-1">Service Note</p>
                                <h3 class="h5 mb-2">Well Suited to Shared Tables and Event Dining</h3>
                                <p class="mb-0 text-secondary">Our kitchen and service flow support shared platters and mixed preferences, making service easier for families, groups, and private events.</p>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('events') }}" class="btn btn-brand-outline btn-sm">View Event Options</a>
                                <a href="{{ route('book-now') }}" class="btn btn-brand btn-sm">Book a Table</a>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
