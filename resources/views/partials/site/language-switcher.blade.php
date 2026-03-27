@php
    $variant = $variant ?? 'header';
    $availableLocales = collect($siteLocales ?? []);
    $currentLocale = $availableLocales->firstWhere('is_current', true) ?? $availableLocales->first();
@endphp

@if($currentLocale)
    <div class="dropdown">
        <a
            href="#"
            class="{{ $variant === 'footer' ? 'language-switcher__trigger language-switcher__trigger--footer dropdown-toggle' : 'nav-link dropdown-toggle language-switcher__trigger' }}"
            data-bs-toggle="dropdown"
            aria-expanded="false"
        >
            <span class="language-switcher__flag">{{ $currentLocale['flag'] }}</span>
            <span class="{{ $variant === 'footer' ? 'language-switcher__copy' : 'language-switcher__label' }}">
                <strong>{{ $currentLocale['native'] }}</strong>
                <small>{{ strtoupper($currentLocale['code']) }}</small>
            </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end language-switcher__menu">
            @foreach($availableLocales as $locale)
                <li>
                    <a
                        href="{{ $locale['url'] }}"
                        class="dropdown-item language-switcher__item {{ $locale['is_current'] ? 'is-current' : '' }}"
                        @if($locale['is_current']) aria-current="true" @endif
                    >
                        <span class="language-switcher__flag">{{ $locale['flag'] }}</span>
                        <span class="language-switcher__copy">
                            <strong>{{ $locale['native'] }}</strong>
                            <small>{{ strtoupper($locale['code']) }}</small>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
