<x-app-layout>
    <x-slot name="title">Students Management</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center mb-4 sm:mb-6">
        <h1 class="text-gray-900 dark:text-white text-2xl sm:text-3xl lg:text-4xl font-black leading-tight tracking-[-0.033em]">Students Management</h1>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-4 sm:mb-6">
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-blue-600/20 to-blue-800/20 dark:from-blue-600/20 dark:to-blue-800/20 border border-blue-500/30 dark:border-blue-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">Total Students</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-green-600/20 to-green-800/20 dark:from-green-600/20 dark:to-green-800/20 border border-green-500/30 dark:border-green-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">Paid Students</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">{{ number_format($stats['paid']) }}</p>
        </div>
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-orange-600/20 to-orange-800/20 dark:from-orange-600/20 dark:to-orange-800/20 border border-orange-500/30 dark:border-orange-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">Pending Payments</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">{{ number_format($stats['pending']) }}</p>
        </div>
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-purple-600/20 to-purple-800/20 dark:from-purple-600/20 dark:to-purple-800/20 border border-purple-500/30 dark:border-purple-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">WhatsApp Added</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">{{ number_format($stats['whatsapp_added']) }}</p>
        </div>
    </div>

    <div class="mt-8 grid gap-6">
        <!-- Search and Filters -->
        <div class="flex flex-col gap-4 rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] p-6">
            <h2 class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">Search & Filter</h2>
            <form method="GET" action="{{ route('admin.students.index') }}" class="flex flex-col gap-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Search</label>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search by ID, Name, Email, or Phone"
                            class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                        />
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Payment Status</label>
                        <select 
                            name="payment_status"
                            class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                            <option value="">All Statuses</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">WhatsApp Status</label>
                        <select 
                            name="whatsapp_status"
                            class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                            <option value="">All</option>
                            <option value="1" {{ request('whatsapp_status') == '1' ? 'selected' : '' }}>Added</option>
                            <option value="0" {{ request('whatsapp_status') == '0' ? 'selected' : '' }}>Not Added</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-primary text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] px-4">
                        <span class="material-symbols-outlined text-base">search</span>
                        <span>Search</span>
                    </button>
                    <a href="{{ route('admin.students.index') }}" class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] px-4 hover:bg-gray-200 dark:hover:bg-white/20">
                        <span class="material-symbols-outlined text-base">refresh</span>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Student Verification -->
        <div class="flex flex-1 flex-col items-start justify-between gap-4 rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] p-6">
            <div class="w-full">
                <h2 class="text-gray-900 dark:text-white text-lg font-bold mb-4">Verify Student by ID</h2>
                <form id="verify-form" class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" id="student_id" placeholder="Enter Student ID" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <button type="button" onclick="verifyStudent()" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
                        <span class="truncate">Verify</span>
                    </button>
                </form>
                <div id="verify-result" class="mt-4 hidden"></div>
            </div>
        </div>

        <!-- Students List -->
        <div class="w-full bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-200 dark:border-[#324d67] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                <h2 class="text-gray-900 dark:text-white text-base sm:text-lg font-bold">All Students</h2>
                <span class="text-gray-500 dark:text-[#92adc9] text-xs sm:text-sm">Total: {{ $students->total() }} students</span>
            </div>
            @if($students->count() > 0)
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <table class="w-full text-left text-xs sm:text-sm min-w-[1000px]">
                        <thead class="bg-gray-50 dark:bg-gray-800 text-xs uppercase">
                            <tr>
                                <th class="px-3 sm:px-5 py-3 text-gray-600 dark:text-gray-300">Student ID</th>
                                <th class="px-3 sm:px-5 py-3 text-gray-600 dark:text-gray-300">Name</th>
                                <th class="px-3 sm:px-5 py-3 text-gray-600 dark:text-gray-300">Email</th>
                                <th class="px-3 sm:px-5 py-3 text-gray-600 dark:text-gray-300">Phone</th>
                                <th class="px-3 sm:px-5 py-3 text-gray-600 dark:text-gray-300">Affiliate Agent</th>
                                <th class="px-3 sm:px-5 py-3 text-gray-600 dark:text-gray-300">Payment Status</th>
                                <th class="px-3 sm:px-5 py-3 text-gray-600 dark:text-gray-300">WhatsApp</th>
                                <th class="px-3 sm:px-5 py-3 text-gray-600 dark:text-gray-300">Registered</th>
                                <th class="px-3 sm:px-5 py-3 text-right text-gray-600 dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-[#324d67]">
                            @foreach($students as $student)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                        <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white font-mono break-all">{{ $student->student_id }}</p>
                                    </td>
                                    <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                        <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white">{{ $student->user->name }}</p>
                                    </td>
                                    <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                        <p class="text-xs sm:text-sm text-gray-500 dark:text-[#92adc9] break-all">{{ $student->user->email }}</p>
                                    </td>
                                    <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                        <p class="text-xs sm:text-sm text-gray-500 dark:text-[#92adc9]">{{ $student->user->phone ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                        <p class="text-xs sm:text-sm text-gray-500 dark:text-[#92adc9]">{{ $student->affiliateAgent->user->name ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-full {{ $student->payment_status === 'paid' ? 'bg-green-100 dark:bg-green-900/30 px-2 sm:px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400' : ($student->payment_status === 'pending' ? 'bg-orange-100 dark:bg-orange-900/30 px-2 sm:px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:text-orange-400' : 'bg-red-100 dark:bg-red-900/30 px-2 sm:px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-400') }}">
                                            {{ strtoupper($student->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if($student->added_to_whatsapp)
                                            <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400">Yes</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:text-gray-400">No</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-500 dark:text-[#92adc9]">{{ $student->created_at->format('Y-m-d') }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-2">
                                            <button onclick="editStudent({{ $student->id }})" class="text-primary hover:text-primary/80 text-sm font-medium px-2 py-1 rounded hover:bg-primary/10" title="Edit">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                            </button>
                                            <form method="POST" action="{{ route('admin.students.destroy', $student) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this student? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm font-medium px-2 py-1 rounded hover:bg-red-50 dark:hover:bg-red-900/30" title="Delete">
                                                    <span class="material-symbols-outlined text-lg">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-5 border-t border-gray-200 dark:border-[#324d67]">
                    {{ $students->links() }}
                </div>
            @else
                <div class="p-5 text-center text-gray-500 dark:text-[#92adc9]">No students found.</div>
            @endif
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#111a22] rounded-lg border border-gray-200 dark:border-[#324d67] p-6 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-gray-900 dark:text-white text-lg font-bold">Edit Student</h3>
                <button onclick="closeEditModal()" class="text-gray-500 dark:text-[#92adc9] hover:text-gray-900 dark:hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="editForm" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Name</label>
                    <input type="text" name="name" id="edit_name" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent" required>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" id="edit_email" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent" required>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Phone</label>
                    <input type="text" name="phone" id="edit_phone" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Payment Status</label>
                    <select name="payment_status" id="edit_payment_status" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="added_to_whatsapp" id="edit_whatsapp" value="1" class="rounded border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-primary focus:ring-primary">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">Added to WhatsApp</span>
                    </label>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="closeEditModal()" class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] px-4 hover:bg-gray-200 dark:hover:bg-white/20">
                        Cancel
                    </button>
                    <button type="submit" class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-primary text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] px-4">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function verifyStudent() {
            const studentId = document.getElementById('student_id').value;
            const resultDiv = document.getElementById('verify-result');
            
            if (!studentId) {
                alert('Please enter a Student ID');
                return;
            }

            fetch('{{ route("admin.students.verify") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ student_id: studentId })
            })
            .then(response => response.json())
            .then(data => {
                resultDiv.classList.remove('hidden');
                if (data.exists) {
                    const student = data.student;
                    let html = `
                        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
                            <h4 class="font-bold mb-2">Student Found</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <p><strong>Student ID:</strong> ${student.student_id}</p>
                                <p><strong>Name:</strong> ${student.name}</p>
                                <p><strong>Email:</strong> ${student.email}</p>
                                <p><strong>Phone:</strong> ${student.phone || 'N/A'}</p>
                                <p><strong>Registration Date:</strong> ${student.registration_date}</p>
                                <p><strong>Payment Status:</strong> ${student.payment_status}</p>
                                <p><strong>Registration Fee:</strong> ₵${student.registration_fee}</p>
                                <p><strong>Payment Date:</strong> ${student.payment_date || 'N/A'}</p>
                                <p><strong>Added to WhatsApp:</strong> ${student.added_to_whatsapp ? 'Yes' : 'No'}</p>
                                <p><strong>Affiliate Agent:</strong> ${student.affiliate_agent || 'N/A'}</p>
                            </div>
                    `;
                    
                    if (!student.added_to_whatsapp && student.payment_status === 'paid') {
                        html += `
                            <div class="mt-4">
                                <button onclick="markAsAdded('${student.student_id}')" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
                                    <span class="truncate">Mark as Added to WhatsApp</span>
                                </button>
                            </div>
                        `;
                    }
                    
                    html += `</div>`;
                    resultDiv.innerHTML = html;
                } else {
                    resultDiv.innerHTML = `
                        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
                            ${data.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                resultDiv.classList.remove('hidden');
                resultDiv.innerHTML = `
                    <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
                        Error verifying student. Please try again.
                    </div>
                `;
            });
        }

        function markAsAdded(studentId) {
            fetch('{{ route("admin.students.mark-added") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ student_id: studentId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    verifyStudent(); // Refresh the result
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                alert('Error marking student as added. Please try again.');
            });
        }

        function editStudent(studentId) {
            fetch(`{{ url('/admin/students') }}/${studentId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_name').value = data.user.name;
                    document.getElementById('edit_email').value = data.user.email;
                    document.getElementById('edit_phone').value = data.user.phone || '';
                    document.getElementById('edit_payment_status').value = data.payment_status;
                    document.getElementById('edit_whatsapp').checked = data.added_to_whatsapp;
                    document.getElementById('editForm').action = `{{ url('/admin/students') }}/${studentId}`;
                    document.getElementById('editModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading student data. Please try again.');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Close modal on outside click
        document.getElementById('editModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</x-app-layout>
