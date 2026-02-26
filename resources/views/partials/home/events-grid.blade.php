<section id="events" class="py-5" data-gsap-stagger>
    <div class="container">
        <x-section-header
            badge="Group Dining"
            title="Plan Family Meals and Group Tables with Flexible Ordering"
            subtitle="Kashmir Grill House is well suited to shared meals, birthday dinners, and group-friendly ordering across dine-in and takeaway."
        />
        <div class="row g-4">
            <div class="col-12 col-md-6 col-xl-3" data-gsap-item>
                <x-highlight-card
                    title="Family Gatherings"
                    text="Comfortable halal dining for family lunches, dinners, and multi-generation tables."
                    ctaLabel="Contact Us"
                    :ctaHref="route('contact')"
                />
            </div>
            <div class="col-12 col-md-6 col-xl-3" data-gsap-item>
                <x-highlight-card
                    title="Birthday Dinners"
                    text="Book a table for birthdays and enjoy a menu that suits mixed preferences across grills, curries, and rice dishes."
                    ctaLabel="Plan Birthday"
                    :ctaHref="route('events')"
                />
            </div>
            <div class="col-12 col-md-6 col-xl-3" data-gsap-item>
                <x-highlight-card
                    title="Group Meals"
                    text="Order for friends, teams, or casual meetups with dine-in and takeaway options built for sharing."
                    ctaLabel="View Menu"
                    :ctaHref="route('menu')"
                />
            </div>
            <div class="col-12 col-md-6 col-xl-3" data-gsap-item>
                <x-highlight-card
                    title="Takeaway for Gatherings"
                    text="Arrange takeaway orders for home gatherings and small celebrations with halal menu choices."
                    ctaLabel="Book / Call"
                    :ctaHref="route('book-now')"
                />
            </div>
        </div>
    </div>
</section>
