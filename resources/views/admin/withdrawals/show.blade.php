<x-app-layout>
    <x-slot name="title">Withdrawal Details</x-slot>

    <div class="flex flex-wrap justify-end gap-3 items-center mb-4 sm:mb-6">
        <a href="{{ route('admin.withdrawals.index') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-gray-600 text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-gray-700">
            <span class="truncate">Back to List</span>
        </a>
    </div>

    <div class="mt-8 grid gap-6">
        <!-- Withdrawal Information -->
        <div class="flex flex-1 flex-col items-start justify-between gap-4 rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] p-5">
            <div class="w-full">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Withdrawal Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Affiliate Agent</p>
                        <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $withdrawal->affiliateAgent->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Email</p>
                        <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $withdrawal->affiliateAgent->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Amount</p>
                        <p class="text-gray-900 dark:text-white text-2xl font-bold leading-tight mt-1">₵{{ number_format($withdrawal->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Status</p>
                        <span class="inline-flex items-center rounded-full mt-1 
                            {{ $withdrawal->status === 'paid' ? 'bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400' : 
                               ($withdrawal->status === 'approved' ? 'bg-blue-100 dark:bg-blue-900/30 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:text-blue-400' : 
                               ($withdrawal->status === 'pending' ? 'bg-orange-100 dark:bg-orange-900/30 px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:text-orange-400' : 'bg-red-100 dark:bg-red-900/30 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-400')) }}">
                            {{ strtoupper($withdrawal->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Requested At</p>
                        <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $withdrawal->requested_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    @if($withdrawal->approved_at)
                        <div>
                            <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Approved At</p>
                            <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $withdrawal->approved_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Approved By</p>
                            <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $withdrawal->approver->name ?? 'N/A' }}</p>
                        </div>
                    @endif
                    @if($withdrawal->payment_proof)
                        <div>
                            <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Payment Proof</p>
                            <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $withdrawal->payment_proof }}</p>
                        </div>
                    @endif
                    @if($withdrawal->notes)
                        <div class="col-span-2">
                            <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Notes</p>
                            <p class="text-gray-900 dark:text-white text-base font-medium leading-normal mt-1">{{ $withdrawal->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Agent Wallet Information -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]">
                <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Total Earnings</p>
                <p class="text-gray-900 dark:text-white tracking-light text-2xl font-bold leading-tight">₵{{ number_format($withdrawal->affiliateAgent->total_earnings, 2) }}</p>
            </div>
            <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]">
                <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Total Withdrawn</p>
                <p class="text-gray-900 dark:text-white tracking-light text-2xl font-bold leading-tight">₵{{ number_format($withdrawal->affiliateAgent->total_withdrawn, 2) }}</p>
            </div>
            <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]">
                <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Current Balance</p>
                <p class="text-gray-900 dark:text-white tracking-light text-2xl font-bold leading-tight">₵{{ number_format($withdrawal->affiliateAgent->wallet_balance, 2) }}</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            @if($withdrawal->status === 'pending')
                <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" class="inline">
                    @csrf
                    <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-green-600 text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-green-700">
                        <span class="truncate">Approve</span>
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}" class="inline">
                    @csrf
                    <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-red-600 text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-red-700" onclick="return confirm('Are you sure?')">
                        <span class="truncate">Reject</span>
                    </button>
                </form>
            @endif
            @if($withdrawal->status === 'approved')
                <button onclick="showMarkPaidModal()" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
                    <span class="truncate">Mark as Paid</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Mark as Paid Modal -->
    <div id="markPaidModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white dark:bg-[#111a22] border-gray-200 dark:border-[#324d67]">
            <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Mark Withdrawal as Paid</h3>
            <form method="POST" action="{{ route('admin.withdrawals.mark-paid', $withdrawal) }}">
                @csrf
                <div class="mb-4">
                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Proof (optional)</label>
                    <input type="text" id="payment_proof" name="payment_proof" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes (optional)</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
                        <span class="truncate">Mark as Paid</span>
                    </button>
                    <button type="button" onclick="closeMarkPaidModal()" class="flex-1 flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-gray-600 text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-gray-700">
                        <span class="truncate">Cancel</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showMarkPaidModal() {
            document.getElementById('markPaidModal').classList.remove('hidden');
        }

        function closeMarkPaidModal() {
            document.getElementById('markPaidModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
