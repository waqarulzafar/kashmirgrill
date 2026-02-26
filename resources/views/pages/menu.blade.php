@extends('layouts.master')

@section('title', 'Food Menu | Kashmir Grill House')
@section('meta_description', 'Browse the Kashmir Grill House food menu by category, with signature grills, curries, rice, desserts, and drinks.')

@php
    $categoryNotes = [
        'appetizers' => 'Crispy starters and tandoor bites to begin the table.',
        'antipasti' => 'Classic starters and soups to open the table.',
        'grill' => 'Charcoal-fired meats and tandoor signatures with bold spice.',
        'griglia' => 'Smoky tandoor grills and signature kebab cuts.',
        'main-course' => 'Rich gravies, classics, and house specials.',
        'primi-piati' => 'Hearty curry classics and karahi-style specialties.',
        'veg-dishes' => 'Comforting vegetarian favorites with layered flavor.',
        'rice' => 'Fragrant basmati rice and biryani dishes.',
        'seasoning' => 'Chutneys, dips, and house add-ons for extra punch.',
        'desserts' => 'Traditional sweets and chilled finishes.',
        'mix-platter' => 'Sharing platters for couples, families, and groups.',
        'drinks' => 'Cooling lassis, chai, and refreshing house drinks.',
    ];

    $heroImage = $categories->flatMap->menuItems->first()?->imageUrl() ?? asset('assets/images/menu/mix-platter.jpg');
@endphp

