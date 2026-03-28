@php
    $featuredDishes = [
        [
            'name' => __('Charcoal Seekh Kebab Plate'),
            'label' => __('Lead Favorite'),
            'image' => 'assets/images/menu/griglia/beef-seekh-kebab.jpg',
            'alt' => __('Smoky seekh kebab plated with herbs'),
            'tags' => [__('Grill'), __('Smoky'), __('Sharing')],
            'description' => __('Flame-finished seekh kebabs with mint chutney and garnish, built for mixed-table ordering.'),
            'price' => 'EUR 14',
            'prep' => __('12-15 min'),
            'orderHint' => __('Top grill pick'),
            'parallax' => '0.08',
        ],
        [
            'name' => __('House Butter Chicken'),
            'label' => __('Curry Classic'),
            'image' => 'assets/images/menu/primi-piati/butter-chicken.jpg',
            'alt' => __('Butter chicken served in copper bowl'),
            'tags' => [__('Curry'), __('Creamy')],
            'description' => __('Creamy tomato curry with balanced spice and tender chicken; a repeat-order staple.'),
            'price' => 'EUR 13',
            'prep' => __('10-12 min'),
            'orderHint' => __('Family favorite'),
            'parallax' => '0.11',
        ],
        [
            'name' => __('Lamb Biryani'),
            'label' => __('Rice Special'),
            'image' => 'assets/images/menu/lamb-biryani.jpg',
            'alt' => __('Lamb biryani served with aromatic rice'),
            'tags' => [__('Rice'), __('Aromatic')],
            'description' => __('Fragrant basmati rice layered with spiced lamb, ideal for weekend lunch and takeaway.'),
            'price' => 'EUR 14',
            'prep' => __('14-16 min'),
            'orderHint' => __('Best seller'),
            'parallax' => '0.1',
        ],
        [
            'name' => __('Mix Grill Tandoori'),
            'label' => __('Chef Mix'),
            'image' => 'assets/images/menu/griglia/mix-grill-tandoori.jpg',
            'alt' => __('Mixed tandoori grill platter'),
            'tags' => [__('Grill'), __('Platter')],
            'description' => __('A mixed grill board combining chicken cuts and kebabs for group tables and sharing.'),
            'price' => 'EUR 18',
            'prep' => __('16-20 min'),
            'orderHint' => __('Group order'),
            'parallax' => '0.09',
        ],
        [
            'name' => __('Chicken Tikka Masala'),
            'label' => __('House Sauce'),
            'image' => 'assets/images/menu/primi-piati/chicken-tikka-masala.jpg',
            'alt' => __('Chicken tikka masala in rich sauce'),
            'tags' => [__('Curry'), __('Classic')],
            'description' => __('Chargrilled chicken tikka finished in a smooth masala sauce with warm spice depth.'),
            'price' => 'EUR 13',
            'prep' => __('10-12 min'),
            'orderHint' => __('Repeat order'),
            'parallax' => '0.1',
        ],
        [
            'name' => __('Shinwari Lamb Karahi'),
            'label' => __('Karahi Style'),
            'image' => 'assets/images/menu/primi-piati/shinwari-lamb-karahi.jpg',
            'alt' => __('Shinwari lamb karahi cooked with herbs'),
            'tags' => [__('Karahi'), __('Lamb')],
            'description' => __('Traditional karahi profile with ginger, tomato, and green chili notes for bold flavor fans.'),
            'price' => 'EUR 16',
            'prep' => __('15-18 min'),
            'orderHint' => __('Rich flavor'),
            'parallax' => '0.12',
        ],
    ];

    $totalFeatured = count($featuredDishes);
    $priceValues = collect($featuredDishes)
        ->map(fn ($dish) => (int) filter_var($dish['price'], FILTER_SANITIZE_NUMBER_INT))
        ->filter();
    $lowestPrice = $priceValues->min() ?? 12;
    $highestPrice = $priceValues->max() ?? 18;
    $featuredDishTicker = collect($featuredDishes)
        ->map(fn (array $dish): string => $dish['name'].' | '.$dish['orderHint'])
        ->all();
@endphp

