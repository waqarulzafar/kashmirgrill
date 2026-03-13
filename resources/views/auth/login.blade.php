@extends('layouts.master')

@section('title', 'Login | Kashmir Grill House')
@section('body_class', 'auth-page-theme')

@section('content')
    <section class="auth-shell">
        <div class="container py-4 py-md-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="auth-panel">
                        <div class="auth-panel__header">
                            <span class="badge rounded-pill badge-brand auth-panel__badge">{{ __('Member Login') }}</span>
                            <h1 class="auth-panel__title">{{ __('Welcome Back') }}</h1>
                            <p class="auth-panel__subtitle">{{ __('Sign in to manage bookings and orders.') }}</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}" class="auth-form">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label auth-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label auth-label">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label auth-note" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a class="auth-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-brand auth-submit-btn mb-3">
                                {{ __('Login') }}
                            </button>

                            @if (Route::has('register'))
                                <p class="auth-note mb-0 text-center">
                                    {{ __("Don't have an account?") }}
                                    <a href="{{ route('register') }}">{{ __('Create one') }}</a>
                                </p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
