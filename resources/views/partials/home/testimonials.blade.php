@php
    $reviews = [
        [
            'quote' => 'Helpful staff, strong chicken tikka, fair pricing, and solid vegetarian options were highlighted in this visit.',
            'name' => 'Angela M.',
            'avatar' => '/images/reviews/angela-m.svg',
            'rating' => '9/10',
            'date' => 'October 6, 2025',
        ],
        [
            'quote' => 'Praised for authentic Pakistani and Indian food, generous portions, courteous staff, and very good value.',
            'name' => 'Ghulam D.',
            'avatar' => '/images/reviews/ghulam-d.svg',
            'rating' => '10/10',
            'date' => 'May 17, 2024',
        ],
        [
            'quote' => 'Guests specifically called out the chops, pakora, and notably friendly service.',
            'name' => 'bill m.',
            'avatar' => '/images/reviews/bill-m.svg',
            'rating' => '10/10',
            'date' => 'May 11, 2024',
        ],
        [
            'quote' => 'A standout review mentioned pakora chaat, lamb karahi, and garlic naan as memorable highlights.',
            'name' => 'Michael W.',
            'avatar' => '/images/reviews/michael-w.svg',
            'rating' => '10/10',
            'date' => 'March 13, 2024',
        ],
        [
            'quote' => 'Positive comments focused on good food and a welcoming team, with an overall enjoyable dining experience.',
            'name' => 'Antonio B.',
            'avatar' => '/images/reviews/antonio-b.svg',
            'rating' => '9/10',
            'date' => 'October 12, 2024',
        ],
    ];
@endphp

<section class="py-5" data-gsap-stagger>
    <div class="container">
        <x-section-header
            class="text-white"
            badge="Guest Reviews"
            title="Trusted Guest Reviews"
            subtitle="Recent diner feedback presented in a clean, hospitality-style carousel."
            subtitleClass="text-white-50"
        />

        <div id="homeReviewsCarousel" class="carousel slide home-reviews-carousel" data-bs-ride="carousel" data-bs-interval="4500" data-bs-pause="hover">
            <div class="carousel-indicators home-reviews-carousel__indicators position-static mb-4">
                @foreach($reviews as $review)
                    <button
                        type="button"
                        data-bs-target="#homeReviewsCarousel"
                        data-bs-slide-to="{{ $loop->index }}"
                        class="{{ $loop->first ? 'active' : '' }}"
                        @if($loop->first) aria-current="true" @endif
                        aria-label="Review {{ $loop->iteration }}"
                    ></button>
                @endforeach
            </div>

            <div class="carousel-inner">
                @foreach($reviews as $review)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="home-reviews-carousel__slide" data-gsap-item>
                            <blockquote class="home-review-card mb-0 mx-auto">
                                <div class="home-review-card__top">
                                    <img
                                        class="home-review-card__avatar"
                                        src="{{ $review['avatar'] }}"
                                        alt="{{ $review['name'] }} profile avatar"
                                        loading="lazy"
                                    >
                                    <div class="home-review-card__meta">
                                        <strong>{{ $review['name'] }}</strong>
                                        <span>{{ $review['date'] }} • Verified diner</span>
                                    </div>
                                    <div class="home-review-card__rating">{{ $review['rating'] }}</div>
                                </div>
                                <div class="home-review-card__stars" aria-label="Top guest review">
                                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                </div>
                                <p class="mb-3">{{ $review['quote'] }}</p>
                                <footer>TheFork diner feedback</footer>
                            </blockquote>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev home-reviews-carousel__nav" type="button" data-bs-target="#homeReviewsCarousel" data-bs-slide="prev" aria-label="Previous review">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next home-reviews-carousel__nav" type="button" data-bs-target="#homeReviewsCarousel" data-bs-slide="next" aria-label="Next review">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</section>
