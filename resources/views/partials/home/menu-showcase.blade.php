<section id="menu" class="py-5" data-gsap-stagger>
    <div class="container">
        <x-section-header
            :badge="__('Menu Showcase')"
            :title="__('Explore the Menu Categories Guests Order Most in Como')"
            :subtitle="__('Halal Pakistani and Indian favorites across grills, curries, rice dishes, vegetarian options, pizza, and refreshments.')"
        />
        <div class="row g-4">
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card :title="__('Starters')" :text="__('Samosas, pakora, soups, and snack-style dishes to start the meal.')" />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card :title="__('Grill & Tandoori')" :text="__('Chargrilled halal meats and tandoori-style favorites prepared for dine-in and takeaway.')" />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card :title="__('Signature Curries')" :text="__('Popular Pakistani and Indian curries including butter chicken, korma, and karahi dishes.')" />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card :title="__('Biryani & Rice')" :text="__('Fragrant rice dishes and biryani options suited for individual meals or sharing.')" />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card :title="__('Vegetarian Dishes')" :text="__('Paneer and vegetable dishes prepared with the same halal kitchen standards and fresh ingredients.')" />
            </div>
            <div class="col-12 col-sm-6 col-lg-4" data-gsap-item>
                <x-highlight-card :title="__('Pizza, Drinks & More')" :text="__('Additional crowd-pleasers and drinks for mixed groups and family orders.')" />
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('menu') }}" class="btn btn-brand">{{ __('View Full Menu') }}</a>
        </div>
    </div>
</section>
