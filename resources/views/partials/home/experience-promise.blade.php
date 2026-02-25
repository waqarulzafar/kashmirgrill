<section class="py-5" data-no-reveal>
    <div class="container">
        <x-section-header
            badge="Our Services"
            title="Professional Halal Dining, Reservations, and Delivery in One Place"
            subtitle="We provide 100% halal grill dining, advance table reservations with menu preferences, and dependable delivery with the same kitchen standards."
        />

        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="home-stat-card h-100">
                    <p class="home-stat-card__label mb-2">Food Standard</p>
                    <div class="home-stat-card__value">100% Halal</div>
                    <p class="mb-0 text-secondary">Every menu category is prepared to a consistent 100% halal kitchen standard.</p>
                </article>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="home-stat-card h-100">
                    <p class="home-stat-card__label mb-2">Dining Options</p>
                    <div class="home-stat-card__value">Dine-In Ready</div>
                    <p class="mb-0 text-secondary">Comfortable dine-in seating for family meals, casual visits, and group dining.</p>
                </article>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="home-stat-card h-100">
                    <p class="home-stat-card__label mb-2">Reservations</p>
                    <div class="home-stat-card__value">Table + Menu</div>
                    <p class="mb-0 text-secondary">Reserve your table online and share menu preferences before you arrive.</p>
                </article>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="home-stat-card h-100">
                    <p class="home-stat-card__label mb-2">Service Mode</p>
                    <div class="home-stat-card__value">Delivery Available</div>
                    <p class="mb-0 text-secondary">Delivery service is available for customers who want Kashmir Grill House at home or work.</p>
                </article>
            </div>
        </div>

        <div class="row g-4 align-items-stretch">
            <div class="col-12 col-lg-6">
                <article class="home-feature-panel h-100">
                    <div class="row g-3 align-items-stretch">
                        <div class="col-12 col-sm-6">
                            <div class="home-feature-panel__media h-100">
                                <img src="{{ asset('assets/images/menu/griglia/mix-grill-tandoori.jpg') }}" alt="Mixed platter served at Kashmir Grill House" loading="lazy" decoding="async">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="home-feature-panel__body h-100">
                                <p class="home-feature-panel__kicker mb-2">Before You Arrive</p>
                                <h3 class="h4 mb-3">Reserve Your Table and Menu Preferences</h3>
                                <p class="text-secondary mb-3">Use the booking form to confirm your table, share occasion details, and request menu preparation in advance for a smoother arrival.</p>
                                <a href="{{ route('book-now') }}" class="btn btn-brand-outline btn-sm">Start Reservation</a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <div class="col-12 col-lg-6">
                <article class="home-feature-panel h-100">
                    <div class="row g-3 align-items-stretch">
                        <div class="col-12 col-sm-6">
                            <div class="home-feature-panel__media h-100">
                                <img src="{{ asset('assets/images/menu/primi-piati/butter-chicken.jpg') }}" alt="Butter chicken served in a bowl" loading="lazy" decoding="async">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="home-feature-panel__body h-100">
                                <p class="home-feature-panel__kicker mb-2">Dine-In & Delivery</p>
                                <h3 class="h4 mb-3">Halal Grill Favorites for Every Service Mode</h3>
                                <p class="text-secondary mb-3">Enjoy charcoal grills, curries, and platters in our dine-in setting or order delivery for family meals and gatherings.</p>
                                <a href="{{ route('menu') }}" class="btn btn-brand btn-sm">Browse Menu</a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
