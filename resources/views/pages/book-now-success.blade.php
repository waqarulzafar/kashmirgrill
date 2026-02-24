@extends('layouts.master')

@section('title', 'Booking Confirmed | Kashmir Grill House')
@section('meta_description', 'Your booking request has been received by Kashmir Grill House. We will confirm your reservation shortly.')

@section('hero')
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <h1 class="display-6 fw-bold mb-3">Booking Request Received</h1>
                <p class="lead mb-0">Thank you. Our team will review your details and contact you soon.</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="alert p-4 rounded-4 shadow-sm booking-success-alert" role="alert">
                    <h2 class="h4 mb-2">Success</h2>
                    <p class="mb-2">Your booking request has been submitted successfully.</p>
                    @if(session('booking_reference'))
                        <p class="mb-3">Reference: <strong>#{{ session('booking_reference') }}</strong></p>
                    @endif
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('home') }}" class="btn btn-brand btn-sm">Back to Home</a>
                        <a href="{{ route('book-now') }}" class="btn btn-brand-outline btn-sm">Create Another Booking</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('styles')
    <style>
        .booking-success-alert {
            background: linear-gradient(180deg, rgba(255, 149, 44, 0.14), rgba(255, 149, 44, 0.05));
            border: 1px solid rgba(255, 149, 44, 0.3);
            color: #123418;
        }
    </style>
@endpush