<section id="dishes" class="py-4 py-lg-5 dishes-gallery" data-dish-parallax="true" data-gsap-stagger data-home-dishes-section>
    <div class="container">
        <div class="dishes-gallery__intro-shell mb-3 mb-lg-4" data-gsap-item>
            <div class="row g-4 align-items-end">
                <div class="col-12 col-lg-8">
                    <x-section-header
                        class="dishes-gallery__header"
                        :badge="__('Food Gallery')"
                        :title="__('Popular Dishes from the Kashmir Grill Kitchen')"
                        :subtitle="__('A quick look at guest favorites from the grill, curry, and rice menu in Como.')"
                    />
                </div>
                <div class="col-12 col-lg-4">
                    <aside class="dishes-gallery__intro-cta h-100">
                        <p class="dishes-gallery__info-kicker mb-2">{{ __('Most Ordered') }}</p>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="dishes-gallery__chip">{{ __('Grill Favorites') }}</span>
                            <span class="dishes-gallery__chip">{{ __('Curry Classics') }}</span>
                            <span class="dishes-gallery__chip">{{ __('Rice Specials') }}</span>
                        </div>
                        <p class="mb-3 text-secondary">{{ __('Guest repeat-orders for dine-in, takeaway, and group tables in Como.') }}</p>
                        <p class="mb-3 text-secondary">{!! __('Now showing <strong>:count dishes</strong> | <strong>EUR :low-:high</strong>', ['count' => $totalFeatured, 'low' => $lowestPrice, 'high' => $highestPrice]) !!}</p>
                        <a href="{{ route('menu') }}" class="btn btn-brand btn-sm">{{ __('View Full Menu') }}</a>
                    </aside>
                </div>
            </div>
        </div>

        <div class="dishes-gallery__stats mb-3 mb-lg-4" data-home-dishes-stats>
            <article class="dishes-gallery__stat-card" data-home-dishes-stat-card>
                <p class="dishes-gallery__stat-label mb-1">{{ __('Featured Picks') }}</p>
                <p class="dishes-gallery__stat-value mb-0">{{ $totalFeatured }}</p>
            </article>
            <article class="dishes-gallery__stat-card" data-home-dishes-stat-card>
                <p class="dishes-gallery__stat-label mb-1">{{ __('Cuisine Streams') }}</p>
                <p class="dishes-gallery__stat-value mb-0">{{ __('3 Core') }}</p>
            </article>
            <article class="dishes-gallery__stat-card" data-home-dishes-stat-card>
                <p class="dishes-gallery__stat-label mb-1">{{ __('Price Window') }}</p>
                <p class="dishes-gallery__stat-value mb-0">EUR {{ $lowestPrice }}-{{ $highestPrice }}</p>
            </article>
            <article class="dishes-gallery__stat-card" data-home-dishes-stat-card>
                <p class="dishes-gallery__stat-label mb-1">{{ __('Halal Kitchen') }}</p>
                <p class="dishes-gallery__stat-value mb-0">100%</p>
            </article>
        </div>

        <div class="dishes-gallery__ticker-shell mb-4 mb-lg-5" data-home-dishes-ticker>
            <div class="dishes-gallery__ticker-track" data-home-dishes-ticker-track>
                @for($loopIndex = 0; $loopIndex < 2; $loopIndex++)
                    @foreach($featuredDishTicker as $tickerItem)
                        <span class="dishes-gallery__ticker-item">{{ $tickerItem }}</span>
                    @endforeach
                @endfor
            </div>
        </div>

        <div class="dishes-gallery__layout">
            @foreach($featuredDishes as $dish)
                <article class="dish-tile {{ $loop->first ? 'dish-tile--lead' : 'dish-tile--spot' }} js-dish-card rounded-4 p-4 h-100 shadow-sm" data-parallax-speed="{{ $dish['parallax'] }}" data-home-dishes-card>
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

            <article class="dishes-gallery__service-strip h-100" data-home-dishes-card>
                <div>
                    <p class="dishes-gallery__service-kicker mb-1">{{ __('Service Note') }}</p>
                    <h3 class="h5 mb-2">{{ __('Designed for Mixed Preferences at Family and Group Tables') }}</h3>
                    <p class="mb-0 text-secondary">{{ __('The menu combines grill items, curries, and rice dishes so groups can order across styles without compromising on halal standards.') }}</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('menu') }}" class="btn btn-brand-outline btn-sm">{{ __('Browse Menu') }}</a>
                    <a href="{{ route('book-now') }}" class="btn btn-brand btn-sm">{{ __('Reserve a Table') }}</a>
                </div>
            </article>
        </div>
    </div>
</section>
