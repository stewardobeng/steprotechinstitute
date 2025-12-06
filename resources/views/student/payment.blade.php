<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-background-light dark:bg-background-dark py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                    Complete Your Registration
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Please complete payment of ₵{{ number_format($registration->registration_fee, 2) }} to finalize your registration.
                </p>
            </div>

            <div class="bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] p-6">
                <div class="text-center">
                    <div class="mb-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 mb-4">
                            <span class="material-symbols-outlined text-primary text-3xl">payment</span>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Payment Required</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Click the button below to open the payment popup.
                    </p>
                    <button 
                        id="payButton"
                        onclick="initializePayment()" 
                        class="w-full flex items-center justify-center px-6 py-3 bg-primary text-white rounded-lg font-semibold hover:bg-primary/90 transition-colors"
                    >
                        <span>Pay ₵{{ number_format($registration->registration_fee, 2) }}</span>
                    </button>
                    <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                        If the payment popup doesn't open, click the button above.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- PayStack Script -->
    <script src="https://js.paystack.co/v1/inline.js"></script>
    
    <script>
        // Auto-trigger payment popup on page load
        window.addEventListener('DOMContentLoaded', function() {
            // Small delay to ensure Paystack script is loaded
            setTimeout(function() {
                initializePayment();
            }, 500);
        });

        function initializePayment() {
            const button = document.getElementById('payButton');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<span>Opening payment...</span>';
            }

            // Initialize PayStack payment - opens directly in popup
            const handler = PaystackPop.setup({
                key: '{{ $publicKey }}',
                email: '{{ $email }}',
                amount: {{ $amount }},
                ref: '{{ $paymentReference }}',
                currency: 'GHS',
                onClose: function() {
                    // User closed the payment popup
                    if (button) {
                        button.disabled = false;
                        button.innerHTML = '<span>Pay ₵{{ number_format($registration->registration_fee, 2) }}</span>';
                    }
                    console.log('Payment popup closed');
                },
                callback: function(response) {
                    // Payment successful - verify and redirect
                    verifyPayment(response.reference);
                }
            });

            handler.openIframe();
        }

        async function verifyPayment(reference) {
            const button = document.getElementById('payButton');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<span>Verifying payment...</span>';
            }

            try {
                const response = await fetch(`{{ route('payment.callback') }}?reference=${reference}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Redirect to dashboard
                    window.location.href = '{{ route("student.dashboard") }}';
                } else {
                    alert(data.message || 'Payment verification failed. Please contact support.');
                    if (button) {
                        button.disabled = false;
                        button.innerHTML = '<span>Pay ₵{{ number_format($registration->registration_fee, 2) }}</span>';
                    }
                }
            } catch (error) {
                console.error('Verification error:', error);
                alert('Payment verification failed. Please contact support.');
                if (button) {
                    button.disabled = false;
                    button.innerHTML = '<span>Pay ₵{{ number_format($registration->registration_fee, 2) }}</span>';
                }
            }
        }
    </script>
</x-guest-layout>

