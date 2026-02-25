@php
    $featuredSlides = [
        [
            'kicker' => 'Featured Selection',
            'title' => 'Grill Signatures',
            'subtitle' => 'Charcoal-grilled favourites prepared in our 100% halal kitchen.',
            'image' => asset('assets/images/menu/griglia/beef-seekh-kebab.jpg'),
            'items' => [
                ['name' => 'Seekh Kebab Platter', 'price' => 'Â£12.90', 'desc' => 'House-spiced lamb seekh kebabs served with chutney and fresh salad.'],
                ['name' => 'Tandoori Chicken', 'price' => 'Â£13.50', 'desc' => 'Marinated chicken roasted in the tandoor and finished with citrus notes.'],
                ['name' => 'Mixed Grill Sizzler', 'price' => 'Â£18.90', 'desc' => 'A generous sharing platter with kebabs, wings, and grilled vegetables.'],
            ],
            'badge' => '100% Halal',
        ],
        [
            'kicker' => 'Featured Selection',
            'title' => 'Signature Curries',
            'subtitle' => 'Classic curry preparations with balanced spice, richness, and fresh herbs.',
            'image' => asset('assets/images/menu/primi-piati/butter-chicken.jpg'),
            'items' => [
                ['name' => 'Butter Chicken', 'price' => 'Â£13.80', 'desc' => 'Creamy tomato curry with tandoor-finished chicken and aromatic spices.'],
                ['name' => 'Lamb Rogan Josh', 'price' => 'Â£14.90', 'desc' => 'Slow-cooked lamb in a rich Kashmiri-inspired gravy.'],
                ['name' => 'Chicken Karahi', 'price' => 'Â£13.20', 'desc' => 'Traditional karahi-style chicken with tomato, ginger, and green chilli.'],
            ],
            'badge' => 'House Favorites',
        ],
        [
            'kicker' => 'Featured Selection',
            'title' => 'Starters & Soups',
            'subtitle' => 'Popular appetizer and soup selections prepared fresh for the table.',
            'image' => asset('assets/images/menu/antipasti/samosa-chaat.jpg'),
            'items' => [
                ['name' => 'Samosa Chaat', 'price' => 'Â£6.90', 'desc' => 'Crispy samosa topped with chutneys, herbs, and house seasoning.'],
                ['name' => 'Chicken Soup', 'price' => 'Â£5.90', 'desc' => 'Comforting chicken soup with aromatic spices and fresh garnish.'],
                ['name' => 'Paneer Pakora', 'price' => 'Â£6.40', 'desc' => 'Golden paneer pakora served with a bright, tangy chutney.'],
            ],
            'badge' => 'Starter Favorites',
        ],
    ];
@endphp

<section class="py-5 home-featured-slider-section" data-home-featured-slider-section data-no-reveal>
    <div class="container">
        <div id="homeFeaturedSelections" class="carousel slide home-featured-slider" data-bs-ride="carousel" data-bs-interval="5200" data-bs-pause="hover">
            <div class="home-featured-slider__top d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3 mb-4">
                <div>
                    <p class="home-featured-slider__eyebrow mb-2">Featured Menu</p>
                    <h2 class="home-featured-slider__title mb-0">Featured Selections from Our 100% Halal Grill Kitchen</h2>
                </div>
                <div class="carousel-indicators home-featured-slider__indicators position-static m-0">
                    @foreach($featuredSlides as $slide)
                        <button
                            type="button"
                            data-bs-target="#homeFeaturedSelections"
                            data-bs-slide-to="{{ $loop->index }}"
                            class="{{ $loop->first ? 'active' : '' }}"
                            @if($loop->first) aria-current="true" @endif
                            aria-label="Slide {{ $loop->iteration }}"
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
                                            <a href="{{ route('menu') }}" class="btn btn-brand">View Full Menu</a>
                                            <a href="{{ route('book-now') }}" class="btn btn-brand-outline">Book a Table</a>
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
                                        <span class="home-featured-slider__visual-chip home-featured-slider__visual-chip--a">Halal Kitchen</span>
                                        <span class="home-featured-slider__visual-chip home-featured-slider__visual-chip--b">Prepared Fresh</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev home-featured-slider__nav" type="button" data-bs-target="#homeFeaturedSelections" data-bs-slide="prev" aria-label="Previous slide">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next home-featured-slider__nav" type="button" data-bs-target="#homeFeaturedSelections" data-bs-slide="next" aria-label="Next slide">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</section>

