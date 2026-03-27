@php
    $featuredSlides = [
        [
            'kicker' => __('Featured Selection'),
            'title' => __('Tandoori & Grill Favorites'),
            'subtitle' => __('Popular halal grill and tandoori options for dine-in, takeaway, and delivery orders in Como.'),
            'image' => asset('assets/images/menu/griglia/beef-seekh-kebab.jpg'),
            'items' => [
                ['name' => __('Chicken Tandoori (2 pcs)'), 'price' => 'EUR 10.50', 'desc' => __('Classic tandoori chicken portion from the delivery menu lineup.') ],
                ['name' => __('Chicken Tikka (5 pcs)'), 'price' => 'EUR 10.00', 'desc' => __('A popular grilled tikka choice for individual meals or sharing.')],
                ['name' => __('Seekh Kebab Platter'), 'price' => 'EUR 10.00', 'desc' => __('House-spiced seekh kebabs served with chutney and fresh salad.')],
            ],
            'badge' => __('100% Halal'),
        ],
        [
            'kicker' => __('Featured Selection'),
            'title' => __('Curry & Karahi Picks'),
            'subtitle' => __('Comfort-food curries and house-style classics commonly ordered by local guests.'),
            'image' => asset('assets/images/menu/primi-piati/butter-chicken.jpg'),
            'items' => [
                ['name' => __('Butter Chicken'), 'price' => 'EUR 10.00', 'desc' => __('Creamy tomato curry and one of the best-known house favorites.')],
                ['name' => __('Chicken Tikka Masala'), 'price' => 'EUR 10.00', 'desc' => __('A delivery-favorite curry with balanced spice and rich sauce.')],
                ['name' => __('Chicken Korma'), 'price' => 'EUR 9.50', 'desc' => __('A milder curry option with creamy texture and aromatic spices.')],
            ],
            'badge' => __('Popular in Como'),
        ],
        [
            'kicker' => __('Featured Selection'),
            'title' => __('Starters & Snacks'),
            'subtitle' => __('Quick bites and starters often added to mixed orders and family meals.'),
            'image' => asset('assets/images/menu/antipasti/samosa-chaat.jpg'),
            'items' => [
                ['name' => __('Samosa Meat'), 'price' => 'EUR 4.50', 'desc' => __('Crispy meat samosa served as a savory starter.')],
                ['name' => __('Samosa Vegetable'), 'price' => 'EUR 3.50', 'desc' => __('Classic vegetarian samosa, ideal for mixed groups.')],
                ['name' => __('Paneer Pakora'), 'price' => 'EUR 4.50', 'desc' => __('Golden paneer pakora with chutney and balanced spice.')],
            ],
            'badge' => __('Add-On Favorites'),
        ],
    ];
@endphp

<section class="py-5 home-featured-slider-section" data-home-featured-slider-section data-no-reveal>
    <div class="container">
        <div id="homeFeaturedSelections" class="carousel slide home-featured-slider" data-bs-interval="false" data-bs-touch="true">
            <div class="home-featured-slider__top d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3 mb-4">
                <div>
                    <p class="home-featured-slider__eyebrow mb-2">{{ __('Featured Menu') }}</p>
                    <h2 class="home-featured-slider__title mb-0">{{ __('Popular Halal Picks from Kashmir Grill House in Como') }}</h2>
                </div>
                <div class="carousel-indicators home-featured-slider__indicators position-static m-0">
                    @foreach($featuredSlides as $slide)
                        <button
                            type="button"
                            data-bs-target="#homeFeaturedSelections"
                            data-bs-slide-to="{{ $loop->index }}"
                            class="{{ $loop->first ? 'active' : '' }}"
                            @if($loop->first) aria-current="true" @endif
                            aria-label="{{ __('Slide :number', ['number' => $loop->iteration]) }}"
                        ></button>
                    @endforeach
                </div>
            </div>

            <div class="carousel-inner">
                @foreach($featuredSlides as $slide)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}" data-home-featured-slide>
                        <div class="home-featured-slider__card">
                            <div class="row g-4 align-items-stretch">
                                <div class="col-12 col-lg-7">
                                    <div class="home-featured-slider__content h-100" data-home-featured-copy>
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                            <span class="home-featured-slider__kicker">{{ $slide['kicker'] }}</span>
                                            <span class="home-featured-slider__badge">{{ $slide['badge'] }}</span>
                                        </div>
                                        <h3 class="home-featured-slider__slide-title mb-2">{{ $slide['title'] }}</h3>
                                        <p class="home-featured-slider__slide-subtitle mb-4">{{ $slide['subtitle'] }}</p>

                                        <ul class="home-featured-slider__price-list list-unstyled mb-4">
                                            @foreach($slide['items'] as $item)
                                                <li class="home-featured-slider__price-item">
                                                    <div class="home-featured-slider__price-head">
                                                        <span class="home-featured-slider__dish-name">{{ $item['name'] }}</span>
                                                        <span class="home-featured-slider__dish-line"></span>
                                                        <span class="home-featured-slider__dish-price">{{ $item['price'] }}</span>
                                                    </div>
                                                    <p class="mb-0">{{ $item['desc'] }}</p>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="{{ route('menu') }}" class="btn btn-brand">{{ __('View Full Menu') }}</a>
                                            <a href="{{ route('book-now') }}" class="btn btn-brand-outline">{{ __('Book a Table') }}</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-5">
                                    <div class="home-featured-slider__visual h-100" data-home-featured-visual>
                                        <div class="home-featured-slider__visual-ring home-featured-slider__visual-ring--lg" aria-hidden="true"></div>
                                        <div class="home-featured-slider__visual-ring home-featured-slider__visual-ring--sm" aria-hidden="true"></div>
                                        <div class="home-featured-slider__visual-disc" data-home-featured-disc>
                                            <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}" loading="lazy" decoding="async">
                                        </div>
                                        <span class="home-featured-slider__visual-chip home-featured-slider__visual-chip--a">{{ __('Halal Kitchen') }}</span>
                                        <span class="home-featured-slider__visual-chip home-featured-slider__visual-chip--b">{{ __('Como Favorites') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev home-featured-slider__nav" type="button" data-bs-target="#homeFeaturedSelections" data-bs-slide="prev" aria-label="{{ __('Previous slide') }}">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next home-featured-slider__nav" type="button" data-bs-target="#homeFeaturedSelections" data-bs-slide="next" aria-label="{{ __('Next slide') }}">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</section>
