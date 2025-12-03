<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the affiliate agent registration view.
     */
    public function createAffiliate(): View
    {
        return view('auth.affiliate-register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'in:admin,affiliate_agent,student'],
            'invite_code' => ['required_if:role,affiliate_agent', 'string'],
        ]);

        $role = $request->role ?? 'student';

        // Validate invite code for affiliate agents
        if ($role === 'affiliate_agent') {
            $inviteCode = \App\Models\InviteCode::where('code', $request->invite_code)
                ->where('type', 'admin_generated')
                ->first();

            if (!$inviteCode || !$inviteCode->canBeUsed()) {
                return back()->withErrors([
                    'invite_code' => 'Invalid or expired invite code.',
                ])->withInput();
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'status' => 'active',
        ]);

        // Create affiliate agent profile if role is affiliate_agent
        if ($role === 'affiliate_agent') {
            $referralLink = 'REF-' . strtoupper(uniqid());
            
            \App\Models\AffiliateAgent::create([
                'user_id' => $user->id,
                'referral_link' => $referralLink,
                'registration_approved' => false,
            ]);

            // Increment invite code usage
            if (isset($inviteCode)) {
                $inviteCode->increment('current_uses');
            }
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on role
        if ($role === 'affiliate_agent') {
            return redirect()->route('affiliate.pending');
        }

        return redirect(route('dashboard', absolute: false));
    }
}
