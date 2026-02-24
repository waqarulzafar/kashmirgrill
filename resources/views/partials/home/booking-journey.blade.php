<section class="py-5" data-no-reveal>
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-12 col-lg-5">
                <x-section-header
                    badge="Reservation Flow"
                    title="Reserve Your Table in Three Simple Steps"
                    subtitle="A clear booking process for table reservations, special requests, and pre-arrival menu planning."
                />
                <div class="home-journey-cta">
                    <p class="mb-3 text-secondary">Planning a family meal or private gathering? Share your guest count, timing, and menu notes so our team can prepare in advance.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('book-now') }}" class="btn btn-brand">Book Now</a>
                        <a href="{{ route('contact') }}" class="btn btn-brand-outline">Contact Team</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="row g-3">
                    <div class="col-12">
                        <article class="home-step-card">
                            <div class="home-step-card__index">01</div>
                            <div>
                                <h3 class="h5 mb-2">Choose Date, Time, and Party Size</h3>
                                <p class="mb-0 text-secondary">Submit your preferred date, time, and guest count for dine-in or occasion-based seating.</p>
                            </div>
                        </article>
                    </div>
                    <div class="col-12">
                        <article class="home-step-card">
                            <div class="home-step-card__index">02</div>
                            <div>
                                <h3 class="h5 mb-2">Add Table Preferences and Menu Notes</h3>
                                <p class="mb-0 text-secondary">Share seating preferences, dietary needs, and menu notes, including any dishes you would like prioritised before arrival.</p>
                            </div>
                        </article>
                    </div>
                    <div class="col-12">
                        <article class="home-step-card">
                            <div class="home-step-card__index">03</div>
                            <div>
                                <h3 class="h5 mb-2">Receive Confirmation from Our Team</h3>
                                <p class="mb-0 text-secondary">Our team confirms your reservation details and follows up on menu planning or event requirements where needed.</p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
