@extends('layouts.master')

@section('title', 'Confirm Password | Kashmir Grill House')
@section('body_class', 'auth-page-theme')

@section('content')
    <section class="auth-shell">
        <div class="container py-4 py-md-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="auth-panel">
                        <div class="auth-panel__header">
                            <span class="badge rounded-pill badge-brand auth-panel__badge">{{ __('Secure Check') }}</span>
                            <h1 class="auth-panel__title">{{ __('Confirm Password') }}</h1>
                            <p class="auth-panel__subtitle">{{ __('Please confirm your password before continuing.') }}</p>
                        </div>

                        <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
                            @csrf

                            <div class="mb-4">
                                <label for="password" class="form-label auth-label">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-brand auth-submit-btn mb-3">
                                {{ __('Confirm Password') }}
                            </button>

                            @if (Route::has('password.request'))
                                <p class="auth-note mb-0 text-center">
                                    <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                                </p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
