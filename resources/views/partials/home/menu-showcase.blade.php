<section id="menu" class="py-5" data-gsap-stagger>
    <div class="container">
        <x-section-header
            badge="Menu Showcase"
            title="Explore the Menu Categories Guests Order Most in Como"
            subtitle="Halal Pakistani and Indian favorites across grills, curries, rice dishes, vegetarian options, pizza, and refreshments."
        />
        <div class="row g-4">
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Starters" text="Samosas, pakora, soups, and snack-style dishes to start the meal." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Grill &amp; Tandoori" text="Chargrilled halal meats and tandoori-style favorites prepared for dine-in and takeaway." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Signature Curries" text="Popular Pakistani and Indian curries including butter chicken, korma, and karahi dishes." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Biryani &amp; Rice" text="Fragrant rice dishes and biryani options suited for individual meals or sharing." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Vegetarian Dishes" text="Paneer and vegetable dishes prepared with the same halal kitchen standards and fresh ingredients." />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card title="Pizza, Drinks &amp; More" text="Additional crowd-pleasers and drinks for mixed groups and family orders." />
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('menu') }}" class="btn btn-brand">View Full Menu</a>
        </div>
    </div>
</section>
