<section id="dishes" class="py-5 dishes-gallery" data-dish-parallax="true" data-gsap-stagger>
    <div class="container">
        <x-section-header
            badge="Food Gallery"
            title="Animated Dishes Spotlight"
            subtitle="A visual taste of what awaits at Kashmir Grill House."
        />
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-4" data-gsap-item>
                <figure class="dish-tile dish-float js-dish-card rounded-4 p-4 h-100 shadow-sm" data-parallax-speed="0.08">
                    <div class="dish-visual mb-3" data-gsap-parallax data-parallax-factor="0.11">
                        <img src="{{ asset('assets/images/menu/seekh-kebab.jpg') }}" alt="Smoky seekh kebab plated with herbs" loading="lazy" decoding="async" fetchpriority="low" sizes="(max-width: 991px) 100vw, 33vw">
                        <span class="dish-visual-label">Chef Selection</span>
                    </div>
                    <h3 class="h5 mb-2">Smoky Seekh Kebab</h3>
                    <p class="mb-0">Flame-finished skewers with zesty mint chutney.</p>
                </figure>
            </div>
            <div class="col-12 col-md-6 col-lg-4" data-gsap-item>
                <figure class="dish-tile dish-float-delay js-dish-card rounded-4 p-4 h-100 shadow-sm" data-parallax-speed="0.12">
                    <div class="dish-visual mb-3" data-gsap-parallax data-parallax-factor="0.14">
                        <img src="{{ asset('assets/images/menu/butter-chicken.jpg') }}" alt="Butter chicken served in copper bowl" loading="lazy" decoding="async" fetchpriority="low" sizes="(max-width: 991px) 100vw, 33vw">
                        <span class="dish-visual-label">House Favorite</span>
                    </div>
                    <h3 class="h5 mb-2">House Butter Chicken</h3>
                    <p class="mb-0">Creamy tomato gravy balanced with warm spices.</p>
                </figure>
            </div>
            <div class="col-12 col-md-6 col-lg-4" data-gsap-item>
                <figure class="dish-tile dish-float js-dish-card rounded-4 p-4 h-100 shadow-sm" data-parallax-speed="0.06">
                    <div class="dish-visual mb-3" data-gsap-parallax data-parallax-factor="0.1">
                        <img src="{{ asset('assets/images/menu/lamb-biryani.jpg') }}" alt="Aromatic lamb biryani with saffron rice" loading="lazy" decoding="async" fetchpriority="low" sizes="(max-width: 991px) 100vw, 33vw">
                        <span class="dish-visual-label">Signature</span>
                    </div>
                    <h3 class="h5 mb-2">Royal Lamb Biryani</h3>
                    <p class="mb-0">Aromatic basmati layered with saffron and herbs.</p>
                </figure>
            </div>
        </div>
    </div>
</section>
