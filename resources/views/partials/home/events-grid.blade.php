<section id="events" class="py-5" data-gsap-stagger>
    <div class="container">
        <x-section-header
            badge="Events & Occasions"
            title="Host Events with Professional Planning and Warm Hospitality"
            subtitle="From family celebrations to corporate gatherings, we provide flexible hosting, curated menus, and attentive service."
        />
        <div class="row g-4">
            <div class="col-12 col-md-6 col-xl-3" data-gsap-item>
                <x-highlight-card
                    title="Weddings"
                    text="Elegant dining service with tailored menu planning and coordinated support for your celebration."
                    ctaLabel="Enquire Now"
                    :ctaHref="route('contact')"
                />
            </div>
            <div class="col-12 col-md-6 col-xl-3" data-gsap-item>
                <x-highlight-card
                    title="Birthdays"
                    text="Celebrate with flexible table arrangements, signature dishes, and a guest-friendly dining experience."
                    ctaLabel="Plan Birthday"
                    :ctaHref="route('events')"
                />
            </div>
            <div class="col-12 col-md-6 col-xl-3" data-gsap-item>
                <x-highlight-card
                    title="Corporate Dinners"
                    text="A professional setting for team dinners, client hosting, and milestone business gatherings."
                    ctaLabel="View Packages"
                    :ctaHref="route('events')"
                />
            </div>
            <div class="col-12 col-md-6 col-xl-3" data-gsap-item>
                <x-highlight-card
                    title="Private Parties"
                    text="Private event hosting with flexible seating, halal menu options, and coordinated service support."
                    ctaLabel="Discuss Booking"
                    :ctaHref="route('book-now')"
                />
            </div>
        </div>
    </div>
</section>
