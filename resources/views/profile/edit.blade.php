<x-app-layout>
    <x-slot name="title">Profile Settings</x-slot>

    @php
        $user = auth()->user();
    @endphp

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center mb-8">
        <div>
            <h1 class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Profile Settings</h1>
            <p class="text-gray-600 dark:text-gray-400 text-base font-normal leading-normal mt-2">Manage your account information, security, and preferences</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Profile Overview -->
        <div class="lg:col-span-1">
            <div class="rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] p-6">
                <div class="flex flex-col items-center text-center">
                    @if($user->profile_image)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" class="w-32 h-32 rounded-full object-cover border-4 border-primary/20 dark:border-primary/30">
                        </div>
                    @else
                        <div class="mb-4 w-32 h-32 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center border-4 border-primary/20 dark:border-primary/30">
                            <span class="text-5xl font-bold text-primary">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <h2 class="text-gray-900 dark:text-white text-2xl font-bold mb-1">{{ $user->name }}</h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">{{ $user->email }}</p>
                    <div class="w-full space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                            <span class="text-gray-600 dark:text-gray-400 text-sm">Role</span>
                            <span class="text-gray-900 dark:text-white text-sm font-semibold capitalize">{{ str_replace('_', ' ', $user->role) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                            <span class="text-gray-600 dark:text-gray-400 text-sm">Status</span>
                            <span class="inline-flex items-center rounded-full {{ $user->status === 'active' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' }} px-2.5 py-0.5 text-xs font-medium">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                        @if($user->two_factor_enabled)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                                <span class="text-gray-600 dark:text-gray-400 text-sm">2FA</span>
                                <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 px-2.5 py-0.5 text-xs font-medium">
                                    Enabled
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings Forms -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Information -->
            <div class="rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-[#324d67] bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-2xl">person</span>
                        <div>
                            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight">Profile Information</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Update your account's profile information and email address</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password -->
            <div class="rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-[#324d67] bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-2xl">lock</span>
                        <div>
                            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight">Update Password</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Ensure your account is using a long, random password to stay secure</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Two Factor Authentication -->
            <div class="rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-[#324d67] bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-2xl">security</span>
                        <div>
                            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight">Two Factor Authentication</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Add an additional layer of security to your account</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.two-factor-authentication-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="rounded-lg bg-white dark:bg-[#111a22] border border-red-500/30 overflow-hidden">
                <div class="px-6 py-4 border-b border-red-500/30 bg-red-50 dark:bg-red-900/10">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-red-500 text-2xl">warning</span>
                        <div>
                            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight">Delete Account</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Permanently delete your account and all associated data</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <!-- Disable 2FA Modal -->
    <div id="disable-2fa-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDisable2FAModal()"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-[#324d67] bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-red-500 text-2xl">warning</span>
                            <div>
                                <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight">Disable Two-Factor Authentication</h2>
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Enter your password to confirm</p>
                            </div>
                        </div>
                        <button onclick="closeDisable2FAModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                </div>
                <div class="px-6 py-6">
                    <form method="POST" action="{{ route('profile.2fa.disable') }}" id="disable-2fa-form">
                        @csrf
                        @method('DELETE')
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Disabling two-factor authentication will reduce the security of your account. Please enter your password to confirm this action.
                            </p>
                            <label for="disable-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Password
                            </label>
                            <input 
                                type="password" 
                                id="disable-password" 
                                name="password" 
                                required 
                                autofocus
                                class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                placeholder="Enter your password"
                            >
                            @error('password')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex gap-3">
                            <button type="button" onclick="closeDisable2FAModal()" class="flex-1 flex items-center justify-center rounded-lg h-12 px-6 border border-gray-300 dark:border-[#324d67] text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 text-base font-medium transition-all">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 flex items-center justify-center rounded-lg h-12 px-6 bg-red-600 text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-red-700 active:scale-[0.98] transition-all shadow-md hover:shadow-lg">
                                Disable 2FA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 2FA Setup Modal -->
    <div id="2fa-setup-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="close2FAModal()"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-[#324d67] bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-2xl">qr_code_scanner</span>
                            <div>
                                <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight">Setup Two-Factor Authentication</h2>
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Scan the QR code with your authenticator app</p>
                            </div>
                        </div>
                        <button onclick="close2FAModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                </div>
                <div class="px-6 py-6" id="2fa-modal-content">
                    <div class="flex items-center justify-center py-12">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function open2FAModal() {
            const modal = document.getElementById('2fa-setup-modal');
            const content = document.getElementById('2fa-modal-content');
            
            // Show modal
            modal.classList.remove('hidden');
            
            // Show loading
            content.innerHTML = '<div class="flex items-center justify-center py-12"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>';
            
            // Fetch modal content
            fetch('{{ route("profile.setup-2fa") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    content.innerHTML = data.html;
                    // Insert QR code SVG separately to avoid encoding issues
                    const qrContainer = document.getElementById('qr-code-container');
                    if (qrContainer && data.qrCodeSvg) {
                        qrContainer.innerHTML = data.qrCodeSvg;
                    }
                } else {
                    content.innerHTML = `<div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 rounded-xl p-4"><p class="text-sm text-red-800 dark:text-red-400">${data.message || 'Failed to load 2FA setup'}</p></div>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                content.innerHTML = '<div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 rounded-xl p-4"><p class="text-sm text-red-800 dark:text-red-400">An error occurred. Please try again.</p></div>';
            });
        }

        function close2FAModal() {
            const modal = document.getElementById('2fa-setup-modal');
            modal.classList.add('hidden');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                close2FAModal();
            }
        });

        // Handle form submission - reload page on success
        document.addEventListener('submit', function(e) {
            if (e.target.id === '2fa-verify-form') {
                // Let form submit normally, page will reload on redirect
            }
        });

        function openDisable2FAModal() {
            const modal = document.getElementById('disable-2fa-modal');
            modal.classList.remove('hidden');
        }

        function closeDisable2FAModal() {
            const modal = document.getElementById('disable-2fa-modal');
            modal.classList.add('hidden');
            // Clear password field
            document.getElementById('disable-password').value = '';
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDisable2FAModal();
            }
        });
    </script>
</x-app-layout>
