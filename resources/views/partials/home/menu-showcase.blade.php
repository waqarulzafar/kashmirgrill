<section id="menu" class="py-5" data-gsap-stagger>
    <div class="container">
        <x-section-header
            badge="Menu Showcase"
            title="Explore the Menu Categories Our Guests Order Most"
            subtitle="A well-rounded selection of halal grills, curries, rice dishes, breads, desserts, and refreshments."
        />
        <div class="row g-4">
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Starters" text="Well-balanced appetisers and shareable plates to begin your meal." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Tandoori Grills" text="Chargrilled meats and vegetarian platters prepared in the tandoor." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Signature Curries" text="Classic curries with layered spice, rich gravies, and fresh herbs." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Biryani" text="Fragrant rice dishes with aromatic spices and tender, slow-cooked proteins." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Fresh Breads" text="Naan and rotis baked to order throughout lunch and dinner service." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Desserts & Drinks" text="Traditional sweets, lassi, and refreshing drinks to complete the meal." />
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('menu') }}" class="btn btn-brand">View Full Menu</a>
        </div>
    </div>
</section>
