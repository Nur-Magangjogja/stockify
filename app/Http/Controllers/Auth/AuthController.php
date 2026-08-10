<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLog\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected ActivityLogService $activityLogService) {}

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route($this->getDashboardRoute(Auth::user()->role));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email harus diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            $request->session()->regenerate();

            $this->activityLogService->log('login', 'User', $user->id, "{$user->name} login ke sistem");

            return redirect()->intended(route($this->getDashboardRoute($user->role)));
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak valid.',
        ])->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $this->activityLogService->log('logout', 'User', Auth::id(), Auth::user()->name . ' logout dari sistem');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function getDashboardRoute(string $role): string
    {
        return match ($role) {
            'Admin'          => 'admin.dashboard',
            'Manajer Gudang' => 'manager.dashboard',
            'Staff Gudang'   => 'staff.dashboard',
            default             => 'login',
        };
    }
}
