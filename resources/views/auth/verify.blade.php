@extends('layouts.master')

@section('title', 'Verify Email | Kashmir Grill House')
@section('body_class', 'auth-page-theme')

@section('content')
    <section class="auth-shell">
        <div class="container py-4 py-md-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="auth-panel">
                        <div class="auth-panel__header">
                            <span class="badge rounded-pill badge-brand auth-panel__badge">{{ __('Email Verification') }}</span>
                            <h1 class="auth-panel__title">{{ __('Verify Your Email') }}</h1>
                            <p class="auth-panel__subtitle">{{ __('Before proceeding, please check your email for a verification link.') }}</p>
                        </div>

                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        <p class="auth-note mb-4">
                            {{ __('If you did not receive the email, request another verification email below.') }}
                        </p>

                        <form method="POST" action="{{ route('verification.resend') }}" class="auth-form">
                            @csrf
                            <button type="submit" class="btn btn-brand auth-submit-btn">
                                {{ __('Resend Verification Email') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
