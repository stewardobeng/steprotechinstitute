<x-app-layout>
    <x-slot name="title">Affiliate Agents Management</x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 mb-4 sm:mb-6">
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-blue-600/20 to-blue-800/20 dark:from-blue-600/20 dark:to-blue-800/20 border border-blue-500/30 dark:border-blue-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">Total Agents</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-green-600/20 to-green-800/20 dark:from-green-600/20 dark:to-green-800/20 border border-green-500/30 dark:border-green-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">Approved</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">{{ number_format($stats['approved']) }}</p>
        </div>
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-orange-600/20 to-orange-800/20 dark:from-orange-600/20 dark:to-orange-800/20 border border-orange-500/30 dark:border-orange-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">Pending</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">{{ number_format($stats['pending']) }}</p>
        </div>
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-purple-600/20 to-purple-800/20 dark:from-purple-600/20 dark:to-purple-800/20 border border-purple-500/30 dark:border-purple-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">Active</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">{{ number_format($stats['active']) }}</p>
        </div>
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-cyan-600/20 to-cyan-800/20 dark:from-cyan-600/20 dark:to-cyan-800/20 border border-cyan-500/30 dark:border-cyan-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">Total Earnings</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">₵{{ number_format($stats['total_earnings'], 2) }}</p>
        </div>
    </div>

    <div class="mt-4 sm:mt-8">
        <!-- Search and Filters -->
        <div class="flex flex-col gap-4 rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] p-4 sm:p-6 mb-4 sm:mb-6">
            <h2 class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">Search & Filter</h2>
            <form method="GET" action="{{ route('admin.affiliate-agents.index') }}" class="flex flex-col gap-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Search</label>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search by Name, Email, Phone, or Referral Link"
                            class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                        />
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Approval Status</label>
                        <select 
                            name="approval_status"
                            class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                            <option value="">All</option>
                            <option value="1" {{ request('approval_status') == '1' ? 'selected' : '' }}>Approved</option>
                            <option value="0" {{ request('approval_status') == '0' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">User Status</label>
                        <select 
                            name="user_status"
                            class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                            <option value="">All</option>
                            <option value="active" {{ request('user_status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('user_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-primary text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] px-4">
                        <span class="material-symbols-outlined text-base">search</span>
                        <span>Search</span>
                    </button>
                    <a href="{{ route('admin.affiliate-agents.index') }}" class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] px-4 hover:bg-gray-200 dark:hover:bg-white/20">
                        <span class="material-symbols-outlined text-base">refresh</span>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Affiliate Agents Table -->
        <div class="w-full bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-[#324d67] flex justify-between items-center">
                <h2 class="text-gray-900 dark:text-white text-lg font-bold">All Affiliate Agents</h2>
                <span class="text-gray-500 dark:text-[#92adc9] text-sm">Total: {{ $agents->total() }} agents</span>
            </div>
            @if($agents->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800 text-xs uppercase">
                            <tr>
                                <th class="px-5 py-3 text-gray-600 dark:text-gray-300">Name</th>
                                <th class="px-5 py-3 text-gray-600 dark:text-gray-300">Email</th>
                                <th class="px-5 py-3 text-gray-600 dark:text-gray-300">Phone</th>
                                <th class="px-5 py-3 text-gray-600 dark:text-gray-300">Referral Link</th>
                                <th class="px-5 py-3 text-gray-600 dark:text-gray-300">Earnings</th>
                                <th class="px-5 py-3 text-gray-600 dark:text-gray-300">Balance</th>
                                <th class="px-5 py-3 text-gray-600 dark:text-gray-300">Students</th>
                                <th class="px-5 py-3 text-gray-600 dark:text-gray-300">Status</th>
                                <th class="px-5 py-3 text-right text-gray-600 dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-[#324d67]">
                            @foreach($agents as $agent)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $agent->user->name }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-500 dark:text-[#92adc9]">{{ $agent->user->email }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-500 dark:text-[#92adc9]">{{ $agent->user->phone ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm font-mono text-gray-500 dark:text-[#92adc9] truncate max-w-xs">{{ $agent->referral_link }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">₵{{ number_format($agent->total_earnings, 2) }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">₵{{ number_format($agent->wallet_balance, 2) }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-500 dark:text-[#92adc9]">{{ $agent->studentRegistrations()->where('payment_status', 'paid')->count() }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            @if($agent->registration_approved)
                                                <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400 w-fit">Approved</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-orange-100 dark:bg-orange-900/30 px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:text-orange-400 w-fit">Pending</span>
                                            @endif
                                            @if($agent->user->status === 'inactive')
                                                <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-400 w-fit">Inactive</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.affiliate-agents.show', $agent) }}" class="text-primary hover:text-primary/80 text-sm font-medium px-2 py-1 rounded hover:bg-primary/10" title="View">
                                                <span class="material-symbols-outlined text-lg">visibility</span>
                                            </a>
                                            <button onclick="editAgent({{ $agent->id }})" class="text-primary hover:text-primary/80 text-sm font-medium px-2 py-1 rounded hover:bg-primary/10" title="Edit">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                            </button>
                                            @if(!$agent->registration_approved)
                                                <form method="POST" action="{{ route('admin.affiliate-agents.approve', $agent) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 text-sm font-medium px-2 py-1 rounded hover:bg-green-500/10" title="Approve">
                                                        <span class="material-symbols-outlined text-lg">check_circle</span>
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.affiliate-agents.reject', $agent) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300 text-sm font-medium px-2 py-1 rounded hover:bg-yellow-500/10" title="Reject" onclick="return confirm('Are you sure you want to reject this agent?');">
                                                        <span class="material-symbols-outlined text-lg">cancel</span>
                                                    </button>
                                                </form>
                                            @endif
                                            @if($agent->user->status === 'active')
                                                <form method="POST" action="{{ route('admin.affiliate-agents.deactivate', $agent) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm font-medium px-2 py-1 rounded hover:bg-red-500/10" title="Deactivate" onclick="return confirm('Are you sure you want to deactivate this agent?');">
                                                        <span class="material-symbols-outlined text-lg">block</span>
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.affiliate-agents.activate', $agent) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 text-sm font-medium px-2 py-1 rounded hover:bg-green-500/10" title="Activate">
                                                        <span class="material-symbols-outlined text-lg">check_circle</span>
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('admin.affiliate-agents.destroy', $agent) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this affiliate agent? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger hover:text-danger/80 text-sm font-medium px-2 py-1 rounded hover:bg-danger/10" title="Delete">
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
                    {{ $agents->links() }}
                </div>
            @else
                <div class="p-5 text-center text-gray-500 dark:text-[#92adc9]">No affiliate agents found.</div>
            @endif
        </div>
    </div>

    <!-- Edit Agent Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#111a22] rounded-lg border border-gray-200 dark:border-[#324d67] p-6 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-gray-900 dark:text-white text-lg font-bold">Edit Affiliate Agent</h3>
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
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="registration_approved" id="edit_approved" value="1" class="rounded border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-primary focus:ring-primary">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">Registration Approved</span>
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
        function editAgent(agentId) {
            fetch(`{{ url('/admin/affiliate-agents') }}/${agentId}`, {
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
                    document.getElementById('edit_approved').checked = data.registration_approved;
                    document.getElementById('editForm').action = `{{ url('/admin/affiliate-agents') }}/${agentId}`;
                    document.getElementById('editModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading agent data. Please try again.');
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
