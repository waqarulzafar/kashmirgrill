@extends('layouts.master')

@section('title', 'Events | Kashmir Grill House')
@section('meta_description', 'Plan weddings, birthdays, meetings, and seasonal celebrations at Kashmir Grill House with flexible event packages.')

@php
    $eventCategories = [
        [
            'name' => 'Ceremonies',
            'description' => 'Elegant setups for engagements, anniversaries, and family milestones with curated dining experiences.',
            'details' => 'Includes table styling options, set-menu guidance, and dedicated service flow for multi-course celebrations.',
        ],
        [
            'name' => 'Get Together',
            'description' => 'Relaxed group dining for friends and families with sharable platters and flexible seating.',
            'details' => 'Best for casual evenings, weekend reunions, and social dinners with mixed vegetarian and non-vegetarian menus.',
        ],
        [
            'name' => 'Meetings',
            'description' => 'Quiet and comfortable arrangements for team lunches, client discussions, and small business gatherings.',
            'details' => 'Midday slots with efficient service, custom meal pacing, and optional tea/coffee add-ons.',
        ],
        [
            'name' => 'Conferences',
            'description' => 'Structured event dining support for large professional groups with timed serving plans.',
            'details' => 'Suitable for conference delegates and workshop groups requiring pre-planned buffet or plated service.',
        ],
        [
            'name' => "Valentine's Day",
            'description' => 'Romantic dining ambiance with chef specials and festive dessert pairings for couples.',
            'details' => 'Advance booking recommended for peak dinner slots and custom celebration notes.',
        ],
        [
            'name' => 'Festivals (Eid, Ramadan, Easter, Christmas)',
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
                            <svg viewBox="0 0 640 280" role="img" aria-label="{{ $category['name'] }} banner image" class="w-100 h-100">
                                <defs>
                                    <linearGradient id="event-gradient-{{ $slug }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#000000"></stop>
                                        <stop offset="55%" stop-color="#db1d30"></stop>
                                        <stop offset="100%" stop-color="#ff952c"></stop>
                                    </linearGradient>
                                </defs>
                                <rect x="0" y="0" width="640" height="280" fill="url(#event-gradient-{{ $slug }})"></rect>
                                <circle cx="{{ 110 + ($index * 8) }}" cy="{{ 82 + ($index * 3) }}" r="66" fill="rgba(255,255,255,0.12)"></circle>
                                <circle cx="534" cy="192" r="96" fill="rgba(255,255,255,0.08)"></circle>
                                <text x="38" y="170" fill="#ffffff" font-size="34" font-weight="600" style="font-family: Poppins, sans-serif;">
                                    {{ $category['name'] }}
                                </text>
                            </svg>
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

        .event-card:hover .event-banner svg {
            transform: scale(1.03);
        }

        .event-banner svg {
            transition: transform .4s ease;
        }

        @media (prefers-reduced-motion: reduce) {
            .event-card,
            .event-banner svg {
                transition: none;
            }

            .event-card:hover {
                transform: none;
            }
        }
    </style>
@endpush
