@extends('layouts.master')

@section('meta_robots', 'noindex,follow')
@section('body_class', 'home-menu-theme account-dashboard-theme')

@section('hero')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="account-hero">
                    <div>
                        <p class="account-hero__kicker mb-2">{{ __('Customer Area') }}</p>
                        <h1 class="account-hero__title mb-2">@yield('account_heading', __('My Dashboard'))</h1>
                        <p class="account-hero__copy mb-0">@yield('account_intro', __('Manage your Kashmir Grill House account with the same website experience and styling.'))</p>
                    </div>
                    <div class="account-hero__meta">
                        <span>{{ $user->name }}</span>
                        <strong>{{ $user->email }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="container pb-5 account-shell">
        <div class="row g-4 align-items-start">
            <div class="col-12 col-xl-3">
                @include('account.partials.sidebar', ['user' => $user, 'activeAccountPage' => $activeAccountPage])
            </div>

            <div class="col-12 col-xl-9">
                @if ($errors->any())
                    <div class="alert alert-danger account-alert mb-4">
                        <strong>{{ __('Please fix the following before continuing:') }}</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('account_content')
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        body.home-menu-theme.account-dashboard-theme {
            background:
                radial-gradient(circle at 86% -12%, rgba(219, 29, 48, 0.22), transparent 42%),
                radial-gradient(circle at 12% 8%, rgba(255, 149, 44, 0.14), transparent 45%),
                linear-gradient(180deg, #050505 0%, #090909 34%, #0d0d0d 100%);
            color: #f2f2f2;
        }

        body.home-menu-theme.account-dashboard-theme main {
            background: transparent;
        }

        .account-hero {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 1rem;
            align-items: end;
            padding: clamp(1.4rem, 3vw, 2.4rem);
            border-radius: 1.4rem;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02)),
                rgba(10, 10, 10, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 22px 42px rgba(0, 0, 0, 0.28);
        }

        .account-hero__kicker {
            color: rgba(255, 255, 255, 0.62);
            text-transform: uppercase;
            letter-spacing: 0.14em;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .account-hero__title {
            color: #fff;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
        }

        .account-hero__copy {
            color: rgba(255, 255, 255, 0.78);
            max-width: 44rem;
        }

        .account-hero__meta {
            justify-self: end;
            width: min(100%, 18rem);
            border-radius: 1rem;
            background: linear-gradient(180deg, rgba(219, 29, 48, 0.16), rgba(255, 149, 44, 0.12));
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1rem 1.1rem;
        }

        .account-hero__meta span {
            display: block;
            color: rgba(255, 255, 255, 0.62);
            font-size: 0.74rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 0.35rem;
        }

        .account-hero__meta strong {
            color: #fff;
            word-break: break-word;
        }

        .account-shell {
            margin-top: 1.6rem;
        }

        .account-shell .row > * {
            min-width: 0;
        }

        .account-sidebar,
        .account-panel {
            border-radius: 1.2rem;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(22, 22, 22, 0.08);
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.08);
        }

        .account-sidebar {
            padding: 1.1rem;
        }

        .account-sidebar__user {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 0.85rem;
            align-items: center;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        }

        .account-sidebar__avatar {
            width: 3rem;
            height: 3rem;
            border-radius: 999px;
            display: inline-grid;
            place-items: center;
            color: #fff;
            font-weight: 700;
            background: linear-gradient(135deg, var(--brand-red, #db1d30), var(--brand-orange, #ff952c));
        }

        .account-sidebar__user strong {
            display: block;
            color: #161616;
        }

        .account-sidebar__user small {
            color: #6a6259;
            word-break: break-word;
        }

        .account-sidebar__nav {
            display: grid;
            gap: 0.45rem;
        }

        .account-sidebar__link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 0.85rem;
            padding: 0.72rem 0.9rem;
            color: #231c16;
            text-decoration: none;
            font-weight: 600;
            transition: background-color .2s ease, color .2s ease, transform .2s ease;
        }

        .account-sidebar__link:hover,
        .account-sidebar__link:focus,
        .account-sidebar__link.is-active {
            background: linear-gradient(90deg, rgba(219, 29, 48, 0.1), rgba(255, 149, 44, 0.12));
            color: #151515;
        }

        .account-panel {
            overflow: hidden;
            padding: 1.2rem;
        }

        .account-panel--danger {
            border-color: rgba(219, 29, 48, 0.18);
            background: linear-gradient(180deg, rgba(255, 245, 245, 0.98), rgba(255, 255, 255, 0.95));
        }

        .account-panel__head {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: start;
            margin-bottom: 1rem;
        }

        .account-panel__kicker {
            color: #8a4f00;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-size: 0.74rem;
            font-weight: 700;
        }

        .account-panel__title {
            color: #181818;
            font-size: clamp(1.35rem, 2vw, 1.8rem);
            font-weight: 700;
        }

        .account-stats-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .account-stat-card {
            border-radius: 1rem;
            padding: 1rem;
            background: linear-gradient(180deg, rgba(18, 18, 18, 0.96), rgba(33, 20, 8, 0.94));
            color: #fff;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.06);
        }

        .account-stat-card__label {
            display: block;
            color: rgba(255, 255, 255, 0.62);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.72rem;
            margin-bottom: 0.45rem;
        }

        .account-stat-card strong {
            display: block;
            font-size: clamp(1.3rem, 2.2vw, 1.8rem);
            margin-bottom: 0.35rem;
        }

        .account-stat-card small {
            color: rgba(255, 255, 255, 0.74);
        }

        .account-summary-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .account-summary-card {
            border-radius: 1rem;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(248, 242, 234, 0.92));
            padding: 1rem;
        }

        .account-summary-card__label {
            display: block;
            color: #8a4f00;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.72rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .account-summary-card strong,
        .account-summary-card a {
            overflow-wrap: anywhere;
        }

        .account-summary-card p {
            color: #6b625a;
            margin-bottom: 0.85rem;
        }

        .account-summary-card__action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 2.8rem;
            padding: 0.65rem 1rem;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            color: #181818;
            background: rgba(0, 0, 0, 0.05);
        }

        .account-history-card {
            border-radius: 1rem;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(250, 247, 242, 0.92));
            padding: 1rem;
        }

        .account-history-card + .account-history-card {
            margin-top: 0.85rem;
        }

        .account-history-card__head {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: start;
            margin-bottom: 0.9rem;
        }

        .account-history-card__head p {
            color: #6f665d;
        }

        .account-history-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.8rem;
        }

        .account-history-grid > div {
            min-width: 0;
        }

        .account-history-grid__label {
            display: block;
            color: #7a6d63;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            margin-bottom: 0.28rem;
        }

        .account-history-grid strong {
            color: #181818;
            font-size: 0.96rem;
            overflow-wrap: anywhere;
        }

        .account-history-actions {
            margin-top: 1rem;
            display: flex;
            justify-content: flex-end;
        }

        .account-history-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 2.7rem;
            padding: 0.65rem 1rem;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            color: #181818;
            background: rgba(0, 0, 0, 0.05);
        }

        .account-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .account-toolbar__back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 2.7rem;
            padding: 0.65rem 1rem;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            color: #f2f2f2;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .account-detail-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(18rem, 1fr);
            gap: 1rem;
        }

        .account-detail-stack {
            display: grid;
            gap: 1rem;
        }

        .account-detail-card {
            border-radius: 1rem;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(248, 242, 234, 0.92));
            padding: 1rem;
        }

        .account-detail-card__title {
            color: #181818;
            font-size: 1.08rem;
            font-weight: 700;
            margin-bottom: 0.9rem;
        }

        .account-detail-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.85rem;
        }

        .account-detail-meta > div,
        .account-detail-card__row {
            min-width: 0;
        }

        .account-detail-label {
            display: block;
            color: #7a6d63;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            margin-bottom: 0.28rem;
        }

        .account-detail-value {
            color: #181818;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .account-detail-copy {
            color: #6b625a;
            margin-bottom: 0;
            overflow-wrap: anywhere;
        }

        .account-line-items {
            display: grid;
            gap: 0.8rem;
        }

        .account-line-item {
            border-radius: 0.95rem;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background: rgba(255, 255, 255, 0.75);
            padding: 0.9rem 1rem;
        }

        .account-line-item__head {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: start;
            margin-bottom: 0.75rem;
        }

        .account-line-item__name {
            color: #181818;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .account-line-item__meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.7rem;
        }

        .account-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.4rem 0.8rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .account-badge.is-success {
            background: rgba(22, 163, 74, 0.12);
            color: #176437;
        }

        .account-badge.is-danger {
            background: rgba(219, 29, 48, 0.12);
            color: #9f1724;
        }

        .account-badge.is-warn {
            background: rgba(255, 149, 44, 0.14);
            color: #8a4f00;
        }

        .account-input {
            min-height: 3.1rem;
            border-radius: 0.9rem;
            border-color: rgba(0, 0, 0, 0.12);
            box-shadow: none;
        }

        .account-input:focus {
            border-color: rgba(255, 149, 44, 0.55);
            box-shadow: 0 0 0 0.2rem rgba(255, 149, 44, 0.15);
        }

        .account-pager {
            margin-top: 1rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .account-pager__item {
            min-width: 7rem;
            text-align: center;
            padding: 0.7rem 1rem;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
            color: #181818;
            background: rgba(0, 0, 0, 0.05);
        }

        .account-pager__item.is-disabled {
            opacity: 0.45;
        }

        .account-pager__meta {
            color: #6c6258;
            font-weight: 600;
        }

        .account-alert {
            border-radius: 1rem;
        }

        .account-empty,
        .account-danger-copy {
            color: #6b625a;
        }

        .account-quicklinks {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .9rem;
        }

        .account-quicklink-card {
            display: block;
            border-radius: 1rem;
            padding: 1rem;
            text-decoration: none;
            color: #151515;
            border: 1px solid rgba(0, 0, 0, .08);
            background: linear-gradient(180deg, rgba(255,255,255,.96), rgba(249,244,237,.92));
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .account-quicklink-card:hover,
        .account-quicklink-card:focus {
            transform: translateY(-2px);
            box-shadow: 0 16px 28px rgba(0, 0, 0, 0.08);
            border-color: rgba(255, 149, 44, .22);
            color: #151515;
        }

        .account-quicklink-card span {
            display: block;
            color: #8a4f00;
            text-transform: uppercase;
            letter-spacing: .1em;
            font-size: .72rem;
            margin-bottom: .35rem;
            font-weight: 700;
        }

        .account-quicklink-card strong {
            display: block;
            font-size: 1.1rem;
            margin-bottom: .35rem;
        }

        .account-quicklink-card small {
            color: #6b625a;
        }

        @media (max-width: 1199.98px) {
            .account-hero {
                grid-template-columns: 1fr;
            }

            .account-hero__meta {
                justify-self: start;
            }
        }

        @media (max-width: 991.98px) {
            .account-stats-grid,
            .account-summary-grid,
            .account-detail-grid,
            .account-detail-meta,
            .account-line-item__meta,
            .account-history-grid,
            .account-quicklinks {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .account-detail-grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        @media (max-width: 767.98px) {
            .account-stats-grid,
            .account-summary-grid,
            .account-detail-meta,
            .account-line-item__meta,
            .account-history-grid,
            .account-quicklinks {
                grid-template-columns: minmax(0, 1fr);
            }

            .account-history-card__head,
            .account-line-item__head,
            .account-pager,
            .account-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .account-history-actions {
                justify-content: stretch;
            }

            .account-history-action,
            .account-toolbar__back {
                width: 100%;
            }

            .account-pager__item {
                width: 100%;
            }
        }
    </style>
@endpush
