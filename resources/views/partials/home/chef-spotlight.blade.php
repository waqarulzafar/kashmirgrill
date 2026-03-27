<section class="py-5">
    <div class="container">
        <div id="chefSpotlightCarousel" class="carousel slide chef-spotlight shadow-sm rounded-4 overflow-hidden" data-bs-ride="carousel" data-bs-interval="4500">
            <div class="carousel-indicators chef-spotlight__indicators mb-3">
                <button type="button" data-bs-target="#chefSpotlightCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="{{ __('Slide 1') }}"></button>
                <button type="button" data-bs-target="#chefSpotlightCarousel" data-bs-slide-to="1" aria-label="{{ __('Slide 2') }}"></button>
                <button type="button" data-bs-target="#chefSpotlightCarousel" data-bs-slide-to="2" aria-label="{{ __('Slide 3') }}"></button>
            </div>

            <div class="carousel-inner">
                <article class="carousel-item active">
                    <div class="row g-0 align-items-stretch">
                        <div class="col-12 col-lg-7">
                            <div class="chef-spotlight__content p-4 p-md-5 h-100">
                                <p class="text-uppercase small mb-2 text-brand">{{ __('Menu Picks') }}</p>
                                <h2 class="h3 mb-3">{{ __('Grill & Tandoori Favorites') }}</h2>
                                <ul class="list-unstyled mb-0">
                                    <li class="chef-spotlight__line mb-3">
                                        <div class="d-flex justify-content-between gap-3"><strong>{{ __('Chicken Tandoori (2 pcs)') }}</strong><span>€10.50</span></div>
                                        <p class="mb-0 text-secondary">{{ __('Tandoori chicken portion and one of the most requested grill-style items.') }}</p>
                                    </li>
                                    <li class="chef-spotlight__line mb-3">
                                        <div class="d-flex justify-content-between gap-3"><strong>{{ __('Chicken Tikka (5 pcs)') }}</strong><span>€10.00</span></div>
                                        <p class="mb-0 text-secondary">{{ __('Popular grilled tikka pieces served as a go-to dine-in or takeaway choice.') }}</p>
                                    </li>
                                    <li class="chef-spotlight__line">
                                        <div class="d-flex justify-content-between gap-3"><strong>{{ __('Seekh Kebab') }}</strong><span>€10.00</span></div>
                                        <p class="mb-0 text-secondary">{{ __('House-spiced kebab option with smoky notes and chutney pairing.') }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-lg-5">
                            <div class="chef-spotlight__image-wrap h-100">
                                <img src="{{ asset('assets/images/menu/griglia/grill-chicken.jpg') }}" alt="{{ __('Grill classics at Kashmir Grill House') }}" class="chef-spotlight__image" loading="lazy" decoding="async">
                            </div>
                        </div>
                    </div>
                </article>

                <article class="carousel-item">
                    <div class="row g-0 align-items-stretch">
                        <div class="col-12 col-lg-7">
                            <div class="chef-spotlight__content p-4 p-md-5 h-100">
                                <p class="text-uppercase small mb-2 text-brand">{{ __('Menu Picks') }}</p>
                                <h2 class="h3 mb-3">{{ __('Popular Curry Selection') }}</h2>
                                <ul class="list-unstyled mb-0">
                                    <li class="chef-spotlight__line mb-3">
                                        <div class="d-flex justify-content-between gap-3"><strong>{{ __('Butter Chicken') }}</strong><span>€10.00</span></div>
                                        <p class="mb-0 text-secondary">{{ __('Creamy tomato curry finished with warm aromatic spice notes.') }}</p>
                                    </li>
                                    <li class="chef-spotlight__line mb-3">
                                        <div class="d-flex justify-content-between gap-3"><strong>{{ __('Chicken Tikka Masala') }}</strong><span>€10.00</span></div>
                                        <p class="mb-0 text-secondary">{{ __('A classic curry favorite with a rich sauce and balanced spice.') }}</p>
                                    </li>
                                    <li class="chef-spotlight__line">
                                        <div class="d-flex justify-content-between gap-3"><strong>{{ __('Chicken Korma') }}</strong><span>€9.50</span></div>
                                        <p class="mb-0 text-secondary">{{ __('A milder curry option with creamy texture and fragrant spices.') }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-lg-5">
                            <div class="chef-spotlight__image-wrap h-100">
                                <img src="{{ asset('assets/images/menu/primi-piati/chicken-tikka-masala.jpg') }}" alt="{{ __('Signature curries at Kashmir Grill House') }}" class="chef-spotlight__image" loading="lazy" decoding="async">
                            </div>
                        </div>
                    </div>
                </article>

                <article class="carousel-item">
                    <div class="row g-0 align-items-stretch">
                        <div class="col-12 col-lg-7">
                            <div class="chef-spotlight__content p-4 p-md-5 h-100">
                                <p class="text-uppercase small mb-2 text-brand">{{ __('Menu Picks') }}</p>
                                <h2 class="h3 mb-3">{{ __('Starter Add-Ons') }}</h2>
                                <ul class="list-unstyled mb-0">
                                    <li class="chef-spotlight__line mb-3">
                                        <div class="d-flex justify-content-between gap-3"><strong>{{ __('Samosa Meat') }}</strong><span>€4.50</span></div>
                                        <p class="mb-0 text-secondary">{{ __('Crispy samosa starter that pairs well with curry and rice orders.') }}</p>
                                    </li>
                                    <li class="chef-spotlight__line mb-3">
                                        <div class="d-flex justify-content-between gap-3"><strong>{{ __('Samosa Vegetable') }}</strong><span>€3.50</span></div>
                                        <p class="mb-0 text-secondary">{{ __('A vegetarian starter option often added to mixed group meals.') }}</p>
                                    </li>
                                    <li class="chef-spotlight__line">
                                        <div class="d-flex justify-content-between gap-3"><strong>{{ __('Paneer Pakora') }}</strong><span>€4.50</span></div>
                                        <p class="mb-0 text-secondary">{{ __('Crisp paneer fritters with balanced spice and chutney.') }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-lg-5">
                            <div class="chef-spotlight__image-wrap h-100">
                                <img src="{{ asset('assets/images/menu/antipasti/samosa-chaat.jpg') }}" alt="{{ __('Starters and snacks at Kashmir Grill House') }}" class="chef-spotlight__image" loading="lazy" decoding="async">
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
