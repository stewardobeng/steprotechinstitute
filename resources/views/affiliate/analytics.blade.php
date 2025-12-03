<x-app-layout>
    <x-slot name="title">Analytics & Reports</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center">
        <h1 class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Analytics & Reports</h1>
    </div>

    <div class="mt-8 grid gap-6">
        @php
            $agent = auth()->user()->affiliateAgent;
            $analytics = [
                'total_earnings' => $agent->total_earnings,
                'total_withdrawn' => $agent->total_withdrawn,
                'wallet_balance' => $agent->wallet_balance,
                'pending_withdrawals' => $agent->withdrawals()->where('status', 'pending')->sum('amount'),
                'total_students' => $agent->studentRegistrations()->where('payment_status', 'paid')->count(),
            ];
        @endphp

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]">
                <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Total Earnings</p>
                <p class="text-gray-900 dark:text-white tracking-light text-2xl font-bold leading-tight">₵{{ number_format($analytics['total_earnings'], 2) }}</p>
            </div>
            <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]">
                <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Total Withdrawn</p>
                <p class="text-gray-900 dark:text-white tracking-light text-2xl font-bold leading-tight">₵{{ number_format($analytics['total_withdrawn'], 2) }}</p>
            </div>
            <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]">
                <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Wallet Balance</p>
                <p class="text-gray-900 dark:text-white tracking-light text-2xl font-bold leading-tight text-green-600">₵{{ number_format($analytics['wallet_balance'], 2) }}</p>
            </div>
            <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]">
                <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Total Students</p>
                <p class="text-gray-900 dark:text-white tracking-light text-2xl font-bold leading-tight">{{ $analytics['total_students'] }}</p>
            </div>
        </div>

        <!-- Detailed Analytics -->
        <div class="flex flex-1 flex-col items-start justify-between gap-4 rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] p-5">
            <div class="w-full">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Detailed Analytics</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold mb-2 text-gray-900 dark:text-white">Earnings Breakdown</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Total Commission Earned:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">₵{{ number_format($analytics['total_earnings'], 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Total Withdrawn:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">₵{{ number_format($analytics['total_withdrawn'], 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Pending Withdrawals:</span>
                                <span class="font-semibold text-orange-600 dark:text-orange-400">₵{{ number_format($analytics['pending_withdrawals'], 2) }}</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 dark:border-[#324d67] pt-2">
                                <span class="text-gray-900 dark:text-white font-semibold">Available Balance:</span>
                                <span class="font-semibold text-green-600 dark:text-green-400">₵{{ number_format($analytics['wallet_balance'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2 text-gray-900 dark:text-white">Performance Metrics</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Total Students:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $analytics['total_students'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Average per Student:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">₵40.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Withdrawal Eligibility:</span>
                                <span class="font-semibold {{ $analytics['wallet_balance'] >= 200 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $analytics['wallet_balance'] >= 200 ? 'Eligible' : 'Not Eligible (Min: 200 GHS)' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="w-full bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-[#324d67]">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent Student Registrations</h2>
            </div>
            @php
                $recentStudents = $agent->studentRegistrations()
                    ->with('user')
                    ->where('payment_status', 'paid')
                    ->latest()
                    ->limit(10)
                    ->get();
            @endphp
            @if($recentStudents->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Student ID</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Name</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Payment Date</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 text-right">Commission</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-[#324d67]">
                            @foreach($recentStudents as $student)
                                <tr>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white font-mono">{{ $student->student_id }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->user->name }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->payment_date ? $student->payment_date->format('Y-m-d') : 'N/A' }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">₵40.00</p>
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
    </div>
</x-app-layout>
