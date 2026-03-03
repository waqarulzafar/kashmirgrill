@extends('layouts.master')

@section('title', $event['name'] . ' | Events | Kashmir Grill House Como')
@section('meta_description', $event['description'])
@section('body_class', 'home-menu-theme')

@section('content')
    <div class="event-detail-page py-5">
        <section class="container">
            <div class="event-detail-shell">
                <div class="row g-4 g-lg-5 align-items-stretch">
                    <div class="col-12 col-lg-6">
                        <div class="event-detail-media">
                            <img
                                src="{{ asset($event['image']) }}"
                                alt="{{ $event['name'] }}"
                                class="w-100 h-100"
                                loading="eager"
                                decoding="async"
                            >
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="event-detail-content h-100">
                            <a href="{{ route('events') }}" class="event-detail-back">Back to Events</a>
                            <p class="event-detail-kicker mb-2">Event Type</p>
                            <h1 class="event-detail-title mb-3">{{ $event['name'] }}</h1>
                            <p class="event-detail-summary mb-3">{{ $event['summary'] }}</p>
                            <p class="event-detail-copy mb-4">{{ $event['details'] }}</p>

                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-6">
                                    <div class="event-detail-panel h-100">
                                        <h2 class="event-detail-panel__title mb-2">Ideal For</h2>
                                        <ul class="event-detail-list">
                                            @foreach($event['ideal_for'] as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="event-detail-panel h-100">
                                        <h2 class="event-detail-panel__title mb-2">Highlights</h2>
                                        <ul class="event-detail-list">
                                            @foreach($event['highlights'] as $highlight)
                                                <li>{{ $highlight }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('book-now') }}" class="btn btn-brand">Book This Event</a>
                                <a href="{{ route('contact') }}" class="btn btn-brand-outline">Contact Team</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if(!empty($relatedEvents))
            <section class="container pt-4 pt-lg-5">
                <div class="event-detail-related">
                    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                        <div>
                            <p class="event-detail-kicker mb-2">Other Occasion Types</p>
                            <h2 class="event-detail-related__title mb-0">Explore More Event Formats</h2>
                        </div>
                        <a href="{{ route('events') }}" class="event-detail-back m-0">All Events</a>
                    </div>

                    <div class="row g-4">
                        @foreach($relatedEvents as $relatedEvent)
                            <div class="col-12 col-md-6 col-xl-4">
                                <a href="{{ route('events.show', $relatedEvent['slug']) }}" class="event-detail-related__card">
                                    <div class="event-detail-related__media">
                                        <img
                                            src="{{ asset($relatedEvent['image']) }}"
                                            alt="{{ $relatedEvent['name'] }}"
                                            class="w-100 h-100"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                    </div>
                                    <div class="event-detail-related__body">
                                        <h3 class="event-detail-related__item-title mb-2">{{ $relatedEvent['name'] }}</h3>
                                        <p class="event-detail-related__copy mb-0">{{ $relatedEvent['description'] }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
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

        .event-detail-page {
            background:
                radial-gradient(circle at 84% 4%, rgba(219, 29, 48, 0.16), transparent 34%),
                radial-gradient(circle at 12% 14%, rgba(255, 149, 44, 0.08), transparent 34%),
                linear-gradient(180deg, rgba(5, 5, 5, 0.98) 0%, rgba(11, 11, 11, 0.94) 100%);
            color: #f5f5f5;
            min-height: calc(100vh - var(--nav-height, 84px));
        }

        .event-detail-shell,
        .event-detail-related,
        .event-detail-panel,
        .event-detail-related__card {
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0)),
                #121212;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.2);
        }

        .event-detail-shell,
        .event-detail-related {
            padding: clamp(1rem, 2.8vw, 1.5rem);
        }

        .event-detail-media,
        .event-detail-related__media {
            overflow: hidden;
            border-radius: 1rem;
            background: #0b0b0b;
            border: 1px solid rgba(255,255,255,.08);
        }

        .event-detail-media {
            min-height: 24rem;
        }

        .event-detail-media img,
        .event-detail-related__media img {
            object-fit: cover;
        }

        .event-detail-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .event-detail-back,
        .event-detail-related__card {
            color: inherit;
            text-decoration: none;
        }

        .event-detail-back {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            color: rgba(255,255,255,.72);
            font: 700 .84rem/1 'Rajdhani', sans-serif;
            letter-spacing: .1em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .event-detail-kicker {
            color: rgba(255,255,255,.58);
            font: 700 .78rem/1 'Rajdhani', sans-serif;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .event-detail-title,
        .event-detail-related__title,
        .event-detail-panel__title,
        .event-detail-related__item-title {
            color: #fff;
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        .event-detail-title {
            font-size: clamp(2.4rem, 5vw, 4rem);
            line-height: .96;
        }

        .event-detail-related__title {
            font-size: clamp(1.8rem, 3.8vw, 2.8rem);
            line-height: .96;
        }

        .event-detail-summary,
        .event-detail-copy,
        .event-detail-related__copy,
        .event-detail-list {
            color: rgba(255,255,255,.78);
            font: 500 1rem/1.7 'Rajdhani', sans-serif;
        }

        .event-detail-panel {
            padding: 1rem;
        }

        .event-detail-list {
            margin: 0;
            padding-left: 1.1rem;
        }

        .event-detail-list li {
            margin-bottom: .45rem;
        }

        .event-detail-related__media {
            height: 13rem;
        }

        .event-detail-related__body {
            padding: 1rem;
        }
    </style>
@endpush
