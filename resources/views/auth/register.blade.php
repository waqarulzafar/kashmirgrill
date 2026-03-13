@extends('layouts.master')

@section('title', 'Register | Kashmir Grill House')
@section('body_class', 'auth-page-theme')

@section('content')
    <section class="auth-shell">
        <div class="container py-4 py-md-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="auth-panel">
                        <div class="auth-panel__header">
                            <span class="badge rounded-pill badge-brand auth-panel__badge">{{ __('New Account') }}</span>
                            <h1 class="auth-panel__title">{{ __('Create Your Profile') }}</h1>
                            <p class="auth-panel__subtitle">{{ __('Join Kashmir Grill House to speed up your bookings and orders.') }}</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="auth-form">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label auth-label">{{ __('Name') }}</label>
                                <input id="name" type="text" class="form-control auth-input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label auth-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label auth-label">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password-confirm" class="form-label auth-label">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control auth-input" name="password_confirmation" required autocomplete="new-password">
                            </div>

                            <button type="submit" class="btn btn-brand auth-submit-btn mb-3">
                                {{ __('Register') }}
                            </button>

                            @if (Route::has('login'))
                                <p class="auth-note mb-0 text-center">
                                    {{ __('Already registered?') }}
                                    <a href="{{ route('login') }}">{{ __('Sign in') }}</a>
                                </p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
