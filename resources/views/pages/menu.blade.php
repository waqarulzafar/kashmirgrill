@extends('layouts.master')

@section('title', 'Menu | Kashmir Grill House')
@section('meta_description', 'Explore appetizers, grills, mains, veg dishes, rice, seasoning, desserts, mix platters, and drinks at Kashmir Grill House.')

@php
    $categories = [
        ['key' => 'appetizers', 'label' => 'Appetizers'],
        ['key' => 'grill', 'label' => 'Grill'],
        ['key' => 'main-course', 'label' => 'Main Course'],
        ['key' => 'veg-dishes', 'label' => 'Veg Dishes'],
        ['key' => 'rice', 'label' => 'Rice'],
        ['key' => 'seasoning', 'label' => 'Seasoning'],
        ['key' => 'desserts', 'label' => 'Desserts'],
        ['key' => 'mix-platter', 'label' => 'Mix Platter'],
        ['key' => 'drinks', 'label' => 'Drinks'],
    ];

    $items = [
        ['category' => 'appetizers', 'name' => 'Samosa Trio', 'description' => 'Crisp pastry parcels with spiced potato filling and mint chutney.', 'price' => '£5.90', 'tags' => ['Popular', 'Crispy']],
        ['category' => 'appetizers', 'name' => 'Chicken Tikka Bites', 'description' => 'Tender marinated tikka bites finished in the tandoor.', 'price' => '£7.50', 'tags' => ['Protein', 'Tandoor']],

        ['category' => 'grill', 'name' => 'Lamb Seekh Grill', 'description' => 'Smoky minced lamb skewers with onion salad and lemon.', 'price' => '£12.90', 'tags' => ['Spicy', 'Chef Pick']],
        ['category' => 'grill', 'name' => 'Paneer Charcoal Grill', 'description' => 'Chargrilled paneer cubes with peppers and house marinade.', 'price' => '£11.20', 'tags' => ['Vegetarian', 'Smoky']],

        ['category' => 'main-course', 'name' => 'Butter Chicken', 'description' => 'Creamy tomato curry with balanced heat and aromatic spices.', 'price' => '£13.80', 'tags' => ['Creamy', 'Best Seller']],
        ['category' => 'main-course', 'name' => 'Lamb Rogan Josh', 'description' => 'Slow-cooked lamb in rich Kashmiri-style gravy.', 'price' => '£14.50', 'tags' => ['Classic', 'Signature']],

        ['category' => 'veg-dishes', 'name' => 'Dal Makhani', 'description' => 'Black lentils simmered overnight with butter and spices.', 'price' => '£10.40', 'tags' => ['Vegetarian', 'Comfort']],
        ['category' => 'veg-dishes', 'name' => 'Aloo Gobi Masala', 'description' => 'Potato and cauliflower tossed in dry masala seasoning.', 'price' => '£9.80', 'tags' => ['Vegan Friendly', 'Home Style']],

        ['category' => 'rice', 'name' => 'Chicken Biryani', 'description' => 'Fragrant basmati layered with saffron and spiced chicken.', 'price' => '£12.70', 'tags' => ['Aromatic', 'Popular']],
        ['category' => 'rice', 'name' => 'Jeera Rice', 'description' => 'Light cumin-scented steamed rice for curry pairing.', 'price' => '£4.90', 'tags' => ['Side', 'Light']],

        ['category' => 'seasoning', 'name' => 'Mint Yogurt Dip', 'description' => 'Cool mint and yogurt dip for grills and starters.', 'price' => '£2.20', 'tags' => ['Fresh', 'Add-on']],
        ['category' => 'seasoning', 'name' => 'Smoked Chili Oil', 'description' => 'House chili oil with smoky notes for flavor boosting.', 'price' => '£2.60', 'tags' => ['Hot', 'Add-on']],

        ['category' => 'desserts', 'name' => 'Gulab Jamun', 'description' => 'Warm milk dumplings soaked in fragrant syrup.', 'price' => '£5.40', 'tags' => ['Sweet', 'Traditional']],
        ['category' => 'desserts', 'name' => 'Kulfi Pistachio', 'description' => 'Dense Indian-style ice cream with pistachio crunch.', 'price' => '£5.90', 'tags' => ['Cold', 'Chef Pick']],

        ['category' => 'mix-platter', 'name' => 'Family Mix Platter', 'description' => 'A sharing platter of kebabs, wings, paneer, and naan.', 'price' => '£26.90', 'tags' => ['Sharing', 'Value']],
        ['category' => 'mix-platter', 'name' => 'Signature Tandoor Platter', 'description' => 'Premium mixed proteins with grilled vegetables.', 'price' => '£29.50', 'tags' => ['Signature', 'Grill']],

        ['category' => 'drinks', 'name' => 'Mango Lassi', 'description' => 'Refreshing yogurt drink blended with ripe mango.', 'price' => '£4.20', 'tags' => ['Cold', 'Popular']],
        ['category' => 'drinks', 'name' => 'Masala Chai', 'description' => 'Classic spiced tea brewed to order.', 'price' => '£3.20', 'tags' => ['Hot', 'Traditional']],
    ];

    $categoryImages = [
        'appetizers' => asset('assets/images/menu/appetizers.jpg'),
        'grill' => asset('assets/images/menu/grill.jpg'),
        'main-course' => asset('assets/images/menu/main-course.jpg'),
        'veg-dishes' => asset('assets/images/menu/veg-dishes.jpg'),
        'rice' => asset('assets/images/menu/rice.jpg'),
        'seasoning' => asset('assets/images/menu/seasoning.jpg'),
        'desserts' => asset('assets/images/menu/desserts.jpg'),
        'mix-platter' => asset('assets/images/menu/mix-platter.jpg'),
        'drinks' => asset('assets/images/menu/drinks.jpg'),
    ];

    $fallbackImage = asset('assets/images/logo.png');
    $items = array_map(function (array $item) use ($categoryImages, $fallbackImage): array {
        $item['image'] = $categoryImages[$item['category']] ?? $fallbackImage;
        return $item;
    }, $items);
