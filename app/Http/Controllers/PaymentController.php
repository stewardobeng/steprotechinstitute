<?php

namespace App\Http\Controllers;

use App\Models\StudentRegistration;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment reference not found.'
                ], 400);
            }
            return redirect()->route('register')
                ->with('error', 'Payment reference not found.');
        }

        $paymentService = app(PaymentService::class);
        $verification = $paymentService->verifyPayment($reference);

        if (!$verification['success']) {
            Log::error('Payment verification failed', [
                'reference' => $reference,
                'verification' => $verification,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $verification['message'] ?? 'Payment verification failed. Please contact support.'
                ], 400);
            }

            return redirect()->route('register')
                ->with('error', 'Payment verification failed. Please contact support.');
        }

        // Check if payment was successful
        if (!$verification['status']) {
            Log::warning('Payment not successful', [
                'reference' => $reference,
                'verification' => $verification,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment was not successful. Please try again.'
                ], 400);
            }

            return redirect()->route('register')
                ->with('error', 'Payment was not successful. Please try again.');
        }

        // Payment verified successfully - now process it
        $registration = StudentRegistration::where('payment_reference', $reference)->first();

        if (!$registration) {
            Log::error('Registration not found for payment reference', ['reference' => $reference]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration not found for this payment.'
                ], 400);
            }

            return redirect()->route('register')
                ->with('error', 'Registration not found for this payment.');
        }

        // Process the payment if not already processed
        if ($registration->payment_status !== 'paid') {
            $paymentData = $verification['data'];
            $processed = $paymentService->processSuccessfulPayment($reference, $paymentData);

            if (!$processed) {
                Log::error('Failed to process payment', [
                    'reference' => $reference,
                    'registration_id' => $registration->id,
                ]);

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to process payment. Please contact support.'
                    ], 400);
                }

                return redirect()->route('register')
                    ->with('error', 'Failed to process payment. Please contact support.');
            }
        }

        // Payment processed successfully
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment successful! Welcome to your dashboard.'
            ]);
        }

        return redirect()->route('student.dashboard')
            ->with('success', 'Payment successful! Welcome to your dashboard.');
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();

        // Verify webhook signature (PayStack sends a signature header)
        $signature = $request->header('x-paystack-signature');
        
        if (!$signature) {
            Log::warning('PayStack webhook missing signature header');
            return response()->json(['status' => 'error', 'message' => 'Missing signature'], 400);
        }

        // Get raw request body for signature verification
        $rawBody = $request->getContent();
        $secretKey = config('paystack.secret_key');
        
        if (empty($secretKey)) {
            Log::error('PayStack secret key not configured for webhook verification');
            return response()->json(['status' => 'error', 'message' => 'Configuration error'], 500);
        }

        // Compute expected signature
        $expectedSignature = hash_hmac('sha512', $rawBody, $secretKey);
        
        // Use hash_equals for timing-safe comparison to prevent timing attacks
        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('Invalid PayStack webhook signature', [
                'received' => substr($signature, 0, 20) . '...',
                'expected' => substr($expectedSignature, 0, 20) . '...',
            ]);
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        }

        $paymentService = app(PaymentService::class);
        $processed = $paymentService->handleWebhook($payload);

        if ($processed) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 400);
    }
}
