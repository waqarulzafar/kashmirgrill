<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        $intendedUrl = $request->session()->get('url.intended');

        if ($user->role !== 'admin' && is_string($intendedUrl)) {
            $adminPath = parse_url(route('admin.dashboard'), PHP_URL_PATH);
            $intendedPath = parse_url($intendedUrl, PHP_URL_PATH);

            if (is_string($adminPath) && is_string($intendedPath) && str_starts_with($intendedPath, $adminPath)) {
                $request->session()->forget('url.intended');

                return redirect()->route('account.dashboard');
            }
        }

        return null;
    }

    protected function redirectTo(): string
    {
        return auth()->user()?->role === 'admin'
            ? route('admin.dashboard')
            : route('account.dashboard');
    }
}