@endphp

@section('hero')
    <div class="container">
        <div class="row align-items-center g-3">
            <div class="col-12 col-lg-8">
                <h1 class="display-6 fw-bold mb-3">Our Menu</h1>
                <p class="lead mb-0">Browse categories, search quickly, and discover dishes crafted for every taste.</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="container py-5">
        <header class="mb-4">
            <x-section-header
                badge="Menu Categories"
                title="Find Dishes Faster"
                subtitle="Use category pills and search to narrow results instantly."
            />
            <div class="row g-3 align-items-center">
                <div class="col-12 col-lg-7">
                    <label for="menu-search" class="form-label mb-1">Search Menu</label>
                    <input id="menu-search" type="search" class="form-control form-control-lg" placeholder="Search by name, description, or tag">
                </div>
                <div class="col-12 col-lg-5 text-lg-end">
                    <small class="text-secondary" id="menu-results-count">Showing {{ count($items) }} items</small>
                </div>
            </div>
        </header>

        <nav aria-label="Menu categories" class="mb-4">
            <ul class="nav nav-pills gap-2 flex-wrap" id="menu-category-pills">
                <li class="nav-item">
                    <button type="button" class="nav-link active" data-category="all">All</button>
                </li>
                @foreach($categories as $category)
                    <li class="nav-item">
                        <button type="button" class="nav-link" data-category="{{ $category['key'] }}">{{ $category['label'] }}</button>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="row g-4" id="menu-grid">
            @foreach($items as $item)
                @php
                    $searchText = strtolower($item['name'] . ' ' . $item['description'] . ' ' . implode(' ', $item['tags']));
                @endphp
                <div class="col-12 col-sm-6 col-xl-4 menu-item-col"
                     data-category="{{ $item['category'] }}"
                     data-search="{{ $searchText }}">
                    <article class="menu-card card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <img
                            src="{{ $item['image'] }}"
                            alt="{{ $item['name'] }}"
                            class="card-img-top menu-card-image"
                            width="640"
                            height="420"
                            loading="lazy"
                            decoding="async"
                            fetchpriority="low"
                            sizes="(max-width: 575px) 100vw, (max-width: 1199px) 50vw, 33vw"
                        >
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <h2 class="h5 mb-0">{{ $item['name'] }}</h2>
                                <span class="badge text-bg-dark">{{ $item['price'] }}</span>
                            </div>
                            <p class="text-secondary mb-3">{{ $item['description'] }}</p>
                            <div class="mt-auto d-flex flex-wrap gap-2">
                                @foreach($item['tags'] as $tag)
                                    <span class="badge rounded-pill menu-tag">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <div id="menu-empty-state" class="text-center py-5 d-none">
            <h2 class="h4 mb-2">No matching items</h2>
            <p class="text-secondary mb-0">Try another keyword or switch category.</p>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        #menu-category-pills .nav-link {
            border: 1px solid rgba(224, 29, 48, 0.2);
            color: var(--brand-black);
            font-weight: 500;
            background-color: #fff;
        }

        #menu-category-pills .nav-link.active {
            background-color: var(--brand-red);
            border-color: var(--brand-red);
            color: #fff;
        }

        .menu-card {
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 .9rem 1.8rem rgba(0, 0, 0, 0.12) !important;
        }

        .menu-card-image {
            aspect-ratio: 16 / 10;
            object-fit: cover;
        }

        .menu-tag {
            background-color: rgba(248, 155, 32, 0.14);
            color: #8a4f00;
            border: 1px solid rgba(248, 155, 32, 0.35);
            font-weight: 500;
        }

        @media (prefers-reduced-motion: reduce) {
            .menu-card {
                transition: none;
            }

            .menu-card:hover {
                transform: none;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const boot = () => {
            const pills = document.querySelectorAll('#menu-category-pills .nav-link');
            const searchInput = document.getElementById('menu-search');
            const cards = document.querySelectorAll('.menu-item-col');
            const emptyState = document.getElementById('menu-empty-state');
            const resultCount = document.getElementById('menu-results-count');
            let activeCategory = 'all';

            const applyFilters = () => {
                const term = (searchInput.value || '').trim().toLowerCase();
                let visibleCount = 0;

                cards.forEach((card) => {
                    const category = card.dataset.category;
                    const searchText = card.dataset.search || '';
                    const inCategory = activeCategory === 'all' || category === activeCategory;
                    const matchesTerm = term.length === 0 || searchText.includes(term);
                    const show = inCategory && matchesTerm;

                    card.classList.toggle('d-none', !show);
                    if (show) {
                        visibleCount += 1;
                    }
                });

                emptyState.classList.toggle('d-none', visibleCount > 0);
                resultCount.textContent = `Showing ${visibleCount} item${visibleCount === 1 ? '' : 's'}`;
            };

            pills.forEach((pill) => {
                pill.addEventListener('click', () => {
                    pills.forEach((btn) => btn.classList.remove('active'));
                    pill.classList.add('active');
                    activeCategory = pill.dataset.category;
                    applyFilters();
                });
            });

            searchInput.addEventListener('input', applyFilters);
            applyFilters();
            };

            if ('requestIdleCallback' in window) {
                window.requestIdleCallback(boot, { timeout: 500 });
            } else {
                window.setTimeout(boot, 0);
            }
        });
    </script>
@endpush

