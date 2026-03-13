@extends('layouts.master')

@section('title', 'Order Placed | Kashmir Grill House Como')
@section('meta_description', 'Your Kashmir Grill House order has been placed successfully.')

@section('hero')
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <h1 class="display-6 fw-bold mb-3">Order Confirmed</h1>
                <p class="lead mb-0">Thank you. Your order has been received by our team.</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="container py-4">
        <article class="p-4 p-md-5 rounded-4 shadow-sm bg-white">
            <h2 class="h4 mb-3">Order Submitted Successfully</h2>
            @if(session('order_reference'))
                <p class="mb-2">Reference: <strong>{{ session('order_reference') }}</strong></p>
            @endif
            <p class="mb-4">We will process your order shortly. For urgent updates, please call <a href="tel:+393511203141">+39 351 1203141</a>.</p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('menu') }}" class="btn btn-brand">Back to Menu</a>
                <a href="{{ route('home') }}" class="btn btn-brand-outline">Go Home</a>
            </div>
        </article>
    </section>
@endsection
