<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAdminProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $admin = $request->user();

        abort_unless($admin && $admin->role === 'admin', 403);

        return view('admin.profile.edit', [
            'admin' => $admin,
        ]);
    }

    public function update(UpdateAdminProfileRequest $request): RedirectResponse
    {
        $admin = $request->user();

        abort_unless($admin && $admin->role === 'admin', 403);

        $validated = $request->validated();

        $admin->name = (string) $validated['name'];
        $admin->email = (string) $validated['email'];

        if (! empty($validated['password'])) {
            $admin->password = (string) $validated['password'];
        }

        $admin->save();

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}
