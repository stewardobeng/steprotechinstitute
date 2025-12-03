<x-app-layout>
    <x-slot name="title">Classroom Management</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center">
        <h1 class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Classroom Management</h1>
    </div>

    <div class="mt-8 grid gap-6">
        <!-- Classroom ID Settings Card -->
        <div class="flex flex-col rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] p-6">
            <h2 class="text-gray-900 dark:text-white text-2xl font-bold leading-tight mb-4">Classroom ID Settings</h2>
            
            <form method="POST" action="{{ route('admin.classroom.update-id') }}" class="space-y-4">
                @csrf
                <div class="flex flex-col gap-2">
                    <label for="classroom_id" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">Classroom ID</label>
                    <div class="flex gap-3">
                        <input 
                            type="text" 
                            id="classroom_id" 
                            name="classroom_id" 
                            value="{{ $classroomId }}" 
                            required
                            class="flex-1 rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-12 px-4 text-base font-normal leading-normal font-mono" 
                            placeholder="Enter TeachMint Classroom ID"
                        />
                        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition font-medium">
                            Save Classroom ID
                        </button>
                    </div>
                    <p class="text-gray-500 dark:text-[#92adc9] text-xs">This Classroom ID will be displayed to students for accessing the TeachMint classroom.</p>
                </div>
            </form>
        </div>

        <!-- Students List Card -->
        <div class="flex flex-col rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-[#324d67]">
                <h2 class="text-gray-900 dark:text-white text-2xl font-bold leading-tight">Student Classroom Access</h2>
                <p class="text-gray-500 dark:text-[#92adc9] text-sm mt-1">Manage student approval status for classroom access</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-[#324d67]">
                        <tr>
                            <th class="px-5 py-3 text-gray-600 dark:text-gray-300 text-sm font-semibold">Student ID</th>
                            <th class="px-5 py-3 text-gray-600 dark:text-gray-300 text-sm font-semibold">Name</th>
                            <th class="px-5 py-3 text-gray-600 dark:text-gray-300 text-sm font-semibold">Email</th>
                            <th class="px-5 py-3 text-gray-600 dark:text-gray-300 text-sm font-semibold">Payment Status</th>
                            <th class="px-5 py-3 text-gray-600 dark:text-gray-300 text-sm font-semibold">Classroom Status</th>
                            <th class="px-5 py-3 text-gray-600 dark:text-gray-300 text-sm font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-[#324d67]">
                        @forelse($students as $student)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                <td class="px-5 py-4">
                                    <p class="text-gray-900 dark:text-white text-sm font-mono">{{ $student->student_id }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-gray-900 dark:text-white text-sm font-medium">{{ $student->user->name }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-gray-500 dark:text-[#92adc9] text-sm">{{ $student->user->email }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full {{ $student->payment_status === 'paid' ? 'bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400' : 'bg-orange-100 dark:bg-orange-900/30 px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:text-orange-400' }}">
                                        {{ strtoupper($student->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full {{ $student->classroom_approved ? 'bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400' : 'bg-orange-100 dark:bg-orange-900/30 px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:text-orange-400' }}">
                                        {{ $student->classroom_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <button 
                                        onclick="toggleApproval({{ $student->id }})"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $student->classroom_approved ? 'bg-orange-500 hover:bg-orange-600 text-white' : 'bg-primary hover:bg-primary/90 text-white' }}"
                                    >
                                        {{ $student->classroom_approved ? 'Revoke Approval' : 'Approve Access' }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-gray-500 dark:text-[#92adc9]">
                                    No students found. Students who have completed payment will appear here.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        async function toggleApproval(studentId) {
            try {
                const response = await fetch(`/admin/classroom/students/${studentId}/toggle-approval`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Reload page to show updated status
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to update approval status.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        }
    </script>
</x-app-layout>

