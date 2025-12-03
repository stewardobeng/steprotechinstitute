<x-app-layout>
    <x-slot name="title">Invite Codes Management</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center">
        <h1 class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Invite Codes Management</h1>
    </div>

    <div class="mt-8 grid gap-6">
        <!-- Generate New Invite Code -->
        <div class="flex flex-1 flex-col items-start justify-between gap-4 rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] p-5">
            <div class="w-full">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Generate New Invite Code</h2>
                <form method="POST" action="{{ route('admin.invite-codes.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Code (optional)</label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Leave empty for auto-generation</p>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="max_uses" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Uses</label>
                        <input type="number" id="max_uses" name="max_uses" value="{{ old('max_uses', 1) }}" required min="1" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                        @error('max_uses')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expires At (optional)</label>
                        <input type="datetime-local" id="expires_at" name="expires_at" value="{{ old('expires_at') }}" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                        @error('expires_at')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-3">
                        <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
                            <span class="truncate">Generate Invite Code</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Validate Invite Code -->
        <div class="flex flex-1 flex-col items-start justify-between gap-4 rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] p-5">
            <div class="w-full">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Validate Invite Code</h2>
                <form id="validate-form" class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" id="validate_code" placeholder="Enter invite code to validate" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <button type="button" onclick="validateCode()" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
                        <span class="truncate">Validate</span>
                    </button>
                </form>
                <div id="validate-result" class="mt-4 hidden"></div>
            </div>
        </div>

        <!-- Invite Codes List -->
        <div class="w-full bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-[#324d67]">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">All Invite Codes</h2>
            </div>
            @if($inviteCodes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Code</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Type</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Generated By</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Uses</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Status</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Expires</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-[#324d67]">
                            @foreach($inviteCodes as $code)
                                <tr>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white font-mono">{{ $code->code }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $code->type)) }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $code->generator->name ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $code->current_uses }} / {{ $code->max_uses }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-full {{ $code->status === 'active' ? 'bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-400' }}">
                                            {{ ucfirst($code->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $code->expires_at ? $code->expires_at->format('Y-m-d H:i') : 'Never' }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if($code->status === 'active')
                                            <form method="POST" action="{{ route('admin.invite-codes.destroy', $code) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm font-medium">Deactivate</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-5 border-t border-gray-200 dark:border-[#324d67]">
                    {{ $inviteCodes->links() }}
                </div>
            @else
                <div class="p-5 text-center text-gray-500 dark:text-gray-400">No invite codes found.</div>
            @endif
        </div>
    </div>

    <script>
        function validateCode() {
            const code = document.getElementById('validate_code').value;
            const resultDiv = document.getElementById('validate-result');
            
            if (!code) {
                alert('Please enter an invite code');
                return;
            }

            fetch('{{ route("admin.invite-codes.validate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code })
            })
            .then(response => response.json())
            .then(data => {
                resultDiv.classList.remove('hidden');
                if (data.valid) {
                    resultDiv.innerHTML = `
                        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
                            <p><strong>Valid Code:</strong> ${data.code}</p>
                            <p>Uses: ${data.current_uses} / ${data.max_uses}</p>
                            <p>Remaining: ${data.remaining_uses}</p>
                        </div>
                    `;
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
                        Error validating code. Please try again.
                    </div>
                `;
            });
        }
    </script>
</x-app-layout>
