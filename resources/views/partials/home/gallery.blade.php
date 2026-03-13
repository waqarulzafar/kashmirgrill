@php
    $featuredDishes = [
        [
            'name' => 'Charcoal Seekh Kebab Plate',
            'label' => 'Lead Favorite',
            'image' => 'assets/images/menu/griglia/beef-seekh-kebab.jpg',
            'alt' => 'Smoky seekh kebab plated with herbs',
            'tags' => ['Grill', 'Smoky', 'Sharing'],
            'description' => 'Flame-finished seekh kebabs with mint chutney and garnish, built for mixed-table ordering.',
            'price' => 'EUR 14',
            'prep' => '12-15 min',
            'orderHint' => 'Top grill pick',
            'parallax' => '0.08',
        ],
        [
            'name' => 'House Butter Chicken',
            'label' => 'Curry Classic',
            'image' => 'assets/images/menu/primi-piati/butter-chicken.jpg',
            'alt' => 'Butter chicken served in copper bowl',
            'tags' => ['Curry', 'Creamy'],
            'description' => 'Creamy tomato curry with balanced spice and tender chicken; a repeat-order staple.',
            'price' => 'EUR 13',
            'prep' => '10-12 min',
            'orderHint' => 'Family favorite',
            'parallax' => '0.11',
        ],
        [
            'name' => 'Lamb Biryani',
            'label' => 'Rice Special',
            'image' => 'assets/images/menu/lamb-biryani.jpg',
            'alt' => 'Lamb biryani served with aromatic rice',
            'tags' => ['Rice', 'Aromatic'],
            'description' => 'Fragrant basmati rice layered with spiced lamb, ideal for weekend lunch and takeaway.',
            'price' => 'EUR 14',
            'prep' => '14-16 min',
            'orderHint' => 'Best seller',
            'parallax' => '0.1',
        ],
        [
            'name' => 'Mix Grill Tandoori',
            'label' => 'Chef Mix',
            'image' => 'assets/images/menu/griglia/mix-grill-tandoori.jpg',
            'alt' => 'Mixed tandoori grill platter',
            'tags' => ['Grill', 'Platter'],
            'description' => 'A mixed grill board combining chicken cuts and kebabs for group tables and sharing.',
            'price' => 'EUR 18',
            'prep' => '16-20 min',
            'orderHint' => 'Group order',
            'parallax' => '0.09',
        ],
        [
            'name' => 'Chicken Tikka Masala',
            'label' => 'House Sauce',
            'image' => 'assets/images/menu/primi-piati/chicken-tikka-masala.jpg',
            'alt' => 'Chicken tikka masala in rich sauce',
            'tags' => ['Curry', 'Classic'],
            'description' => 'Chargrilled chicken tikka finished in a smooth masala sauce with warm spice depth.',
            'price' => 'EUR 13',
            'prep' => '10-12 min',
            'orderHint' => 'Repeat order',
            'parallax' => '0.1',
        ],
        [
            'name' => 'Shinwari Lamb Karahi',
            'label' => 'Karahi Style',
            'image' => 'assets/images/menu/primi-piati/shinwari-lamb-karahi.jpg',
            'alt' => 'Shinwari lamb karahi cooked with herbs',
            'tags' => ['Karahi', 'Lamb'],
            'description' => 'Traditional karahi profile with ginger, tomato, and green chili notes for bold flavor fans.',
            'price' => 'EUR 16',
            'prep' => '15-18 min',
            'orderHint' => 'Rich flavor',
            'parallax' => '0.12',
        ],
    ];

    $totalFeatured = count($featuredDishes);
    $priceValues = collect($featuredDishes)
        ->map(fn ($dish) => (int) filter_var($dish['price'], FILTER_SANITIZE_NUMBER_INT))
        ->filter();
    $lowestPrice = $priceValues->min() ?? 12;
    $highestPrice = $priceValues->max() ?? 18;
@endphp

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
                        <p class="mb-2 text-secondary">Explore dishes guests repeat-order for family dining, takeaway, and group tables.</p>
                        <p class="mb-3 text-secondary">Featured now: <strong>{{ $totalFeatured }} dishes</strong> | Typical range: <strong>EUR {{ $lowestPrice }}-{{ $highestPrice }}</strong></p>
                        <a href="{{ route('menu') }}" class="btn btn-brand btn-sm">View Full Menu</a>
                    </aside>
                </div>
            </div>
        </div>

        <div class="dishes-gallery__stats mb-4" data-gsap-item>
            <article class="dishes-gallery__stat-card">
                <p class="dishes-gallery__stat-label mb-1">Featured Picks</p>
                <p class="dishes-gallery__stat-value mb-0">{{ $totalFeatured }}</p>
            </article>
            <article class="dishes-gallery__stat-card">
                <p class="dishes-gallery__stat-label mb-1">Cuisine Streams</p>
                <p class="dishes-gallery__stat-value mb-0">3 Core</p>
            </article>
            <article class="dishes-gallery__stat-card">
                <p class="dishes-gallery__stat-label mb-1">Price Window</p>
                <p class="dishes-gallery__stat-value mb-0">EUR {{ $lowestPrice }}-{{ $highestPrice }}</p>
            </article>
            <article class="dishes-gallery__stat-card">
                <p class="dishes-gallery__stat-label mb-1">Halal Kitchen</p>
                <p class="dishes-gallery__stat-value mb-0">100%</p>
            </article>
        </div>

        <div class="dishes-gallery__layout">
            @foreach($featuredDishes as $dish)
                <article class="dish-tile {{ $loop->first ? 'dish-tile--lead' : 'dish-tile--spot' }} js-dish-card rounded-4 p-4 h-100 shadow-sm" data-gsap-item data-parallax-speed="{{ $dish['parallax'] }}">
                    <div class="dish-visual mb-3" data-gsap-parallax data-parallax-factor="{{ $dish['parallax'] }}">
                        <img src="{{ asset($dish['image']) }}" alt="{{ $dish['alt'] }}" loading="lazy" decoding="async" fetchpriority="low" sizes="(max-width: 991px) 100vw, 32vw">
                        <span class="dish-visual-label">{{ $dish['label'] }}</span>
                    </div>
                    <div class="dish-meta-row mb-2">
                        @foreach($dish['tags'] as $tag)
                            <span class="dish-meta-chip">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <h3 class="{{ $loop->first ? 'h4' : 'h5' }} mb-2">{{ $dish['name'] }}</h3>
                    <p class="mb-3">{{ $dish['description'] }}</p>
                    <div class="dish-detail-row">
                        <span class="dish-price">{{ $dish['price'] }}</span>
                        <span class="dish-detail-pill">{{ $dish['prep'] }}</span>
                        <span class="dish-detail-pill">{{ $dish['orderHint'] }}</span>
                    </div>
                </article>
            @endforeach

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
