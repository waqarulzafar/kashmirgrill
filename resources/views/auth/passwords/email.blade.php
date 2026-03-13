@extends('layouts.master')

@section('title', 'Forgot Password | Kashmir Grill House')
@section('body_class', 'auth-page-theme')

@section('content')
    <section class="auth-shell">
        <div class="container py-4 py-md-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="auth-panel">
                        <div class="auth-panel__header">
                            <span class="badge rounded-pill badge-brand auth-panel__badge">{{ __('Account Recovery') }}</span>
                            <h1 class="auth-panel__title">{{ __('Forgot Your Password?') }}</h1>
                            <p class="auth-panel__subtitle">{{ __('Enter your email address and we will send your reset link.') }}</p>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label auth-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-brand auth-submit-btn mb-3">
                                {{ __('Send Password Reset Link') }}
                            </button>

                            @if (Route::has('login'))
                                <p class="auth-note mb-0 text-center">
                                    <a href="{{ route('login') }}">{{ __('Back to login') }}</a>
                                </p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
