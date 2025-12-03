<x-app-layout>
    <x-slot name="title">Student Dashboard</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center">
        <h1 class="text-gray-900 dark:text-white text-2xl sm:text-3xl lg:text-4xl font-black leading-tight tracking-[-0.033em]">Student Dashboard</h1>
    </div>

    <div class="mt-8 grid gap-6">
        <!-- Student Information Card -->
        <div class="flex flex-1 flex-col items-start justify-between gap-4 rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] p-4 sm:p-5">
            <div class="flex flex-col gap-4 w-full">
                <div>
                    <p class="text-gray-500 dark:text-[#92adc9] text-xs sm:text-sm font-normal leading-normal">Student ID</p>
                    @if($registration->payment_status === 'paid')
                        <p class="text-gray-900 dark:text-white text-xl sm:text-2xl font-bold leading-tight mt-1 font-mono break-all">{{ $registration->student_id }}</p>
                    @else
                        <p class="text-gray-900 dark:text-white text-xl sm:text-2xl font-bold leading-tight mt-1 font-mono blur-sm select-none break-all" title="Complete payment to view your Student ID">{{ $registration->student_id }}</p>
                        <p class="text-gray-500 dark:text-[#92adc9] text-xs font-normal leading-normal mt-1">Complete payment to view your Student ID</p>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Name</p>
                        <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $registration->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Email</p>
                        <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $registration->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Payment Status</p>
                        <span class="inline-flex items-center rounded-full mt-1 {{ $registration->payment_status === 'paid' ? 'bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400' : 'bg-orange-100 dark:bg-orange-900/30 px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:text-orange-400' }}">
                            {{ strtoupper($registration->payment_status) }}
                        </span>
                    </div>
                    @if($registration->payment_status === 'paid')
                        <div>
                            <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Registration Fee Paid</p>
                            <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">₵{{ number_format($registration->registration_fee, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Payment Date</p>
                            <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $registration->payment_date ? $registration->payment_date->format('Y-m-d H:i:s') : 'N/A' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($registration->payment_status === 'pending')
            <!-- Payment Pending -->
            <div class="flex flex-1 flex-col items-start justify-between gap-4 rounded-xl border border-orange-200 dark:border-orange-800 bg-orange-50 dark:bg-orange-900/20 p-5">
                <div class="flex flex-col gap-1 w-full">
                    <p class="text-gray-900 dark:text-white text-base font-bold leading-tight">Complete Your Registration</p>
                    <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Your registration is incomplete. Please complete payment of ₵{{ number_format($registration->registration_fee, 2) }} to access all features.</p>
                </div>
                <button onclick="initializePayment()" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
                    <span class="truncate">Complete Payment</span>
                </button>
            </div>
        @elseif($registration->payment_status === 'paid')
            <!-- WhatsApp Contact -->
            <div class="flex flex-1 flex-col items-start justify-between gap-4 rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] p-5">
                <div class="flex flex-col gap-1 w-full">
                    <p class="text-gray-900 dark:text-white text-base font-bold leading-tight">Contact Registrar</p>
                    <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Click the button below to contact the registrar via WhatsApp</p>
                </div>
                <a href="{{ $whatsappLink }}" target="_blank" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
                    <span class="truncate">Contact via WhatsApp</span>
                </a>
            </div>
        @endif
    </div>

    <!-- PayStack Script -->
    <script src="https://js.paystack.co/v1/inline.js"></script>
    
    <script>
        async function initializePayment() {
            try {
                const response = await fetch('{{ route("student.payment") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (!data.success) {
                    alert(data.message || 'Failed to initialize payment. Please try again.');
                    return;
                }

                // Initialize PayStack payment - opens directly in popup
                const handler = PaystackPop.setup({
                    key: data.public_key,
                    email: data.email,
                    amount: data.amount,
                    ref: data.reference,
                    currency: 'GHS',
                    onClose: function() {
                        // User closed the payment popup
                        console.log('Payment popup closed');
                    },
                    callback: function(response) {
                        // Payment successful - verify and reload
                        verifyPayment(response.reference);
                    }
                });

                handler.openIframe();
            } catch (error) {
                console.error('Payment error:', error);
                alert('An error occurred. Please try again.');
            }
        }

        async function verifyPayment(reference) {
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
                    // Reload page to show updated status
                    window.location.reload();
                } else {
                    alert(data.message || 'Payment verification failed. Please contact support.');
                }
            } catch (error) {
                console.error('Verification error:', error);
                alert('Payment verification failed. Please contact support.');
            }
        }
    </script>
</x-app-layout>
