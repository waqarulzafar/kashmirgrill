@extends('account.layout')

@section('title', 'Profile & Security | Kashmir Grill House')
@section('account_heading', 'Profile & Security')
@section('account_intro', 'Update your account details from a dedicated settings page without mixing them into your history screens.')

@section('account_content')
    <section class="account-panel mb-4">
        <div class="account-panel__head">
            <div>
                <p class="account-panel__kicker mb-1">Profile</p>
                <h2 class="account-panel__title mb-0">Update Details</h2>
            </div>
        </div>

        <form method="POST" action="{{ route('account.profile.update') }}" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-12 col-md-6">
                <label for="account_name" class="form-label">Name</label>
                <input id="account_name" name="name" type="text" class="form-control account-input @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="account_email" class="form-label">Email</label>
                <input id="account_email" name="email" type="email" class="form-control account-input @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-brand">Save Profile</button>
            </div>
        </form>
    </section>

    <section class="account-panel mb-4">
        <div class="account-panel__head">
            <div>
                <p class="account-panel__kicker mb-1">Security</p>
                <h2 class="account-panel__title mb-0">Change Password</h2>
            </div>
        </div>

        <form method="POST" action="{{ route('account.password.update') }}" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-12 col-md-4">
                <label for="current_password" class="form-label">Current Password</label>
                <input id="current_password" name="current_password" type="password" class="form-control account-input @error('current_password') is-invalid @enderror" required>
            </div>
            <div class="col-12 col-md-4">
                <label for="password" class="form-label">New Password</label>
                <input id="password" name="password" type="password" class="form-control account-input @error('password') is-invalid @enderror" required>
            </div>
            <div class="col-12 col-md-4">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control account-input" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-brand">Update Password</button>
            </div>
        </form>
    </section>

    <section class="account-panel account-panel--danger">
        <div class="account-panel__head">
            <div>
                <p class="account-panel__kicker mb-1">Danger Zone</p>
                <h2 class="account-panel__title mb-0">Delete Account</h2>
            </div>
        </div>

        <p class="account-danger-copy">Deleting your account removes your login access. Historical operational records remain in the system, but this customer profile will no longer be active.</p>

        <form method="POST" action="{{ route('account.destroy') }}" class="row g-3" onsubmit="return confirm('Delete your account permanently?');">
            @csrf
            @method('DELETE')
            <div class="col-12 col-md-6">
                <label for="delete_current_password" class="form-label">Confirm Current Password</label>
                <input id="delete_current_password" name="current_password" type="password" class="form-control account-input @error('current_password') is-invalid @enderror" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-danger">Delete Account</button>
            </div>
        </form>
    </section>
@endsection
