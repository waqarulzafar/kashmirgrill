@extends('layouts.mainadmin')

@section('title')
    <div id="kt_app_header_page_title_wrapper">
        <div class="page-title d-flex flex-column justify-content-center me-3 mb-0">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                @yield('admin_title', 'Admin')
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Admin</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Management</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <div class="admin-shell">
                        <section class="admin-page-hero">
                            <div class="row g-5 align-items-center">
                                <div class="col-lg-8">
                                    <span class="admin-page-kicker">Admin Workspace</span>
                                    <h1 class="admin-page-title">@yield('admin_title', 'Admin')</h1>
                                    <p class="admin-page-description">@yield('admin_description', 'Manage the restaurant operations, monitor live activity, and keep the customer-facing experience aligned across bookings, menu, and orders.')</p>
                                </div>
                                <div class="col-lg-4">
                                    @hasSection('admin_actions')
                                        <div class="admin-page-actions">
                                            @yield('admin_actions')
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </section>

                        <nav class="admin-page-tabs" aria-label="Admin Sections">
                            <a href="{{ route('admin.dashboard') }}" class="admin-page-tab {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Dashboard</a>
                            <a href="{{ route('admin.menu-categories.index') }}" class="admin-page-tab {{ request()->routeIs('admin.menu-categories.*') ? 'is-active' : '' }}">Menu Categories</a>
                            <a href="{{ route('admin.menu-items.index') }}" class="admin-page-tab {{ request()->routeIs('admin.menu-items.*') ? 'is-active' : '' }}">Menu Items</a>
                            <a href="{{ route('admin.dine-in-slots.index') }}" class="admin-page-tab {{ request()->routeIs('admin.dine-in-slots.*') ? 'is-active' : '' }}">Dine-In Slots</a>
                            <a href="{{ route('admin.bookings.index') }}" class="admin-page-tab {{ request()->routeIs('admin.bookings.*') ? 'is-active' : '' }}">Bookings</a>
                            <a href="{{ route('admin.orders.index') }}" class="admin-page-tab {{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}">Orders</a>
                            <a href="{{ route('admin.localization.edit') }}" class="admin-page-tab {{ request()->routeIs('admin.localization.*') ? 'is-active' : '' }}">Localization</a>
                        </nav>

                        @if (session('success'))
                            <div class="alert alert-success d-flex align-items-center p-5 mb-7 admin-alert">
                            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-success">Success</h4>
                                <span>{{ session('success') }}</span>
                            </div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger p-5 mb-7 admin-alert">
                                <h4 class="mb-2 text-danger">Please fix the following:</h4>
                                <ul class="mb-0 ps-4">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('admin_content')
                    </div>
                </div>
            </div>
        </div>
        @include('partials.admin.footer')
    </div>
@endsection
