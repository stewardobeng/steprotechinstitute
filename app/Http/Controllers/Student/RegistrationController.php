<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AffiliateAgent;
use App\Models\InviteCode;
use App\Models\StudentRegistration;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function show(Request $request)
    {
        $ref = $request->query('ref');
        $inviteCode = $request->query('code');

        // If referral link is in URL but NOT in session, redirect to homepage first for better UX
        // This handles direct access to /register?ref=XXX
        // But if session exists, user is coming from homepage, so don't redirect
        if (($ref || $inviteCode) && !$request->session()->has('referral_ref') && !$request->session()->has('referral_code')) {
            $params = [];
            if ($ref) $params['ref'] = $ref;
            if ($inviteCode) $params['code'] = $inviteCode;
            return redirect()->route('home', $params);
        }

        // Check session for referral parameters (set from homepage)
        // Prefer query params if they exist (in case they're passed), otherwise use session
        if (!$ref) {
            $ref = $request->session()->get('referral_ref');
        }
        if (!$inviteCode) {
            $inviteCode = $request->session()->get('referral_code');
        }

        $affiliateAgent = null;
        $code = null;

        if ($ref) {
            $affiliateAgent = AffiliateAgent::where('referral_link', $ref)->first();
        }

        if ($inviteCode) {
            $code = InviteCode::where('code', $inviteCode)->first();
        }

        return view('student.register', compact('affiliateAgent', 'code', 'ref', 'inviteCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'affiliate_link' => 'nullable|string',
            'invite_code' => 'nullable|string|required_without:affiliate_link',
        ], [
            'invite_code.required_without' => 'Please provide either an affiliate link or an invite code to register.',
        ]);

        // Check if affiliate link or invite code is provided
        $affiliateAgentId = null;
        $inviteCodeUsed = null;

        if (isset($validated['affiliate_link']) && !empty($validated['affiliate_link'])) {
            $agent = AffiliateAgent::where('referral_link', $validated['affiliate_link'])->first();
            if ($agent) {
                $affiliateAgentId = $agent->id;
            }
        }

        if (isset($validated['invite_code']) && !empty($validated['invite_code'])) {
            $inviteCode = InviteCode::where('code', $validated['invite_code'])->first();
            
            if (!$inviteCode || !$inviteCode->canBeUsed()) {
                return back()->withErrors([
                    'invite_code' => 'Invalid or expired invite code.',
                ])->withInput();
            }

            $inviteCodeUsed = $inviteCode->code;
            $inviteCode->increment('current_uses');

            // If no affiliate agent from link, try to get from invite code generator
            if (!$affiliateAgentId && $inviteCode->generator && $inviteCode->generator->isAffiliateAgent()) {
                $affiliateAgentId = $inviteCode->generator->affiliateAgent->id ?? null;
            }
        }

        // Require either affiliate link or invite code
        if (!$affiliateAgentId && !$inviteCodeUsed) {
            return back()->withErrors([
                'affiliate_link' => 'Please provide either an affiliate link or an invite code.',
            ])->withInput();
        }

        try {
            $registration = \DB::transaction(function () use ($validated, $affiliateAgentId, $inviteCodeUsed) {
                // Create user
                $user = \App\Models\User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'password' => \Hash::make($validated['password']),
                    'role' => 'student',
                    'status' => 'active',
                ]);

                // Generate student ID
                $studentId = 'STU-' . strtoupper(Str::random(8));

                // Create student registration
                $registration = StudentRegistration::create([
                    'user_id' => $user->id,
                    'student_id' => $studentId,
                    'affiliate_agent_id' => $affiliateAgentId,
                    'invite_code_used' => $inviteCodeUsed,
                    'registration_fee' => 150.00,
                    'payment_status' => 'pending',
                ]);

                return $registration;
            });

            // Get the user from the registration
            $user = $registration->user;

            // Send notification when student is registered (outside transaction)
            try {
                $affiliateAgent = $affiliateAgentId ? \App\Models\AffiliateAgent::find($affiliateAgentId) : null;
                $notificationService = app(\App\Services\NotificationService::class);
                $notificationService->notifyStudentAdded($user, $affiliateAgent);
            } catch (\Exception $e) {
                \Log::warning('Failed to send student registration notification', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Log the user in automatically after registration
            auth()->login($user);

            // Redirect to payment page that will show popup
            return redirect()->route('register.payment', ['registration' => $registration->id])
                ->with('success', 'Registration successful! Please complete your payment.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Registration failed. Please try again.',
            ])->withInput();
        }
    }

    public function payment(StudentRegistration $registration)
    {
        // Ensure the registration belongs to the authenticated user
        if ($registration->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this registration.');
        }

        // If already paid, redirect to dashboard
        if ($registration->payment_status === 'paid') {
            return redirect()->route('student.dashboard')
                ->with('success', 'Payment already completed!');
        }

        // Initialize payment
        $paymentService = app(PaymentService::class);
        $paymentResult = $paymentService->initializePayment($registration, [
            'email' => $registration->user->email,
            'name' => $registration->user->name,
        ]);

        if (!$paymentResult['success']) {
            return redirect()->route('student.dashboard')
                ->with('error', $paymentResult['message'] ?? 'Failed to initialize payment. Please try again.');
        }

        return view('student.payment', [
            'registration' => $registration,
            'paymentReference' => $paymentResult['reference'],
            'publicKey' => $paymentService->getPublicKey(),
            'amount' => $registration->registration_fee * 100, // Amount in kobo
            'email' => $registration->user->email,
        ]);
    }
}
