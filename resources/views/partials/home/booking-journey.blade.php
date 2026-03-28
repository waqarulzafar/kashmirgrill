<section class="py-5" data-no-reveal>
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-12 col-lg-5">
                <x-section-header
                    :badge="__('Visit Kashmir Grill')"
                    :title="__('Plan Your Table Visit in Como in Three Simple Steps')"
                    :subtitle="__('Book a table, share your preferences, and confirm details with the team before you arrive.')"
                />
                <div class="home-journey-cta">
                    <p class="mb-3 text-secondary">{{ __('Planning a family meal or group dinner? Use the booking form or call the restaurant to share guest count, timing, and any special requests.') }}</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('book-now') }}" class="btn btn-brand">{{ __('Reserve a Table') }}</a>
                        <a href="{{ route('contact') }}" class="btn btn-brand-outline">{{ __('Contact Team') }}</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="row g-3">
                    <div class="col-12">
                        <article class="home-step-card">
                            <div class="home-step-card__index">01</div>
                            <div>
                                <h3 class="h5 mb-2">{{ __('Choose Your Visit Date, Time, and Party Size') }}</h3>
                                <p class="mb-0 text-secondary">{{ __('Send your preferred schedule and guest count for dine-in seating at Kashmir Grill House in Como.') }}</p>
                            </div>
                        </article>
                    </div>
                    <div class="col-12">
                        <article class="home-step-card">
                            <div class="home-step-card__index">02</div>
                            <div>
                                <h3 class="h5 mb-2">{{ __('Add Preferences or Order Notes') }}</h3>
                                <p class="mb-0 text-secondary">{{ __('Share seating preferences, halal-related dietary notes, and any dishes you want the team to prepare for your group.') }}</p>
                            </div>
                        </article>
                    </div>
                    <div class="col-12">
                        <article class="home-step-card">
                            <div class="home-step-card__index">03</div>
                            <div>
                                <h3 class="h5 mb-2">{{ __('Receive Confirmation and Directions') }}</h3>
                                <p class="mb-0 text-secondary">{{ __('The team confirms availability and you can use the Google Business Profile link for live directions and current opening hours.') }}</p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
