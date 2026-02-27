@extends('layouts.master')

@section('title', 'Group Dining & Occasions | Kashmir Grill House Como')
@section('meta_description', 'Plan group dining and occasions at Kashmir Grill House in Como with halal Pakistani and Indian menu options for families, birthdays, and gatherings.')
@section('meta_keywords', 'group dining Como, Kashmir Grill House events, family dinner Como, birthday dinner halal Como')

@php
    $eventCategories = [
        [
            'name' => 'Ceremonies',
            'image' => 'assets/images/events/ceremonies.jpg',
            'description' => 'Elegant setups for engagements, anniversaries, and family milestones with curated dining experiences.',
            'details' => 'Includes table styling options, set-menu guidance, and dedicated service flow for multi-course celebrations.',
        ],
        [
            'name' => 'Get Together',
            'image' => 'assets/images/events/get-together.jpg',
            'description' => 'Relaxed group dining for friends and families with sharable platters and flexible seating.',
            'details' => 'Best for casual evenings, weekend reunions, and social dinners with mixed vegetarian and non-vegetarian menus.',
        ],
        [
            'name' => 'Meetings',
            'image' => 'assets/images/events/meetings.jpg',
            'description' => 'Quiet and comfortable arrangements for team lunches, client discussions, and small business gatherings.',
            'details' => 'Midday slots with efficient service, custom meal pacing, and optional tea/coffee add-ons.',
        ],
        [
            'name' => 'Conferences',
            'image' => 'assets/images/events/conferences.jpg',
            'description' => 'Structured event dining support for large professional groups with timed serving plans.',
            'details' => 'Suitable for conference delegates and workshop groups requiring pre-planned buffet or plated service.',
        ],
        [
            'name' => "Valentine's Day",
            'image' => 'assets/images/events/valentines-day.jpg',
            'description' => 'Romantic dining ambiance with chef specials and festive dessert pairings for couples.',
            'details' => 'Advance booking recommended for peak dinner slots and custom celebration notes.',
        ],
        [
            'name' => 'Festivals (Eid, Ramadan, Easter, Christmas)',
            'image' => 'assets/images/events/festivals.jpg',
            'description' => 'Seasonal menus and celebration dining for major festive moments throughout the year.',
            'details' => 'Festival packages can include themed platters, extended family seating, and pre-order recommendations.',
        ],
    ];
@endphp

@section('hero')
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <h1 class="display-6 fw-bold mb-3">Events & Occasions</h1>
                <p class="lead mb-0">Celebrate life's moments in a premium setting with customizable food and hospitality packages.</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="container py-5">
        <x-section-header
            badge="Occasion Categories"
            title="Choose the Right Event Experience"
            subtitle="Every category includes premium dining flow, attentive service, and straightforward reservation support."
        />

        <div class="row g-4">
            @foreach($eventCategories as $index => $category)
                @php
                    $slug = \Illuminate\Support\Str::slug($category['name']);
                    $modalId = 'event-details-' . $slug;
                @endphp
                <div class="col-12 col-md-6 col-xl-4">
                    <article id="event-{{ $slug }}" class="event-card h-100 rounded-4 bg-white shadow-sm overflow-hidden">
                        <div class="event-banner">
                            <img
                                src="{{ asset($category['image']) }}"
                                alt="{{ $category['name'] }} banner image"
                                class="w-100 h-100"
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                        <div class="p-4">
                            <h2 class="h4 mb-3">{{ $category['name'] }}</h2>
                            <p class="text-secondary mb-4">{{ $category['description'] }}</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('book-now') }}" class="btn btn-brand btn-sm">Book Now</a>
                                @if(!empty($category['details']))
                                    <button type="button" class="btn btn-brand-outline btn-sm" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                                        View Details
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </section>

    @foreach($eventCategories as $category)
        @php
            $slug = \Illuminate\Support\Str::slug($category['name']);
            $modalId = 'event-details-' . $slug;
        @endphp
        @if(!empty($category['details']))
            <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}-label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0 shadow">
                        <div class="modal-header border-0 pb-0">
                            <h2 class="modal-title h5" id="{{ $modalId }}-label">{{ $category['name'] }}</h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-2">
                            <p class="mb-0 text-secondary">{{ $category['details'] }}</p>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <a href="{{ route('book-now') }}" class="btn btn-brand btn-sm">Book Now</a>
                            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection

@push('styles')
    <style>
        .event-card {
            border: 1px solid rgba(219, 29, 48, 0.12);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .event-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.12) !important;
            border-color: rgba(255, 149, 44, 0.45);
        }

        .event-banner {
            aspect-ratio: 16 / 7;
            overflow: hidden;
        }

        .event-card:hover .event-banner img {
            transform: scale(1.03);
        }

        .event-banner img {
            object-fit: cover;
            transition: transform .4s ease;
        }

        @media (prefers-reduced-motion: reduce) {
            .event-card,
            .event-banner img {
                transition: none;
            }

            .event-card:hover {
                transform: none;
            }
        }
    </style>
@endpush
