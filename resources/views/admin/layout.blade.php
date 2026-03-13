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
                    <div class="card mb-7">
                        <div class="card-body py-4">
                            <div class="d-flex flex-wrap gap-3">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm {{ request()->routeIs('admin.dashboard') ? 'btn-primary' : 'btn-light-primary' }}">Dashboard</a>
                                <a href="{{ route('admin.menu-categories.index') }}" class="btn btn-sm {{ request()->routeIs('admin.menu-categories.*') ? 'btn-primary' : 'btn-light-primary' }}">Menu Categories</a>
                                <a href="{{ route('admin.menu-items.index') }}" class="btn btn-sm {{ request()->routeIs('admin.menu-items.*') ? 'btn-primary' : 'btn-light-primary' }}">Menu Items</a>
                                <a href="{{ route('admin.dine-in-slots.index') }}" class="btn btn-sm {{ request()->routeIs('admin.dine-in-slots.*') ? 'btn-primary' : 'btn-light-primary' }}">Dine-In Slots</a>
                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success d-flex align-items-center p-5 mb-7">
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
                        <div class="alert alert-danger p-5 mb-7">
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
        @include('partials.admin.footer')
    </div>
@endsection
