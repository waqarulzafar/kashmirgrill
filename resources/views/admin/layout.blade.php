@extends('layouts.master')

@section('hero')
    <div class="container">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-lg-8">
                <h1 class="display-6 fw-bold mb-2">@yield('admin_title', 'Admin')</h1>
                <p class="lead mb-0 text-secondary">Simple, fast management for categories and menu items.</p>
            </div>
            <div class="col-12 col-lg-4 text-lg-end">
                <a href="{{ route('home') }}" class="btn btn-brand-outline">View Site</a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="container py-4">
        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('admin.menu-categories.index') }}" class="btn {{ request()->routeIs('admin.menu-categories.*') ? 'btn-brand' : 'btn-outline-secondary' }} btn-sm">Menu Categories</a>
            <a href="{{ route('admin.menu-items.index') }}" class="btn {{ request()->routeIs('admin.menu-items.*') ? 'btn-brand' : 'btn-outline-secondary' }} btn-sm">Menu Items</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('admin_content')
    </section>
@endsection
