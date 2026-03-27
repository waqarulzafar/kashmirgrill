<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateLocalizationSettingsRequest;
use App\Support\LocalizationManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocalizationSettingsController extends Controller
{
    public function edit(LocalizationManager $localizationManager): View
    {
        return view('admin.localization.edit', [
            'defaultLocale' => $localizationManager->defaultLocale(),
            'supportedLocales' => $localizationManager->supportedLocales(),
        ]);
    }

    public function update(
        UpdateLocalizationSettingsRequest $request,
        LocalizationManager $localizationManager,
    ): RedirectResponse {
        $validated = $request->validated();

        $localizationManager->setDefaultLocale((string) $validated['default_locale']);

        return redirect()
            ->route('admin.localization.edit')
            ->with('success', 'Default locale updated successfully.');
    }
}
