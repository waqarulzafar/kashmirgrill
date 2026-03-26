@extends('admin.layout')

@section('admin_title', 'My Profile')
@section('admin_description', 'Maintain the admin identity and credentials used to access the restaurant control panel.')

@section('admin_actions')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Back to Dashboard</a>
@endsection

@section('admin_content')
    <div class="card admin-panel">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">Update Profile</h3>
                <p class="admin-panel-copy">Change your admin details and rotate your password when you need to refresh credentials securely.</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="row g-5">
                @csrf
                @method('PUT')

                <div class="col-12 col-md-6">
                    <label for="name" class="form-label fw-semibold">Full Name</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        class="form-control form-control-solid"
                        value="{{ old('name', $admin->name) }}"
                        required
                    >
                </div>

                <div class="col-12 col-md-6">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-control form-control-solid"
                        value="{{ old('email', $admin->email) }}"
                        required
                    >
                </div>

                <div class="col-12">
                    <div class="separator my-2"></div>
                    <p class="text-gray-600 fs-7 mb-0">Fill password fields only if you want to change your password.</p>
                </div>

                <div class="col-12 col-md-4">
                    <label for="current_password" class="form-label fw-semibold">Current Password</label>
                    <input
                        id="current_password"
                        type="password"
                        name="current_password"
                        class="form-control form-control-solid"
                        autocomplete="current-password"
                    >
                </div>

                <div class="col-12 col-md-4">
                    <label for="password" class="form-label fw-semibold">New Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control form-control-solid"
                        autocomplete="new-password"
                    >
                </div>

                <div class="col-12 col-md-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="form-control form-control-solid"
                        autocomplete="new-password"
                    >
                </div>

                <div class="col-12 d-flex gap-3">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </div>
@endsection
