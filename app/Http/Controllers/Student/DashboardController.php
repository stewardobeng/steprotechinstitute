<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $registration = $user->studentRegistration;

        if (!$registration) {
            return redirect()->route('register');
        }

        $whatsappLink = $this->generateWhatsAppLink($registration);

        return view('student.dashboard', compact('registration', 'whatsappLink'));
    }

    public function payment()
    {
        $user = auth()->user();
        $registration = $user->studentRegistration;

        if (!$registration) {
            return redirect()->route('register');
        }

        if ($registration->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Your payment has already been completed.'
            ], 400);
        }

        // Initialize payment
        $paymentService = app(PaymentService::class);
        $paymentResult = $paymentService->initializePayment($registration, [
            'email' => $user->email,
            'name' => $user->name,
        ]);

        if ($paymentResult['success']) {
            return response()->json([
                'success' => true,
                'reference' => $paymentResult['reference'],
                'public_key' => $paymentService->getPublicKey(),
                'amount' => $registration->registration_fee * 100, // Amount in kobo
                'email' => $user->email,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to initialize payment. Please try again.'
        ], 400);
    }

    private function generateWhatsAppLink($registration): string
    {
        // Get phone number from settings, with fallback to env, then default
        $phone = \App\Models\Setting::getValue('whatsapp_number', env('WHATSAPP_NUMBER', '233244775129'));
        
        // Convert to string and remove any + sign, spaces, dashes, or other non-numeric characters
        $phone = trim((string) $phone);
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // CRITICAL: If phone is empty, just "233", or too short, use the full default number
        // This ensures we always have a valid full phone number
        // Ghana numbers must be exactly 12 digits: 233 (country code) + 9 digits
        if (empty($phone) || $phone === '233' || strlen($phone) < 12) {
            $phone = '233244775129';
        }
        
        // Final validation: ensure we have exactly 12 digits (Ghana standard)
        if (strlen($phone) !== 12 || substr($phone, 0, 3) !== '233') {
            $phone = '233244775129';
        }
        
        // Build message with payment date if available
        $message = "Hello, I am {$registration->user->name} with Student ID: {$registration->student_id}. I have completed my registration and payment for the next batch of the 5 day AI Literacy Professional Certification course.";
        
        if ($registration->payment_date) {
            $paymentDate = $registration->payment_date->format('F d, Y');
            $message .= " Payment date: {$paymentDate}.";
        }
        
        // Use rawurlencode for proper URL encoding (RFC 3986 compliant)
        // rawurlencode is preferred over urlencode for WhatsApp links
        // It properly encodes spaces as %20 and special characters
        $encodedMessage = rawurlencode($message);
        
        // WhatsApp URL format (best practice):
        // https://wa.me/PHONENUMBER?text=MESSAGE
        // 
        // Requirements:
        // - Phone number: digits only, no +, no spaces, no dashes
        // - Must be in international format (country code + number)
        // - For Ghana: 233 (country code) + 9 digits = 12 digits total
        // - Message: must be URL encoded using rawurlencode()
        // 
        // Example: https://wa.me/233244775129?text=Hello%20World
        return "https://wa.me/{$phone}?text={$encodedMessage}";
    }
}