@section('content')
    <div class="menu-page" data-menu-experience data-no-reveal>
        <div class="menu-scroll-progress" aria-hidden="true">
            <span data-menu-progress></span>
        </div>

        <section class="menu-hero position-relative overflow-hidden">
            <div class="menu-hero-bg" aria-hidden="true"></div>
            <div class="menu-flames menu-flames-left" data-menu-flame aria-hidden="true"></div>
            <div class="menu-flames menu-flames-right" data-menu-flame aria-hidden="true"></div>
            <div class="menu-embers" aria-hidden="true" data-menu-embers>
                @for($i = 0; $i < 12; $i++)
                    <span class="menu-ember"></span>
                @endfor
            </div>

            <div class="container py-5 py-lg-6 position-relative">
                <div class="row align-items-center g-4 g-lg-5">
                    <div class="col-12 col-lg-7">
                        <span class="menu-hero-badge" data-menu-hero-badge>kashmir grill house</span>

                        <h1 class="menu-hero-title mb-3" data-menu-hero-title>
                            <span class="line-wrap"><span class="line line-light">FOOD</span></span>
                            <span class="line-wrap"><span class="line line-red">MENU</span></span>
                        </h1>

                        <p class="menu-hero-subtitle mb-4" data-menu-hero-subtitle>
                            Explore our categories and signature dishes presented in a bold menu experience inspired by the original menu artwork.
                        </p>

                        <div class="d-flex flex-wrap gap-2" data-menu-hero-cta>
                            @if($categories->isNotEmpty())
                                <button type="button" class="menu-cta-btn menu-cta-primary" data-menu-chip="{{ $categories->first()->slug }}">
                                    Start With {{ $categories->first()->name }}
                                </button>
                            @endif
                            <a href="{{ route('book-now') }}" class="menu-cta-btn menu-cta-ghost">Reserve Table</a>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5">
                        <div class="menu-hero-visual" data-menu-hero-visual>
                            <div class="menu-hero-visual-glow" data-menu-hero-glow aria-hidden="true"></div>
                            <img
                                src="{{ $heroImage }}"
                                alt="Kashmir Grill House menu preview"
                                class="img-fluid"
                                width="700"
                                height="700"
                                loading="eager"
                                decoding="async"
                                fetchpriority="high"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if($categories->isEmpty())
            <section class="container py-5">
                <div class="alert alert-warning border-0 shadow-sm rounded-4">
                    <h2 class="h5 mb-2">No menu items found</h2>
                    <p class="mb-0">
                        Run <code>php artisan db:seed</code> to load the seeded menu categories and items.
                    </p>
                </div>
            </section>
        @else
            <section class="menu-chip-shell sticky-top" data-no-reveal>
                <div class="container py-2">
                    <div class="menu-chip-rail" data-menu-chip-rail data-lenis-prevent role="tablist" aria-label="Menu categories">
                        @foreach($categories as $category)
                            <button
                                type="button"
                                class="menu-chip {{ $loop->first ? 'is-active' : '' }}"
                                data-menu-chip="{{ $category->slug }}"
                                aria-current="{{ $loop->first ? 'true' : 'false' }}"
                            >
                                <span>{{ $category->name }}</span>
                                <small>{{ $category->menuItems->count() }}</small>
                            </button>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="menu-catalog py-4 py-lg-5">
                <div class="container">
                    @foreach($categories as $category)
                        @php
                            $sectionImage = $category->menuItems->first()?->imageUrl() ?? asset('assets/images/menu/main-course.jpg');
                        @endphp

                        <section
                            id="menu-{{ $category->slug }}"
                            class="menu-category-section mb-5 mb-lg-6"
                            data-menu-section="{{ $category->slug }}"
                            data-no-reveal
                        >
                            <div class="menu-category-header" data-menu-section-header>
                                <div class="row g-4 align-items-stretch">
                                    <div class="col-12 col-lg-7">
                                        <div class="menu-category-header-panel h-100">
                                            <div class="menu-kicker">{{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }}</div>
                                            <h2 class="menu-category-title mb-2">{{ $category->name }}</h2>
                                            <p class="menu-category-copy mb-0">
                                                {{ $categoryNotes[$category->slug] ?? 'Signature dishes made with bold spice and careful preparation.' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-5">
                                        <div class="menu-category-banner h-100">
                                            <img
                                                src="{{ $sectionImage }}"
                                                alt="{{ $category->name }}"
                                                class="img-fluid w-100 h-100"
                                                width="720"
                                                height="420"
                                                loading="lazy"
                                                decoding="async"
                                                data-menu-parallax
                                            >
                                            <div class="menu-category-banner-overlay" aria-hidden="true"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4 mt-1">
                                @foreach($category->menuItems as $item)
                                    @php
                                        $image = $item->imageUrl() ?? $sectionImage;
                                        $tags = $item->tagList();
                                    @endphp
                                    <div class="col-12 col-md-6 col-xl-4">
                                        <article class="menu-item-card h-100" data-menu-card>
                                            <div class="menu-item-glare" aria-hidden="true"></div>
                                            <div class="menu-item-media">
                                                <img
                                                    src="{{ $image }}"
                                                    alt="{{ $item->name }}"
                                                    width="640"
                                                    height="420"
                                                    loading="lazy"
                                                    decoding="async"
                                                    class="w-100 h-100"
                                                    data-menu-parallax
                                                >
                                                <div class="menu-item-media-overlay" aria-hidden="true"></div>
                                                <div class="menu-item-price">&euro;{{ number_format((float) $item->price, 2) }}</div>
                                            </div>

                                            <div class="menu-item-body">
                                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                                    <h3 class="menu-item-title mb-0">{{ $item->name }}</h3>
                                                    @if(!$item->is_available)
                                                        <span class="badge text-bg-secondary">Unavailable</span>
                                                    @endif
                                                </div>

                                                @if($item->description)
                                                    <p class="menu-item-desc mb-3">{{ $item->description }}</p>
                                                @endif

                                                @if(!empty($tags))
                                                    <div class="menu-tags">
                                                        @foreach($tags as $tag)
                                                            <span class="menu-tag">{{ $tag }}</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </article>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rajdhani:wght@400;500;600;700&display=swap');

        .menu-page {
            --menu-red: #db1d30;
            --menu-red-deep: #aa0513;
            --menu-ink: #090909;
            --menu-panel: #121212;
            --menu-panel-soft: #181818;
            --menu-cream: #f2efe8;
            --menu-line: rgba(255, 255, 255, 0.12);
            background:
                radial-gradient(circle at 80% -10%, rgba(219, 29, 48, 0.18), transparent 45%),
                radial-gradient(circle at 15% 20%, rgba(219, 29, 48, 0.10), transparent 55%),
                linear-gradient(180deg, #050505 0%, #090909 35%, #0c0c0c 100%);
            color: #f6f6f6;
            width: 100%;
            max-width: 100%;
            overflow-x: clip;
        }

        .menu-scroll-progress {
            position: fixed;
            inset: 0 0 auto 0;
            z-index: 1055;
            height: 3px;
            background: rgba(255, 255, 255, 0.06);
            pointer-events: none;
        }

        .menu-scroll-progress > span {
            display: block;
            width: 100%;
            height: 100%;
            transform-origin: 0 50%;
            transform: scaleX(0);
            background:
                linear-gradient(90deg, rgba(255, 255, 255, 0.45), rgba(255, 255, 255, 0) 14%),
                linear-gradient(90deg, #ff3442, #da0919 55%, #8f020d);
            box-shadow: 0 0 18px rgba(227, 18, 31, 0.5);
        }

        .menu-hero {
            isolation: isolate;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background:
                linear-gradient(180deg, rgba(0, 0, 0, 0.78), rgba(0, 0, 0, 0.88)),
                repeating-linear-gradient(
                    90deg,
                    rgba(255, 255, 255, 0.015) 0 2px,
                    rgba(0, 0, 0, 0.02) 2px 22px,
                    rgba(255, 255, 255, 0.012) 22px 24px
                ),
                linear-gradient(90deg, #050505, #0a0a0a);
        }

        .menu-hero-bg {
            position: absolute;
            inset: 0;
            opacity: 0.18;
            background:
                radial-gradient(circle at 20% 18%, rgba(255, 255, 255, 0.08), transparent 20%),
                radial-gradient(circle at 82% 15%, rgba(255, 255, 255, 0.06), transparent 20%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), transparent 18%, rgba(255, 255, 255, 0.02) 65%, transparent);
            pointer-events: none;
        }

        .menu-embers {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .menu-ember {
            position: absolute;
            bottom: -6%;
            width: .45rem;
            height: .45rem;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, #ffd1a6, #ff5d23 45%, rgba(227, 18, 31, 0.18) 70%, rgba(227, 18, 31, 0));
            opacity: .4;
            filter: blur(.4px);
        }

        .menu-ember:nth-child(1) { left: 6%; width: .35rem; height: .35rem; }
        .menu-ember:nth-child(2) { left: 13%; width: .6rem; height: .6rem; }
        .menu-ember:nth-child(3) { left: 22%; width: .42rem; height: .42rem; }
        .menu-ember:nth-child(4) { left: 31%; width: .75rem; height: .75rem; }
        .menu-ember:nth-child(5) { left: 41%; width: .4rem; height: .4rem; }
        .menu-ember:nth-child(6) { left: 52%; width: .52rem; height: .52rem; }
        .menu-ember:nth-child(7) { left: 60%; width: .36rem; height: .36rem; }
        .menu-ember:nth-child(8) { left: 69%; width: .68rem; height: .68rem; }
        .menu-ember:nth-child(9) { left: 76%; width: .44rem; height: .44rem; }
        .menu-ember:nth-child(10) { left: 84%; width: .58rem; height: .58rem; }
        .menu-ember:nth-child(11) { left: 90%; width: .38rem; height: .38rem; }
        .menu-ember:nth-child(12) { left: 95%; width: .5rem; height: .5rem; }

        .menu-flames {
            position: absolute;
            bottom: -6rem;
            width: min(28rem, 44vw);
            height: min(24rem, 34vw);
            background:
                radial-gradient(circle at 18% 78%, rgba(227, 18, 31, 0.95), rgba(227, 18, 31, 0) 60%),
                radial-gradient(circle at 52% 58%, rgba(227, 18, 31, 0.88), rgba(227, 18, 31, 0) 62%),
                radial-gradient(circle at 80% 82%, rgba(227, 18, 31, 0.9), rgba(227, 18, 31, 0) 60%);
            filter: blur(10px) saturate(1.1);
            opacity: 0.78;
            z-index: 0;
            pointer-events: none;
            will-change: transform, opacity;
        }

        .menu-flames-left {
            left: -4rem;
            transform: rotate(-6deg);
        }

        .menu-flames-right {
            right: -4rem;
            transform: scaleX(-1) rotate(-6deg);
        }

        .menu-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .35rem .85rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: rgba(255, 255, 255, 0.03);
            font: 600 0.9rem/1 'Rajdhani', sans-serif;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.86);
            margin-bottom: 1rem;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.02);
        }

        .menu-hero-title {
            font-family: 'Bebas Neue', sans-serif;
            line-height: .9;
            font-size: clamp(3.2rem, 10vw, 7rem);
            letter-spacing: .02em;
            text-transform: uppercase;
            margin-inline-start: -0.02em;
        }

        .menu-hero-title .line-wrap {
            display: block;
            overflow: hidden;
        }

        .menu-hero-title .line {
            display: block;
            text-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
        }

        .menu-hero-title .line-light {
            color: #f3f3f3;
        }

        .menu-hero-title .line-red {
            color: var(--menu-red);
        }

        .menu-hero-subtitle {
            max-width: 42rem;
            color: rgba(255, 255, 255, 0.78);
            font: 500 1rem/1.6 'Rajdhani', sans-serif;
            letter-spacing: .01em;
        }

        .menu-cta-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            padding: .75rem 1rem;
            border-radius: .85rem;
            border: 1px solid transparent;
            text-decoration: none;
            cursor: pointer;
            font: 700 .95rem/1 'Rajdhani', sans-serif;
            letter-spacing: .07em;
            text-transform: uppercase;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease, background-color .2s ease;
        }

        .menu-cta-primary {
            background: linear-gradient(180deg, #ff1f2d, #cb0716);
            color: #fff;
            box-shadow: 0 10px 30px rgba(227, 18, 31, 0.28);
        }

        .menu-cta-primary:hover,
        .menu-cta-primary:focus {
            transform: translateY(-2px);
            box-shadow: 0 14px 34px rgba(227, 18, 31, 0.34);
        }

        .menu-cta-ghost {
            background: rgba(255, 255, 255, 0.03);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.16);
        }

        .menu-cta-ghost:hover,
        .menu-cta-ghost:focus {
            color: #fff;
            border-color: rgba(255, 255, 255, 0.32);
            transform: translateY(-2px);
        }

        .menu-hero-visual {
            position: relative;
            --hero-tilt-x: 0deg;
            --hero-tilt-y: 0deg;
            border-radius: 1.6rem;
            padding: 1rem;
            background:
                linear-gradient(160deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.01)),
                rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            transform: perspective(1000px) rotateX(var(--hero-tilt-x)) rotateY(var(--hero-tilt-y));
            transform-style: preserve-3d;
            will-change: transform;
        }

        .menu-hero-visual-glow {
            position: absolute;
            inset: auto -10% -5% -10%;
            height: 50%;
            background: radial-gradient(circle at 50% 50%, rgba(227, 18, 31, 0.45), rgba(227, 18, 31, 0));
            filter: blur(18px);
            pointer-events: none;
        }

        .menu-hero-visual img {
            position: relative;
            z-index: 1;
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 1.2rem;
            transform: translateZ(0);
        }

        .menu-hero-visual::after {
            content: '';
            position: absolute;
            inset: -20% -30% auto auto;
            width: 60%;
            aspect-ratio: 1 / 1;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.14), rgba(255, 255, 255, 0));
            transform: rotate(18deg);
            pointer-events: none;
            z-index: 2;
        }

        .menu-chip-shell {
            top: calc(var(--nav-height, 84px) - 1px);
            z-index: 1020;
            background: rgba(8, 8, 8, 0.78);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .menu-chip-shell::after {
            content: '';
            position: absolute;
            inset: auto 0 0 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(227, 18, 31, 0.38), transparent);
            pointer-events: none;
        }

        .menu-chip-shell .container {
            display: flex;
            justify-content: center;
        }

        .menu-chip-rail {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: .75rem;
            padding: .35rem 0 1.0rem;
            /*width: min(1120px, 100%);*/
            margin: 0 auto;
        }

        .menu-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            flex: 0 0 196px;
            width: 196px;
            min-height: 3.1rem;
            gap: .55rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.03);
            color: rgba(255, 255, 255, 0.82);
            padding: .78rem 1.2rem;
            font: 700 .88rem/1 'Rajdhani', sans-serif;
            letter-spacing: .06em;
            text-transform: uppercase;
            transition: transform .18s ease, border-color .18s ease, background-color .18s ease, color .18s ease;
            position: relative;
            overflow: hidden;
        }

        @media (max-width: 640px) {
            .menu-chip {
                flex: 1 1 calc(50% - .75rem);
                width: auto;
            }
        }

        .menu-chip::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(115deg, transparent 25%, rgba(255, 255, 255, 0.15), transparent 68%);
            transform: translateX(-120%);
            transition: transform .45s ease;
            pointer-events: none;
        }

        .menu-chip small {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.5rem;
            height: 1.5rem;
            padding: 0 .35rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            font-size: .75rem;
            line-height: 1;
        }

        .menu-chip:hover,
        .menu-chip:focus {
            transform: translateY(-1px);
            border-color: rgba(255, 255, 255, 0.28);
        }

        .menu-chip:hover::before,
        .menu-chip:focus::before {
            transform: translateX(115%);
        }

        .menu-chip.is-active {
            background: linear-gradient(180deg, rgba(255, 29, 45, 0.18), rgba(170, 5, 19, 0.18));
            border-color: rgba(255, 60, 72, 0.55);
            color: #fff;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.04), 0 8px 24px rgba(227, 18, 31, 0.18);
        }

        .menu-catalog {
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.015), rgba(255, 255, 255, 0)),
                linear-gradient(180deg, #0a0a0a, #0d0d0d);
        }

        .menu-category-section {
            scroll-margin-top: calc(var(--nav-height, 84px) + 92px);
        }

        .menu-category-header-panel {
            position: relative;
            border-radius: 1.1rem;
            padding: 1.1rem 1.1rem 1rem;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0)),
                #131313;
            border: 1px solid var(--menu-line);
            overflow: hidden;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.15);
        }

        .menu-category-header-panel::before {
            content: '';
            position: absolute;
            inset: 0 auto 0 0;
            width: 5px;
            background: linear-gradient(180deg, #ff2937, #9f0310);
        }

        .menu-kicker {
            color: rgba(255, 255, 255, 0.55);
            font: 700 .9rem/1 'Rajdhani', sans-serif;
            letter-spacing: .22em;
            text-transform: uppercase;
            margin-bottom: .45rem;
        }

        .menu-category-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2rem, 4vw, 3.2rem);
            line-height: .92;
            color: #fff;
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        .menu-category-copy {
            font: 500 1rem/1.55 'Rajdhani', sans-serif;
            color: rgba(255, 255, 255, 0.74);
            max-width: 44rem;
        }

        .menu-category-banner {
            position: relative;
            border-radius: 1.1rem;
            overflow: hidden;
            border: 1px solid var(--menu-line);
            min-height: 13rem;
            background: #0f0f0f;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.16);
        }

        .menu-category-banner img {
            object-fit: cover;
            transform: scale(1.02);
            will-change: transform;
        }

        .menu-category-banner-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(0, 0, 0, 0.08), rgba(0, 0, 0, 0.56)),
                linear-gradient(120deg, rgba(227, 18, 31, 0.2), rgba(227, 18, 31, 0));
            pointer-events: none;
        }

        .menu-item-card {
            --tilt-x: 0deg;
            --tilt-y: 0deg;
            --lift: 0px;
            --glare-x: 50%;
            --glare-y: 50%;
            position: relative;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0)),
                var(--menu-panel);
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.22);
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
            transform: perspective(900px) rotateX(var(--tilt-x)) rotateY(var(--tilt-y)) translateY(var(--lift));
            transform-style: preserve-3d;
            will-change: transform;
        }

        .menu-item-card:hover {
            --lift: -5px;
            border-color: rgba(255, 255, 255, 0.18);
            box-shadow: 0 22px 42px rgba(0, 0, 0, 0.3);
        }

        .menu-item-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            border: 1px solid rgba(255, 255, 255, 0.03);
            pointer-events: none;
        }

        .menu-item-glare {
            position: absolute;
            inset: -40%;
            background:
                radial-gradient(circle at var(--glare-x) var(--glare-y), rgba(255, 255, 255, 0.14), rgba(255, 255, 255, 0) 36%),
                radial-gradient(circle at calc(var(--glare-x) + 10%) calc(var(--glare-y) + 8%), rgba(227, 18, 31, 0.12), rgba(227, 18, 31, 0) 42%);
            opacity: 0;
            transition: opacity .25s ease;
            pointer-events: none;
            z-index: 0;
        }

        .menu-item-card:hover .menu-item-glare {
            opacity: 1;
        }

        .menu-item-media {
            position: relative;
            height: 13rem;
            overflow: hidden;
            background: #0f0f0f;
            transform: translateZ(14px);
        }

        .menu-item-media img {
            object-fit: cover;
            transform: scale(1.03);
            transition: transform .35s ease, filter .35s ease;
            will-change: transform;
        }

        .menu-item-card:hover .menu-item-media img {
            transform: scale(1.08);
            filter: saturate(1.05) contrast(1.03);
        }

        .menu-item-media-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(0, 0, 0, 0.06), rgba(0, 0, 0, 0.68)),
                radial-gradient(circle at 15% 90%, rgba(227, 18, 31, 0.24), transparent 42%);
            pointer-events: none;
        }

        .menu-item-price {
            position: absolute;
            right: .75rem;
            bottom: .75rem;
            z-index: 1;
            background: linear-gradient(180deg, #ff2130, #c70716);
            color: #fff;
            border-radius: .7rem;
            padding: .45rem .65rem .35rem;
            box-shadow: 0 10px 22px rgba(227, 18, 31, 0.3);
            font: 700 1rem/1 'Rajdhani', sans-serif;
            letter-spacing: .03em;
        }

        .menu-item-body {
            padding: 1rem 1rem 1.05rem;
            position: relative;
            z-index: 1;
            transform: translateZ(10px);
        }

        .menu-item-title {
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 1.15rem;
            line-height: 1.05;
            letter-spacing: .01em;
            text-transform: uppercase;
        }

        .menu-item-desc {
            color: rgba(255, 255, 255, 0.72);
            font: 500 .98rem/1.45 'Rajdhani', sans-serif;
        }

        .menu-tags {
            display: flex;
            flex-wrap: wrap;
            gap: .45rem;
        }

        .menu-tag {
            display: inline-flex;
            align-items: center;
            padding: .36rem .55rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.09);
            background: rgba(255, 255, 255, 0.03);
            color: rgba(255, 255, 255, 0.86);
            font: 700 .72rem/1 'Rajdhani', sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .py-lg-6 {
            padding-top: 5rem !important;
            padding-bottom: 5rem !important;
        }

        .mb-lg-6 {
            margin-bottom: 5rem !important;
        }

        @media (max-width: 991.98px) {
            .menu-chip-shell {
                top: calc(var(--nav-height, 84px) - 1px);
            }

            .menu-item-media {
                height: 12rem;
            }

            .menu-flames {
                opacity: .52;
                bottom: -7rem;
            }
        }

        @media (max-width: 575.98px) {
            .menu-page .container {
                padding-inline: 1rem;
            }

            .menu-hero-title {
                font-size: clamp(2.7rem, 16vw, 4.2rem);
            }

            .menu-hero-subtitle {
                font-size: .95rem;
            }

            .menu-cta-btn {
                width: 100%;
            }

            .menu-category-header-panel,
            .menu-category-banner,
            .menu-item-card {
                border-radius: .9rem;
            }

            .menu-item-body {
                padding: .9rem;
            }

            .menu-item-title {
                font-size: 1.05rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .menu-cta-btn,
            .menu-chip,
            .menu-item-card,
            .menu-item-media img,
            .menu-chip::before {
                transition: none !important;
            }

            .menu-item-card:hover,
            .menu-cta-primary:hover,
            .menu-cta-ghost:hover {
                transform: none !important;
            }

            .menu-scroll-progress {
                display: none;
            }
        }

        body[data-performance-mode="lite"] .menu-embers,
        body[data-performance-mode="lite"] .menu-flames,
        body[data-performance-mode="lite"] .menu-item-glare {
            display: none;
        }

        body[data-performance-mode="lite"] .menu-chip-shell {
            backdrop-filter: none;
        }

        body[data-performance-mode="lite"] .menu-hero-visual {
            transform: none !important;
        }

        body[data-performance-mode="lite"] .menu-item-card,
        body[data-performance-mode="lite"] .menu-item-media img,
        body[data-performance-mode="lite"] .menu-chip::before {
            transition-duration: .12s !important;
        }
    </style>
@endpush
