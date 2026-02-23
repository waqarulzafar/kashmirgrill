@extends('layouts.master')

@section('title', 'Book Now | Kashmir Grill House')
@section('meta_description', 'Reserve your table at Kashmir Grill House online. Choose date, time, party size, and dining preference in minutes.')

@section('hero')
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <h1 class="display-6 fw-bold mb-3">Book Now</h1>
                <p class="lead mb-0">Reserve your seat for lunch, dinner, or special celebrations.</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="container py-4" id="book-now">
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <article class="p-4 p-md-5 bg-white rounded-4 shadow-sm">
                    <h2 class="h4 mb-4">Reservation Details</h2>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>There were problems with your booking:</strong>
                            <ul class="mb-0 mt-2 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="row g-3" method="POST" action="{{ route('bookings.store') }}">
                        @csrf
                        <input type="hidden" name="form_rendered_at" value="{{ now()->timestamp }}">

                        <div class="d-none" aria-hidden="true">
                            <label for="website">Website</label>
                            <input id="website" name="website" type="text" autocomplete="off" tabindex="-1">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="full_name">Full Name</label>
                            <input id="full_name" name="full_name" class="form-control" type="text" value="{{ old('full_name') }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="email">Email</label>
                            <input id="email" name="email" class="form-control" type="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="phone">Phone</label>
                            <input id="phone" name="phone" class="form-control" type="tel" value="{{ old('phone') }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="persons">Persons</label>
                            <input id="persons" name="persons" class="form-control" type="number" min="1" max="40" value="{{ old('persons', 2) }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="date">Date</label>
                            <input id="date" name="date" class="form-control" type="date" value="{{ old('date') }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="time">Time</label>
                            <input id="time" name="time" class="form-control" type="time" value="{{ old('time') }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="table_preference">Table Preference</label>
                            <select id="table_preference" name="table_preference" class="form-select">
                                <option value="">No preference</option>
                                <option value="Window" @selected(old('table_preference') === 'Window')>Window</option>
                                <option value="Quiet Corner" @selected(old('table_preference') === 'Quiet Corner')>Quiet Corner</option>
                                <option value="Family Seating" @selected(old('table_preference') === 'Family Seating')>Family Seating</option>
                                <option value="Outdoor" @selected(old('table_preference') === 'Outdoor')>Outdoor</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="selected_menu">Selected Menu</label>
                            <select id="selected_menu" name="selected_menu" class="form-select">
                                <option value="">Choose menu focus</option>
                                <option value="A la carte" @selected(old('selected_menu') === 'A la carte')>A la carte</option>
                                <option value="Family Sharing" @selected(old('selected_menu') === 'Family Sharing')>Family Sharing</option>
                                <option value="Vegetarian" @selected(old('selected_menu') === 'Vegetarian')>Vegetarian</option>
                                <option value="Chef Specials" @selected(old('selected_menu') === 'Chef Specials')>Chef Specials</option>
                                <option value="Festival Menu" @selected(old('selected_menu') === 'Festival Menu')>Festival Menu</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="additional_notes">Additional Notes</label>
                            <textarea id="additional_notes" name="additional_notes" class="form-control" rows="4" placeholder="Dietary notes, occasion, seating preference">{{ old('additional_notes') }}</textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-brand">Send Reservation Request</button>
                        </div>
                    </form>
                </article>
            </div>
            <div class="col-12 col-lg-4">
                <article class="p-4 bg-white rounded-4 shadow-sm h-100">
                    <h2 class="h5 section-accent mb-3">Booking Notes</h2>
                    <ul class="ps-3 mb-0">
                        <li>Walk-ins accepted subject to availability.</li>
                        <li>Please arrive 10 minutes before your slot.</li>
                        <li>For 10+ guests, include details in additional notes.</li>
                        <li>Confirmation is sent after team review.</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>
@endsection
