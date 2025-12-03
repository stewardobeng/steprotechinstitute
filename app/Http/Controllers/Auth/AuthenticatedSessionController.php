<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Display the 2FA verification view.
     */
    public function show2FA(): View
    {
        if (!session('login.id')) {
            return redirect()->route('login');
        }

        return view('auth.verify-2fa');
    }

    /**
     * Handle 2FA verification during login.
     */
    public function verify2FA(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $userId = $request->session()->get('login.id');
        
        if (!$userId) {
            return redirect()->route('login')
                ->withErrors(['code' => 'Session expired. Please login again.']);
        }

        $user = \App\Models\User::find($userId);
        
        if (!$user || !$user->two_factor_enabled) {
            $request->session()->forget(['login.id', 'login.remember']);
            return redirect()->route('login')
                ->withErrors(['code' => 'Invalid session. Please login again.']);
        }

        $twoFactorService = app(\App\Services\TwoFactorService::class);
        
        // Verify the 2FA code (use verifyCode which checks if enabled)
        if (!$twoFactorService->verifyCode($user, $request->code)) {
            return redirect()->route('login.2fa')
                ->withErrors(['code' => 'Invalid verification code. Please try again.']);
        }

        // Code is valid, complete authentication
        $remember = $request->session()->get('login.remember', false);
        Auth::login($user, $remember);
        
        $request->session()->forget(['login.id', 'login.remember']);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();
        
        // Check if user has 2FA enabled
        if ($user->two_factor_enabled) {
            $twoFactor = $user->twoFactorAuthentication;
            
            if ($twoFactor && $twoFactor->enabled) {
                // Store user ID in session for 2FA verification
                $request->session()->put('login.id', $user->id);
                $request->session()->put('login.remember', $request->boolean('remember'));
                
                // Don't fully authenticate yet - require 2FA code
                Auth::logout();
                
                return redirect()->route('login.2fa');
            }
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
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
