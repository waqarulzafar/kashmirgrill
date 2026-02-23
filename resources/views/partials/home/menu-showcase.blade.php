<section id="menu" class="py-5" data-gsap-stagger>
    <div class="container">
        <x-section-header
            badge="Menu Showcase"
            title="Discover Our Most-Loved Categories"
            subtitle="A balanced mix of smoky grills, aromatic curries, and comfort classics."
        />
        <div class="row g-4">
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Starters" text="Crisp, spicy, and shareable plates to start your meal right." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Tandoori Grills" text="Chargrilled meats and vegetarian platters from our clay oven." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Signature Curries" text="Rich gravies layered with authentic spices and fresh herbs." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Biryani" text="Long-grain fragrant rice dishes with bold flavor and tender proteins." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Fresh Breads" text="Soft naan and artisan rotis, baked to order throughout service." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Desserts & Drinks" text="Traditional sweets, creamy lassi, and refreshing mocktails." />
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('menu') }}" class="btn btn-brand">See Full Menu</a>
        </div>
    </div>
</section>
