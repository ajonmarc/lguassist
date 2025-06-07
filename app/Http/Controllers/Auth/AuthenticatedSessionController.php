<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
  public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    $role = Auth::user()?->role;

    if ($role === UserRole::Admin) {
        return redirect()->route("admin.dashboard")->with("success", "Welcome back, Admin!");
    } elseif ($role === UserRole::Citizen) {
        return redirect()->route("dashboard")->with("success", "Welcome back, Citizen!");
    } elseif ($role === UserRole::LGU) {
        return redirect()->route("lgu.dashboard")->with("success", "Welcome back, Local Government Unit!");
    } else {
        return redirect()->route("dashboard")->with("success", "Welcome back!");
    }
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
