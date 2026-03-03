@extends('layouts.master')

@section('title', 'Events & Occasions | Kashmir Grill House Como')
@section('meta_description', 'Discover private dining, celebrations, and event hosting at Kashmir Grill House with curated halal menus and structured reservation support.')
@section('body_class', 'home-menu-theme')

@section('content')
    <div class="events-premium-page py-5">
        <section class="container pb-4 pb-lg-5">
            <div class="events-hero">
                <div class="row g-4 align-items-center">
                    <div class="col-12 col-lg-7">
                        <span class="badge badge-brand rounded-pill mb-3">Events & Occasions</span>
                        <h1 class="events-hero__title mb-3">Private Dining and Occasion Hosting with the Same Premium Hospitality</h1>
                        <p class="events-hero__copy mb-4">
                            Kashmir Grill House offers event-focused dining for celebrations, family gatherings, and professional occasions, with halal menu planning, coordinated service, and advance booking support.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('book-now') }}" class="btn btn-brand">Book Event</a>
                            <a href="{{ route('contact') }}" class="btn btn-brand-outline">Speak to Team</a>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5">
                        <div class="events-hero__panel">
                            <div class="events-hero__panel-item">
                                <span>Event Types</span>
                                <strong>{{ count($events) }} curated options</strong>
                            </div>
                            <div class="events-hero__panel-item">
                                <span>Food Standard</span>
                                <strong>100% halal kitchen</strong>
                            </div>
                            <div class="events-hero__panel-item">
                                <span>Planning Support</span>
                                <strong>Menu and seating coordination</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container pb-4 pb-lg-5">
            <div class="events-service-strip">
                <span>Private Celebrations</span>
                <span>Family Gatherings</span>
                <span>Corporate Dining</span>
                <span>Advance Menu Planning</span>
                <span>Halal Group Service</span>
                <span>Seasonal Occasions</span>
            </div>
        </section>

        <section class="container pb-4 pb-lg-5">
            <x-section-header
                badge="Event Categories"
                title="Choose the Right Event Format for Your Guests"
                subtitle="Each event category includes a dedicated overview, service direction, and booking path so guests can understand exactly what we provide."
            />

            <div class="row g-4">
                @foreach($events as $event)
                    <div class="col-12 col-md-6 col-xl-4">
                        <article class="events-card h-100" id="event-{{ $event['slug'] }}">
                            <a href="{{ route('events.show', $event['slug']) }}" class="events-card__media">
                                <img
                                    src="{{ asset($event['image']) }}"
                                    alt="{{ $event['name'] }}"
                                    class="w-100 h-100"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </a>
                            <div class="events-card__body">
                                <p class="events-card__eyebrow mb-2">Occasion Type</p>
                                <h2 class="events-card__title mb-2">{{ $event['name'] }}</h2>
                                <p class="events-card__copy mb-3">{{ $event['description'] }}</p>

                                @if(!empty($event['highlights']))
                                    <div class="events-card__tags mb-4">
                                        @foreach($event['highlights'] as $highlight)
                                            <span>{{ $highlight }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('events.show', $event['slug']) }}" class="btn btn-brand btn-sm">View Details</a>
                                    <a href="{{ route('book-now') }}" class="btn btn-brand-outline btn-sm">Book Now</a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="container pb-4 pb-lg-5">
            <div class="row g-4">
                <div class="col-12 col-lg-6">
                    <div class="events-info-panel h-100">
                        <p class="events-card__eyebrow mb-2">What We Handle</p>
                        <h2 class="events-info-panel__title mb-3">Professional Event Dining Without Unclear Planning</h2>
                        <ul class="events-info-panel__list">
                            <li>Guest-focused seating and table planning</li>
                            <li>Advance menu coordination before the event date</li>
                            <li>Flexible service pacing for family and business occasions</li>
                            <li>Support for shared platters and mixed menu preferences</li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="events-info-panel h-100">
                        <p class="events-card__eyebrow mb-2">Booking Process</p>
                        <h2 class="events-info-panel__title mb-3">A Clear Reservation Flow for Private Occasions</h2>
                        <ol class="events-info-panel__steps">
                            <li>Choose the event type that fits your gathering</li>
                            <li>Submit booking details with guest count and notes</li>
                            <li>Confirm menu direction and table requirements with our team</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        body.home-menu-theme {
            background:
                radial-gradient(circle at 86% -12%, rgba(219, 29, 48, 0.22), transparent 42%),
                radial-gradient(circle at 12% 8%, rgba(255, 149, 44, 0.14), transparent 45%),
                linear-gradient(180deg, #050505 0%, #090909 34%, #0d0d0d 100%);
            color: #f2f2f2;
        }

        body.home-menu-theme main {
            background: transparent;
        }

        .events-premium-page {
            background:
                radial-gradient(circle at 84% 4%, rgba(219, 29, 48, 0.16), transparent 34%),
                radial-gradient(circle at 12% 14%, rgba(255, 149, 44, 0.08), transparent 34%),
                linear-gradient(180deg, rgba(5, 5, 5, 0.98) 0%, rgba(11, 11, 11, 0.94) 100%);
            color: #f5f5f5;
            min-height: calc(100vh - var(--nav-height, 84px));
        }

        .events-hero,
        .events-service-strip,
        .events-card,
        .events-info-panel {
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0)),
                #121212;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.2);
        }

        .events-hero {
            padding: clamp(1.2rem, 3vw, 1.8rem);
            background:
                radial-gradient(circle at 88% 18%, rgba(255, 149, 44, 0.12), transparent 34%),
                radial-gradient(circle at 8% 88%, rgba(219, 29, 48, 0.14), transparent 38%),
                linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,0)),
                #111;
        }

        .events-hero__title,
        .events-info-panel__title,
        .events-card__title {
            color: #fff;
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: .02em;
            text-transform: uppercase;
            line-height: .96;
        }

        .events-hero__title {
            font-size: clamp(2.3rem, 5vw, 4.4rem);
        }

        .events-hero__copy,
        .events-card__copy {
            color: rgba(255, 255, 255, 0.76);
            font: 500 1rem/1.65 'Rajdhani', sans-serif;
        }

        .events-hero__panel {
            display: grid;
            gap: .9rem;
        }

        .events-hero__panel-item {
            padding: 1rem;
            border-radius: 1rem;
            border: 1px solid rgba(255,255,255,.08);
            background: rgba(255,255,255,.03);
        }

        .events-hero__panel-item span,
        .events-card__eyebrow {
            display: block;
            color: rgba(255, 255, 255, 0.58);
            font: 700 .78rem/1 'Rajdhani', sans-serif;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .events-hero__panel-item strong {
            display: block;
            margin-top: .35rem;
            color: #fff;
            font: 700 1rem/1.3 'Rajdhani', sans-serif;
            text-transform: uppercase;
        }

        .events-service-strip {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: .65rem;
            padding: .95rem 1rem;
        }

        .events-service-strip span,
        .events-card__tags span {
            display: inline-flex;
            align-items: center;
            padding: .42rem .7rem;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,.08);
            background: rgba(255,255,255,.04);
            color: rgba(255, 255, 255, 0.86);
            font: 700 .72rem/1 'Rajdhani', sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .events-card {
            overflow: hidden;
            height: 100%;
        }

        .events-card__media {
            display: block;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: #0a0a0a;
        }

        .events-card__media img {
            object-fit: cover;
            transition: transform .35s ease;
        }

        .events-card:hover .events-card__media img {
            transform: scale(1.04);
        }

        .events-card__body,
        .events-info-panel {
            padding: 1.1rem;
        }

        .events-card__tags {
            display: flex;
            flex-wrap: wrap;
            gap: .45rem;
        }

        .events-info-panel__title {
            font-size: clamp(1.9rem, 3.5vw, 2.7rem);
        }

        .events-info-panel__list,
        .events-info-panel__steps {
            margin: 0;
            padding-left: 1.2rem;
            color: rgba(255, 255, 255, 0.78);
            font: 500 1rem/1.7 'Rajdhani', sans-serif;
        }

        .events-info-panel__list li,
        .events-info-panel__steps li {
            margin-bottom: .55rem;
        }
    </style>
@endpush
