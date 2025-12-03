<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Setting;
use App\Models\StudentRegistration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private string $secretKey;
    private string $publicKey;
    private string $baseUrl = 'https://api.paystack.co';

    public function __construct()
    {
        // Try to get from database settings first, then fall back to config/env
        $secretKey = Setting::getValue('paystack_secret_key', config('paystack.secret_key'));
        $publicKey = Setting::getValue('paystack_public_key', config('paystack.public_key'));

        if (empty($secretKey)) {
            throw new \RuntimeException('PayStack secret key is not configured. Please configure it in Admin Settings or set PAYSTACK_SECRET_KEY in your .env file.');
        }

        if (empty($publicKey)) {
            throw new \RuntimeException('PayStack public key is not configured. Please configure it in Admin Settings or set PAYSTACK_PUBLIC_KEY in your .env file.');
        }

        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
    }

    /**
     * Initialize payment with PayStack
     */
    public function initializePayment(StudentRegistration $registration, array $customerData): array
    {
        $amount = (int)($registration->registration_fee * 100); // Convert to kobo (PayStack uses kobo)
        
        // Ensure amount is at least 1 kobo
        if ($amount < 1) {
            Log::error('Invalid payment amount', [
                'amount' => $amount,
                'registration_fee' => $registration->registration_fee,
            ]);
            return [
                'success' => false,
                'message' => 'Invalid payment amount',
            ];
        }

        $payload = [
            'email' => $customerData['email'],
            'amount' => $amount,
            'reference' => $this->generateReference(),
            'currency' => 'GHS',
            'callback_url' => route('payment.callback'),
            'metadata' => [
                'student_registration_id' => $registration->id,
                'student_id' => $registration->student_id,
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/transaction/initialize", $payload);

        if ($response->successful()) {
            $responseData = $response->json();
            
            if (isset($responseData['data'])) {
                $data = $responseData['data'];
                
                // Update registration with payment reference
                $registration->update([
                    'payment_reference' => $data['reference'],
                ]);

                return [
                    'success' => true,
                    'authorization_url' => $data['authorization_url'],
                    'reference' => $data['reference'],
                ];
            }
        }

        $errorResponse = $response->json();
        Log::error('PayStack initialization failed', [
            'status' => $response->status(),
            'response' => $errorResponse,
            'payload' => $payload,
            'registration_id' => $registration->id,
        ]);

        $errorMessage = $errorResponse['message'] ?? 'Failed to initialize payment';
        if (isset($errorResponse['data']['message'])) {
            $errorMessage = $errorResponse['data']['message'];
        }

        return [
            'success' => false,
            'message' => $errorMessage,
        ];
    }

    /**
     * Verify payment with PayStack
     */
    public function verifyPayment(string $reference): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type' => 'application/json',
        ])->get("{$this->baseUrl}/transaction/verify/{$reference}");

        if ($response->successful()) {
            $data = $response->json()['data'];
            
            return [
                'success' => true,
                'status' => $data['status'] === 'success',
                'data' => $data,
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to verify payment',
        ];
    }

    /**
     * Handle PayStack webhook
     */
    public function handleWebhook(array $payload): bool
    {
        $event = $payload['event'] ?? null;
        $data = $payload['data'] ?? [];

        if ($event === 'charge.success') {
            $reference = $data['reference'] ?? null;
            
            if ($reference) {
                return $this->processSuccessfulPayment($reference, $data);
            }
        }

        return false;
    }

    /**
     * Process successful payment
     */
    public function processSuccessfulPayment(string $reference, array $data): bool
    {
        $registration = StudentRegistration::where('payment_reference', $reference)->first();

        if (!$registration) {
            Log::warning('Registration not found for payment reference', ['reference' => $reference]);
            return false;
        }

        if ($registration->payment_status === 'paid') {
            Log::info('Payment already processed', ['reference' => $reference]);
            return true;
        }

        try {
            \DB::transaction(function () use ($registration, $data, $reference) {
                // Update registration
                $registration->update([
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                ]);

                // Create payment record
                Payment::create([
                    'student_registration_id' => $registration->id,
                    'amount' => isset($data['amount']) ? ($data['amount'] / 100) : $registration->registration_fee, // Convert from kobo
                    'paystack_reference' => $reference,
                    'paystack_transaction_id' => $data['id'] ?? null,
                    'status' => 'success',
                    'payment_method' => 'paystack',
                    'paid_at' => now(),
                ]);

                // Process commission if affiliate agent exists
                if ($registration->affiliate_agent_id) {
                    try {
                        $commissionService = app(\App\Services\CommissionService::class);
                        $commissionService->processCommission($registration);
                    } catch (\Exception $e) {
                        Log::warning('Failed to process commission', [
                            'registration_id' => $registration->id,
                            'error' => $e->getMessage(),
                        ]);
                        // Don't fail the payment if commission processing fails
                    }
                }
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to process payment', [
                'reference' => $reference,
                'registration_id' => $registration->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }

        return true;
    }

    /**
     * Generate unique payment reference
     */
    private function generateReference(): string
    {
        return 'STU-' . strtoupper(uniqid()) . '-' . time();
    }

    /**
     * Get public key for frontend
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}

