<x-app-layout>
    <x-slot name="title">My Students</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center">
        <h1 class="text-gray-900 dark:text-white text-2xl sm:text-3xl lg:text-4xl font-black leading-tight tracking-[-0.033em]">My Students</h1>
    </div>

    <div class="mt-6 sm:mt-8">
        <!-- Students Table -->
        <div class="w-full bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-200 dark:border-[#324d67]">
                <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">All My Referred Students</h2>
            </div>
            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <table class="w-full text-left text-xs sm:text-sm min-w-[800px]">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-3 sm:px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Student ID</th>
                            <th class="px-3 sm:px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Student Name</th>
                            <th class="px-3 sm:px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Email</th>
                            <th class="px-3 sm:px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Payment Status</th>
                            <th class="px-3 sm:px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Payment Date</th>
                            <th class="px-3 sm:px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Registration Fee</th>
                            <th class="px-3 sm:px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 text-right">Commission Earned</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-[#324d67]">
                        @forelse($students as $student)
                            <tr>
                                <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                    <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white font-mono break-all">{{ $student->student_id }}</p>
                                </td>
                                <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                    <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white">{{ $student->user->name }}</p>
                                </td>
                                <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 break-all">{{ $student->user->email }}</p>
                                </td>
                                <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-full {{ $student->payment_status === 'paid' ? 'bg-green-100 dark:bg-green-900/30 px-2 sm:px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400' : ($student->payment_status === 'pending' ? 'bg-orange-100 dark:bg-orange-900/30 px-2 sm:px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:text-orange-400' : 'bg-red-100 dark:bg-red-900/30 px-2 sm:px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-400') }}">
                                        {{ ucfirst($student->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $student->payment_date ? $student->payment_date->format('Y-m-d') : ($student->payment_status === 'pending' ? $student->created_at->format('Y-m-d') . ' (Pending)' : 'N/A') }}</p>
                                </td>
                                <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">₵{{ number_format($student->registration_fee, 2) }}</p>
                                </td>
                                <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap text-right">
                                    <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white">₵{{ $student->payment_status === 'paid' ? '40.00' : '0.00' }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-4 text-center text-gray-500 dark:text-gray-400">No students have registered yet. Share your referral link to start earning commissions!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($students->hasPages())
                <div class="p-4 sm:p-5 border-t border-gray-200 dark:border-[#324d67]">
                    {{ $students->links() }}
                </div>
            @endif
            @if($students->count() > 0)
                <div class="p-4 sm:p-5 border-t border-gray-200 dark:border-[#324d67] bg-gray-50 dark:bg-gray-800">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400"><strong>Total Students:</strong> {{ $students->total() }}</p>
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400"><strong>Paid Students:</strong> {{ $students->where('payment_status', 'paid')->count() }}</p>
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400"><strong>Total Commission:</strong> ₵{{ number_format($students->where('payment_status', 'paid')->count() * 40, 2) }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
