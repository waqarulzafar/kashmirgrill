<section id="events" class="py-5" data-gsap-stagger>
    <div class="container">
        <x-section-header
            badge="Events & Occasions"
            title="Host Every Celebration in Style"
            subtitle="Flexible spaces, curated menus, and service tailored for intimate gatherings and large occasions."
        />
        <div class="row g-4">
            <div class="col-12 col-md-6 col-xl-3" data-gsap-item>
                <x-highlight-card
                    title="Weddings"
                    text="Elegant dining experiences with custom menu planning and dedicated hosting support."
                    ctaLabel="Enquire"
                    :ctaHref="route('contact')"
                />
            </div>
            <div class="col-12 col-md-6 col-xl-3" data-gsap-item>
                <x-highlight-card
                    title="Birthdays"
                    text="From cozy family tables to vibrant group celebrations with signature dishes."
                    ctaLabel="Plan Event"
                    :ctaHref="route('events')"
                />
            </div>
            <div class="col-12 col-md-6 col-xl-3" data-gsap-item>
                <x-highlight-card
                    title="Corporate Dinners"
                    text="Professional yet warm setting for team meals, client hosting, and milestone dinners."
                    ctaLabel="View Options"
                    :ctaHref="route('events')"
                />
            </div>
            <div class="col-12 col-md-6 col-xl-3" data-gsap-item>
                <x-highlight-card
                    title="Private Parties"
                    text="Tailored service packages with flexible seating and dietary-friendly menu selections."
                    ctaLabel="Book Consultation"
                    :ctaHref="route('book-now')"
                />
            </div>
        </div>
    </div>
</section>
