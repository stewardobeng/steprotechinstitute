<x-app-layout>
    <x-slot name="title">Withdrawal Requests</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center">
        <h1 class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Withdrawal Requests</h1>
    </div>

    <div class="mt-8 grid gap-6">
        <!-- Wallet Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]">
                <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Total Earnings</p>
                <p class="text-gray-900 dark:text-white tracking-light text-2xl font-bold leading-tight">₵{{ number_format($agent->total_earnings, 2) }}</p>
            </div>
            <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]">
                <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Total Withdrawn</p>
                <p class="text-gray-900 dark:text-white tracking-light text-2xl font-bold leading-tight">₵{{ number_format($agent->total_withdrawn, 2) }}</p>
            </div>
            <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]">
                <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Available Balance</p>
                <p class="text-gray-900 dark:text-white tracking-light text-2xl font-bold leading-tight text-green-600">₵{{ number_format($agent->wallet_balance, 2) }}</p>
            </div>
        </div>

        <!-- Request Withdrawal -->
        <div class="flex flex-1 flex-col items-start justify-between gap-4 rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] p-5">
            <div class="flex flex-col gap-1 w-full">
                <p class="text-gray-900 dark:text-white text-base font-bold leading-tight">Request Withdrawal</p>
                <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Minimum withdrawal amount: 200 GHS</p>
            </div>
            @if($agent->wallet_balance >= 200)
                <form method="POST" action="{{ route('affiliate.withdrawal.request') }}" class="w-full @[480px]:flex-row @[480px]:items-center flex flex-col gap-4">
                    @csrf
                    <div class="flex-1">
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount (GHS)</label>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" required min="200" step="0.01" max="{{ $agent->wallet_balance }}" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Available: ₵{{ number_format($agent->wallet_balance, 2) }}</p>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
                        <span class="truncate">Request Withdrawal</span>
                    </button>
                </form>
            @else
                <div class="w-full bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-400 dark:border-yellow-800 text-yellow-700 dark:text-yellow-400 px-4 py-3 rounded-lg">
                    <p>You need at least 200 GHS in your wallet to request a withdrawal.</p>
                    <p class="mt-1">Current balance: ₵{{ number_format($agent->wallet_balance, 2) }}</p>
                </div>
            @endif
        </div>

        <!-- Withdrawal History -->
        <div class="w-full bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-[#324d67]">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Withdrawal History</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Amount</th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Status</th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Requested</th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Approved/Paid</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-[#324d67]">
                        @forelse($withdrawals as $withdrawal)
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
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-4 text-center text-gray-500 dark:text-gray-400">No withdrawal requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($withdrawals->hasPages())
                <div class="p-5 border-t border-gray-200 dark:border-[#324d67]">
                    {{ $withdrawals->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
