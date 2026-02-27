<section class="py-5 home-discovery-story" data-home-story-section data-no-reveal>
    <div class="container">
        <div class="home-discovery-story__showcase">
            <div class="row g-4 g-xl-5 align-items-stretch">
                <div class="col-12 col-lg-6">
                    <div class="home-discovery-story__intro text-start mb-0">
                        <p class="home-discovery-story__eyebrow mb-2">Discover</p>
                        <h2 class="home-discovery-story__headline mb-3">
                            A welcoming halal restaurant in Como serving Pakistani and Indian favorites for dine-in and takeaway.
                        </h2>
                        <p class="home-discovery-story__brand mb-3">Kashmir Grill House</p>
                        <p class="text-secondary mb-4">
                            Kashmir Grill House brings together grills, curries, biryani and rice dishes, starters, and vegetarian options in a casual setting on Via Milano, Como.
                        </p>

                        <div class="home-discovery-story__service-chips mb-4" aria-label="Service highlights">
                            <span>100% Halal Kitchen</span>
                            <span>Dine-In</span>
                            <span>Takeaway</span>
                            <span>Delivery Options</span>
                            <span>Como, Italy</span>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('menu') }}" class="btn btn-brand">View Menu</a>
                            <a href="{{ route('contact') }}" class="btn btn-brand-outline">Contact &amp; Directions</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="home-discovery-story__visual-shell" data-home-story-visual>
                        <div class="home-discovery-story__mosaic">
                            <figure class="home-discovery-story__media-card home-discovery-story__media-card--main">
                                <img src="{{ asset('assets/images/menu/primi-piati/shinwari-chicken-karahi.jpg') }}" alt="Signature curry bowl" loading="lazy" decoding="async">
                                <figcaption>Main Curry Pick</figcaption>
                            </figure>
                            <figure class="home-discovery-story__media-card home-discovery-story__media-card--top">
                                <img src="{{ asset('assets/images/menu/griglia/beef-seekh-kebab.jpg') }}" alt="Seekh kebab plate" loading="lazy" decoding="async">
                                <figcaption>Grill Favorite</figcaption>
                            </figure>
                            <figure class="home-discovery-story__media-card home-discovery-story__media-card--bottom">
                                <img src="{{ asset('assets/images/menu/antipasti/paneer-pakora-gpz.jpg') }}" alt="Vegetarian starter plate" loading="lazy" decoding="async">
                                <figcaption>Starter Option</figcaption>
                            </figure>
                        </div>

                        <div class="home-discovery-story__location-strip">
                            <span class="home-discovery-story__location-dot" aria-hidden="true"></span>
                            Via Milano, 253, 22100 Como, Italy
                        </div>
                    </div>
                </div>
            </div>

            <article class="home-discovery-story__panel mt-4" data-home-story-panel>
                <div class="row g-4 align-items-center">
                    <div class="col-12 col-lg-7">
                        <p class="home-discovery-story__eyebrow mb-2">About The Story</p>
                        <h3 class="h3 mb-3">Freshly prepared halal dishes with a menu built for everyday meals and group sharing</h3>
                        <p class="text-secondary mb-3">
                            Guests can dine in, place takeaway orders, or use delivery platforms, with phone and social channels available for quick updates and contact.
                        </p>
                        <div class="home-discovery-story__mini-grid">
                            <div>
                                <span>Phone</span>
                                <strong>+39 351 1203141</strong>
                            </div>
                            <div>
                                <span>Location</span>
                                <strong>Como, Italy</strong>
                            </div>
                            <div>
                                <span>Access</span>
                                <strong>Google Directions</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5">
                        <div class="home-discovery-story__cta-box">
                            <div class="home-discovery-story__icon" aria-hidden="true"><i class="fa-solid fa-utensils"></i></div>
                            <p class="mb-3 text-secondary">Explore the menu, reserve your table, or open the Google Business Profile for live directions, reviews, and current opening hours.</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('book-now') }}" class="btn btn-brand btn-sm">Reserve Table</a>
                                <a href="https://share.google/grft1lwOxyW4px1OV" target="_blank" rel="noopener noreferrer" class="btn btn-brand-outline btn-sm">Open Google</a>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>
