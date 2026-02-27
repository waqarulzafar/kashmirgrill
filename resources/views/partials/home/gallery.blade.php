<section id="dishes" class="py-5 dishes-gallery" data-dish-parallax="true" data-gsap-stagger>
    <div class="container">
        <div class="dishes-gallery__intro-shell mb-4 mb-lg-5" data-gsap-item>
            <div class="row g-4 align-items-end">
                <div class="col-12 col-lg-8">
                    <x-section-header
                        badge="Food Gallery"
                        title="Popular Dishes from the Kashmir Grill Kitchen"
                        subtitle="A visual overview of guest favorites across grills, curries, and rice dishes served for dine-in and takeaway in Como."
                    />
                </div>
                <div class="col-12 col-lg-4">
                    <aside class="dishes-gallery__intro-cta h-100">
                        <p class="dishes-gallery__info-kicker mb-2">Most Ordered</p>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="dishes-gallery__chip">Grill Favorites</span>
                            <span class="dishes-gallery__chip">Curry Classics</span>
                            <span class="dishes-gallery__chip">Rice Specials</span>
                        </div>
                        <p class="mb-3 text-secondary">Explore dishes guests repeat-order for family dining, takeaway, and group tables.</p>
                        <a href="{{ route('menu') }}" class="btn btn-brand btn-sm">View Full Menu</a>
                    </aside>
                </div>
            </div>
        </div>

        <div class="dishes-gallery__layout">
            <article class="dish-tile dish-tile--lead js-dish-card rounded-4 p-4 h-100 shadow-sm" data-gsap-item data-parallax-speed="0.06">
                <div class="dish-visual mb-3" data-gsap-parallax data-parallax-factor="0.08">
                    <img src="{{ asset('assets/images/menu/griglia/beef-seekh-kebab.jpg') }}" alt="Smoky seekh kebab plated with herbs" loading="lazy" decoding="async" fetchpriority="low" sizes="(max-width: 991px) 100vw, 52vw">
                    <span class="dish-visual-label">Lead Favorite</span>
                </div>
                <div class="dish-meta-row mb-2">
                    <span class="dish-meta-chip">Grill</span>
                    <span class="dish-meta-chip">Smoky</span>
                    <span class="dish-meta-chip">Sharing Plate</span>
                </div>
                <h3 class="h4 mb-2">Charcoal Seekh Kebab Plate</h3>
                <p class="mb-3">Flame-finished seekh kebabs served with chutney and fresh garnish, ideal for shared tables and mixed group orders.</p>
                <div class="dish-feature-points">
                    <span>Charcoal finish</span>
                    <span>House chutney</span>
                    <span>Group-friendly</span>
                </div>
            </article>

            <article class="dish-tile dish-tile--spot js-dish-card rounded-4 p-4 h-100 shadow-sm" data-gsap-item data-parallax-speed="0.1">
                <div class="dish-visual mb-3" data-gsap-parallax data-parallax-factor="0.12">
                    <img src="{{ asset('assets/images/menu/primi-piati/butter-chicken.jpg') }}" alt="Butter chicken served in copper bowl" loading="lazy" decoding="async" fetchpriority="low" sizes="(max-width: 991px) 100vw, 25vw">
                    <span class="dish-visual-label">House Favorite</span>
                </div>
                <div class="dish-meta-row mb-2">
                    <span class="dish-meta-chip">Curry</span>
                    <span class="dish-meta-chip">Creamy</span>
                </div>
                <h3 class="h5 mb-2">House Butter Chicken</h3>
                <p class="mb-0">A creamy tomato-based curry and one of the most recognisable menu favorites for dine-in and delivery orders.</p>
            </article>

            <article class="dish-tile dish-tile--spot js-dish-card rounded-4 p-4 h-100 shadow-sm" data-gsap-item data-parallax-speed="0.08">
                <div class="dish-visual mb-3" data-gsap-parallax data-parallax-factor="0.1">
                    <img src="{{ asset('assets/images/menu/lamb-biryani.jpg') }}" alt="Lamb biryani served with aromatic rice" loading="lazy" decoding="async" fetchpriority="low" sizes="(max-width: 991px) 100vw, 25vw">
                    <span class="dish-visual-label">Rice Special</span>
                </div>
                <div class="dish-meta-row mb-2">
                    <span class="dish-meta-chip">Rice</span>
                    <span class="dish-meta-chip">Aromatic</span>
                </div>
                <h3 class="h5 mb-2">Lamb Biryani</h3>
                <p class="mb-0">A fragrant rice dish with warm spice notes and tender lamb, commonly chosen for family meals and takeaway.</p>
            </article>

            <article class="dishes-gallery__service-strip h-100" data-gsap-item>
                <div>
                    <p class="dishes-gallery__service-kicker mb-1">Service Note</p>
                    <h3 class="h5 mb-2">Designed for Mixed Preferences at Family and Group Tables</h3>
                    <p class="mb-0 text-secondary">The menu combines grill items, curries, and rice dishes so groups can order across styles without compromising on halal standards.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('menu') }}" class="btn btn-brand-outline btn-sm">Browse Menu</a>
                    <a href="{{ route('book-now') }}" class="btn btn-brand btn-sm">Book a Table</a>
                </div>
            </article>
        </div>
    </div>
</section>
