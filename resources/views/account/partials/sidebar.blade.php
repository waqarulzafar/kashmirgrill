<aside class="account-sidebar">
    <div class="account-sidebar__user">
        <span class="account-sidebar__avatar">{{ strtoupper(substr((string) $user->name, 0, 1)) }}</span>
        <div>
            <strong>{{ $user->name }}</strong>
            <small>{{ $user->email }}</small>
        </div>
    </div>

    <nav class="account-sidebar__nav" aria-label="Account sections">
        <a href="{{ route('account.dashboard') }}" class="account-sidebar__link {{ $activeAccountPage === 'dashboard' ? 'is-active' : '' }}">Overview</a>
        <a href="{{ route('account.orders') }}" class="account-sidebar__link {{ $activeAccountPage === 'orders' ? 'is-active' : '' }}">Order History</a>
        <a href="{{ route('account.bookings') }}" class="account-sidebar__link {{ $activeAccountPage === 'bookings' ? 'is-active' : '' }}">Booking History</a>
        <a href="{{ route('account.profile') }}" class="account-sidebar__link {{ $activeAccountPage === 'profile' ? 'is-active' : '' }}">Profile & Security</a>
    </nav>
</aside>
