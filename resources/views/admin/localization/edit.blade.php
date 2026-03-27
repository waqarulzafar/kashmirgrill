@extends('admin.layout')

@section('admin_title', 'Localization Settings')
@section('admin_description', 'Choose the default frontend locale used for the public site, translated route defaults, and SEO alternate language handling.')

@section('admin_actions')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Back to Dashboard</a>
@endsection

@section('admin_content')
    <div class="card admin-panel">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">Default Frontend Locale</h3>
                <p class="admin-panel-copy">This controls which language visitors land on first. Users can still switch languages from the frontend at any time.</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <form method="POST" action="{{ route('admin.localization.update') }}" class="row g-5">
                @csrf
                @method('PUT')

                <div class="col-12 col-lg-7">
                    <label for="default_locale" class="form-label fw-semibold">Default Locale</label>
                    <select id="default_locale" name="default_locale" class="form-select form-select-solid" required>
                        @foreach ($supportedLocales as $code => $locale)
                            <option value="{{ $code }}" @selected(old('default_locale', $defaultLocale) === $code)>
                                {{ $locale['flag'] }} {{ $locale['name'] }} ({{ strtoupper($code) }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text text-muted">Supported languages: English, Italian, French, and German.</div>
                </div>

                <div class="col-12">
                    <div class="separator my-2"></div>
                    <div class="d-flex flex-wrap gap-3">
                        <button type="submit" class="btn btn-primary">Save Locale Settings</button>
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-light">Back to Profile</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
