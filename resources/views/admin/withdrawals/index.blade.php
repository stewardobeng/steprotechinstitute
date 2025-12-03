<x-app-layout>
    <x-slot name="title">Withdrawal Requests Management</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center">
        <h1 class="text-gray-900 dark:text-white text-2xl sm:text-3xl lg:text-4xl font-black leading-tight tracking-[-0.033em]">Withdrawal Requests Management</h1>
    </div>

    <div class="mt-4 sm:mt-8">
        <!-- Withdrawals Table -->
        <div class="w-full bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-200 dark:border-[#324d67]">
                <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">All Withdrawal Requests</h2>
            </div>
            @if($withdrawals->count() > 0)
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <table class="w-full text-left text-xs sm:text-sm min-w-[800px]">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Agent</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Amount</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Status</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Requested</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Approved</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-[#324d67]">
                            @foreach($withdrawals as $withdrawal)
                                <tr>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $withdrawal->affiliateAgent->user->name }}</p>
                                    </td>
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
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.withdrawals.show', $withdrawal) }}" class="text-primary hover:text-primary/80 text-sm font-medium">View</a>
                                            @if($withdrawal->status === 'pending')
                                                <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 text-sm font-medium">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm font-medium" onclick="return confirm('Are you sure you want to reject this withdrawal?')">Reject</button>
                                                </form>
                                            @endif
                                            @if($withdrawal->status === 'approved')
                                                <button onclick="showMarkPaidModal({{ $withdrawal->id }})" class="text-primary hover:text-primary/80 text-sm font-medium">Mark as Paid</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-5 border-t border-gray-200 dark:border-[#324d67]">
                    {{ $withdrawals->links() }}
                </div>
            @else
                <div class="p-5 text-center text-gray-500 dark:text-gray-400">No withdrawal requests found.</div>
            @endif
        </div>
    </div>

    <!-- Mark as Paid Modal -->
    <div id="markPaidModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white dark:bg-[#111a22] border-gray-200 dark:border-[#324d67]">
            <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Mark Withdrawal as Paid</h3>
            <form id="markPaidForm" method="POST">
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
        function showMarkPaidModal(withdrawalId) {
            document.getElementById('markPaidForm').action = `/admin/withdrawals/${withdrawalId}/mark-paid`;
            document.getElementById('markPaidModal').classList.remove('hidden');
        }

        function closeMarkPaidModal() {
            document.getElementById('markPaidModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
