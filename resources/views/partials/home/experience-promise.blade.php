<section class="py-5" data-no-reveal>
    <div class="container">
        <x-section-header
            :badge="__('Our Services')"
            :title="__('Halal Dine-In, Takeaway, and Delivery for Como Guests')"
            :subtitle="__('Kashmir Grill House combines halal Pakistani and Indian cooking with dine-in seating, takeaway service, and delivery-friendly ordering.')"
        />

        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="home-stat-card h-100">
                    <p class="home-stat-card__label mb-2">{{ __('Food Standard') }}</p>
                    <div class="home-stat-card__value">{{ __('100% Halal') }}</div>
                    <p class="mb-0 text-secondary">{{ __('Every menu category is prepared to a consistent 100% halal kitchen standard.') }}</p>
                </article>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="home-stat-card h-100">
                    <p class="home-stat-card__label mb-2">{{ __('Dining Options') }}</p>
                    <div class="home-stat-card__value">{{ __('Dine-In + Takeaway') }}</div>
                    <p class="mb-0 text-secondary">{{ __('Visit the restaurant on Via Milano or place takeaway orders for home and office meals.') }}</p>
                </article>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="home-stat-card h-100">
                    <p class="home-stat-card__label mb-2">{{ __('Location') }}</p>
                    <div class="home-stat-card__value">Como, Italy</div>
                    <p class="mb-0 text-secondary">{{ __('Easy to find at Via Milano, 253 with directions and reviews available on Google Business Profile.') }}</p>
                </article>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="home-stat-card h-100">
                    <p class="home-stat-card__label mb-2">{{ __('Ordering') }}</p>
                    <div class="home-stat-card__value">{{ __('Delivery Options') }}</div>
                    <p class="mb-0 text-secondary">{{ __('Order from home through supported delivery platforms or contact the restaurant directly by phone.') }}</p>
                </article>
            </div>
        </div>

        <div class="row g-4 align-items-stretch">
            <div class="col-12 col-lg-6">
                <article class="home-feature-panel h-100">
                    <div class="row g-3 align-items-stretch">
                        <div class="col-12 col-sm-6">
                            <div class="home-feature-panel__media h-100">
                                <img src="{{ asset('assets/images/menu/griglia/mix-grill-tandoori.jpg') }}" alt="{{ __('Mixed platter served at Kashmir Grill House') }}" loading="lazy" decoding="async">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="home-feature-panel__body h-100">
                                <p class="home-feature-panel__kicker mb-2">{{ __('Before You Arrive') }}</p>
                                <h3 class="h4 mb-3">{{ __('Reserve a Table and Check Directions') }}</h3>
                                <p class="text-secondary mb-3">{{ __('Book your visit online, call the team, and use the Google Business Profile to confirm today\'s hours before heading to Como.') }}</p>
                                <a href="https://share.google/grft1lwOxyW4px1OV" target="_blank" rel="noopener noreferrer" class="btn btn-brand-outline btn-sm">{{ __('Open Google Profile') }}</a>
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
                                <img src="{{ asset('assets/images/menu/primi-piati/butter-chicken.jpg') }}" alt="{{ __('Butter chicken served in a bowl') }}" loading="lazy" decoding="async">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="home-feature-panel__body h-100">
                                <p class="home-feature-panel__kicker mb-2">{{ __('Dine-In & Delivery') }}</p>
                                <h3 class="h4 mb-3">{{ __('Curries, Grills, Rice Dishes, and More') }}</h3>
                                <p class="text-secondary mb-3">{{ __('Explore a menu that includes popular curries, grilled items, biryani and rice dishes, starters, and vegetarian options.') }}</p>
                                <a href="{{ route('menu') }}" class="btn btn-brand btn-sm">{{ __('Browse Menu') }}</a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
