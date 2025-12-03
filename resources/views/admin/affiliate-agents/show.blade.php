<x-app-layout>
    <x-slot name="title">Affiliate Agent Details</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center">
        <h1 class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Affiliate Agent Details</h1>
        <a href="{{ route('admin.affiliate-agents.index') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-gray-600 text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-gray-700">
            <span class="truncate">Back to List</span>
        </a>
    </div>

    <div class="mt-8 grid gap-6">
        <!-- Agent Information -->
        <div class="flex flex-1 flex-col items-start justify-between gap-4 rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] p-5">
            <div class="w-full">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Agent Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Name</p>
                        <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $affiliateAgent->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Email</p>
                        <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $affiliateAgent->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Phone</p>
                        <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $affiliateAgent->user->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Referral Link</p>
                        <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1 font-mono">{{ $affiliateAgent->referral_link }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Registration Status</p>
                        <span class="inline-flex items-center rounded-full mt-1 {{ $affiliateAgent->registration_approved ? 'bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400' : 'bg-orange-100 dark:bg-orange-900/30 px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:text-orange-400' }}">
                            {{ $affiliateAgent->registration_approved ? 'Approved' : 'Pending' }}
                        </span>
                    </div>
                    @if($affiliateAgent->approved_at)
                        <div>
                            <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Approved At</p>
                            <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $affiliateAgent->approved_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Approved By</p>
                            <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $affiliateAgent->approver->name ?? 'N/A' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]">
                <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Total Earnings</p>
                <p class="text-gray-900 dark:text-white tracking-light text-2xl font-bold leading-tight">₵{{ number_format($affiliateAgent->total_earnings, 2) }}</p>
            </div>
            <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]">
                <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Total Withdrawn</p>
                <p class="text-gray-900 dark:text-white tracking-light text-2xl font-bold leading-tight">₵{{ number_format($affiliateAgent->total_withdrawn, 2) }}</p>
            </div>
            <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]">
                <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Wallet Balance</p>
                <p class="text-gray-900 dark:text-white tracking-light text-2xl font-bold leading-tight">₵{{ number_format($affiliateAgent->wallet_balance, 2) }}</p>
            </div>
        </div>

        <!-- Student Registrations -->
        <div class="w-full bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-[#324d67]">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Student Registrations ({{ $affiliateAgent->studentRegistrations->count() }})</h2>
            </div>
            @if($affiliateAgent->studentRegistrations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Student ID</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Name</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Payment Status</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-[#324d67]">
                            @foreach($affiliateAgent->studentRegistrations as $registration)
                                <tr>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white font-mono">{{ $registration->student_id }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $registration->user->name }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-full {{ $registration->payment_status === 'paid' ? 'bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400' : 'bg-orange-100 dark:bg-orange-900/30 px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:text-orange-400' }}">
                                            {{ strtoupper($registration->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $registration->created_at->format('Y-m-d') }}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-5 text-center text-gray-500 dark:text-gray-400">No student registrations yet.</div>
            @endif
        </div>

        <!-- Withdrawal History -->
        <div class="w-full bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-[#324d67]">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Withdrawal History</h2>
            </div>
            @if($affiliateAgent->withdrawals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Amount</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Status</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Requested</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Approved</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-[#324d67]">
                            @foreach($affiliateAgent->withdrawals as $withdrawal)
                                <tr>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">₵{{ number_format($withdrawal->amount, 2) }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-full 
                                            {{ $withdrawal->status === 'paid' ? 'bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400' : 
                                               ($withdrawal->status === 'approved' ? 'bg-blue-100 dark:bg-blue-900/30 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:text-blue-400' : 
                                               ($withdrawal->status === 'pending' ? 'bg-orange-100 dark:bg-orange-900/30 px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:text-orange-400' : 'bg-red-100 dark:bg-red-900/30 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-400')) }}">
                                            {{ strtoupper($withdrawal->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $withdrawal->requested_at->format('Y-m-d H:i') }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $withdrawal->approved_at ? $withdrawal->approved_at->format('Y-m-d H:i') : 'N/A' }}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-5 text-center text-gray-500 dark:text-gray-400">No withdrawal requests yet.</div>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            @if(!$affiliateAgent->registration_approved)
                <form method="POST" action="{{ route('admin.affiliate-agents.approve', $affiliateAgent) }}" class="inline">
                    @csrf
                    <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-green-600 text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-green-700">
                        <span class="truncate">Approve Registration</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
