<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            {{ __('Two Factor Authentication') }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            {{ __('Add an additional layer of security to your account by enabling two-factor authentication.') }}
        </p>
    </header>

    @php
        $twoFactor = $user->twoFactorAuthentication ?? null;
    @endphp

    <div class="space-y-6">
        @if($twoFactor && $twoFactor->enabled)
            <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-green-800 dark:text-green-400 mb-1">
                            {{ __('Two Factor Authentication is enabled.') }}
                        </h3>
                        <p class="text-sm text-green-700 dark:text-green-300">
                            {{ __('Your account is protected with two-factor authentication.') }}
                        </p>
                    </div>
                    <button onclick="openDisable2FAModal()" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-red-600 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-red-700">
                        <span class="truncate">{{ __('Disable') }}</span>
                    </button>
                </div>
            </div>

            @php
                $displayCodes = session('backup_codes') ?? ($twoFactor->backup_codes ?? []);
                $showWarning = session('warning');
            @endphp

            @if(session('warning'))
                <div class="bg-orange-100 dark:bg-orange-900/30 border border-orange-400 dark:border-orange-800 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-600 dark:text-orange-400">warning</span>
                        <p class="text-sm text-orange-800 dark:text-orange-400 font-medium">{{ session('warning') }}</p>
                    </div>
                </div>
            @endif

            @if(!empty($displayCodes))
                <div class="bg-blue-100 dark:bg-blue-900/30 border border-blue-400 dark:border-blue-800 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-bold text-blue-800 dark:text-blue-400">
                            {{ __('Backup Codes') }}
                        </h3>
                        <div class="flex gap-2">
                            <button onclick="copyBackupCodes()" class="flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-sm">content_copy</span>
                                <span>Copy</span>
                            </button>
                            <button onclick="downloadBackupCodes()" class="flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-sm">download</span>
                                <span>Download</span>
                            </button>
                            <form method="POST" action="{{ route('profile.2fa.regenerate-codes') }}" class="inline" onsubmit="return confirm('Are you sure? This will invalidate your current backup codes and generate new ones. Make sure to save the new codes!');">
                                @csrf
                                <button type="submit" class="flex items-center gap-1 px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-sm">refresh</span>
                                    <span>Regenerate</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    <p class="text-sm text-blue-700 dark:text-blue-300 mb-3">
                        {{ __('Save these backup codes in a safe place. You can use them to access your account if you lose your device.') }}
                    </p>
                    <div id="backup-codes-container" class="grid grid-cols-2 gap-2 font-mono text-sm">
                        @foreach($displayCodes as $code)
                            <div class="bg-white dark:bg-gray-800 p-2 rounded-lg border border-gray-200 dark:border-[#324d67] text-gray-900 dark:text-white">{{ $code }}</div>
                        @endforeach
                    </div>
                    <div id="copy-success-message" class="hidden mt-3 text-xs text-green-600 dark:text-green-400 font-medium">
                        ✓ Codes copied to clipboard!
                    </div>
                </div>

                <script>
                    const backupCodes = @json($displayCodes);
                    
                    function copyBackupCodes() {
                        const codesText = backupCodes.join('\n');
                        
                        navigator.clipboard.writeText(codesText).then(function() {
                            const message = document.getElementById('copy-success-message');
                            message.classList.remove('hidden');
                            setTimeout(() => {
                                message.classList.add('hidden');
                            }, 3000);
                        }).catch(function(err) {
                            console.error('Failed to copy codes:', err);
                            alert('Failed to copy codes. Please copy them manually.');
                        });
                    }
                    
                    function downloadBackupCodes() {
                        const codesText = backupCodes.join('\n');
                        const filename = 'backup-codes-' + new Date().toISOString().split('T')[0] + '.txt';
                        const content = `Two-Factor Authentication Backup Codes\n` +
                                      `Generated: ${new Date().toLocaleString()}\n` +
                                      `\nIMPORTANT: Store these codes in a safe place.\n` +
                                      `You can use them to access your account if you lose your device.\n\n` +
                                      codesText;
                        
                        const blob = new Blob([content], { type: 'text/plain' });
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);
                    }
                </script>
            @endif
        @else
            <div class="bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-400 dark:border-yellow-800 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-yellow-800 dark:text-yellow-400 mb-1">
                            {{ __('Two Factor Authentication is disabled.') }}
                        </h3>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">
                            {{ __('Enable two-factor authentication to add an extra layer of security to your account.') }}
                        </p>
                    </div>
                    <button onclick="open2FAModal()" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
                        <span class="truncate">{{ __('Enable') }}</span>
                    </button>
                </div>
            </div>
        @endif
    </div>
</section>
