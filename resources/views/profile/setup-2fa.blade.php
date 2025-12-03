<x-app-layout>
    <x-slot name="title">Setup Two-Factor Authentication</x-slot>

    @php
        $user = auth()->user();
        $isAdmin = $user->isAdmin();
    @endphp

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center mb-8">
        <div>
            <h1 class="{{ $isAdmin ? 'text-white' : 'text-gray-900 dark:text-white' }} text-4xl font-black leading-tight tracking-[-0.033em]">Setup Two-Factor Authentication</h1>
            <p class="{{ $isAdmin ? 'text-text-muted-dark' : 'text-gray-600 dark:text-gray-400' }} text-base font-normal leading-normal mt-2">Scan the QR code with your authenticator app to enable 2FA</p>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="rounded-lg {{ $isAdmin ? 'bg-surface-dark border border-border-dark' : 'bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67]' }} overflow-hidden">
            <div class="px-6 py-4 border-b {{ $isAdmin ? 'border-border-dark bg-background-dark/50' : 'border-gray-200 dark:border-[#324d67] bg-gray-50 dark:bg-gray-800' }}">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-2xl">qr_code_scanner</span>
                    <div>
                        <h2 class="{{ $isAdmin ? 'text-white' : 'text-gray-900 dark:text-white' }} text-xl font-bold leading-tight">Scan QR Code</h2>
                        <p class="{{ $isAdmin ? 'text-text-muted-dark' : 'text-gray-500 dark:text-gray-400' }} text-sm font-normal leading-normal">Use an authenticator app like Google Authenticator or Authy</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @if(session('error'))
                    <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 rounded-xl p-4">
                        <p class="text-sm text-red-800 dark:text-red-400">{{ session('error') }}</p>
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Instructions -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <h3 class="text-sm font-bold text-blue-900 dark:text-blue-400 mb-2">How to set up:</h3>
                        <ol class="list-decimal list-inside space-y-1 text-sm text-blue-800 dark:text-blue-300">
                            <li>Install an authenticator app on your phone (Google Authenticator, Authy, Microsoft Authenticator, etc.)</li>
                            <li>Open the app and tap "Add account" or the "+" button</li>
                            <li>Scan the QR code below with your phone</li>
                            <li>Enter the 6-digit code from your app to verify and enable 2FA</li>
                        </ol>
                    </div>

                    <!-- QR Code -->
                    <div class="flex flex-col items-center">
                        <div class="bg-white p-2 rounded-lg border-2 border-gray-200 dark:border-[#324d67] mb-4 flex items-center justify-center" style="width: 280px; height: 280px;">
                            <div style="width: 256px; height: 256px;">
                                {!! $qrCodeSvg !!}
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center mb-4">
                            Can't scan? Enter this code manually: <span class="font-mono font-bold text-gray-900 dark:text-white">{{ $secret }}</span>
                        </p>
                    </div>
                    <style>
                        .qr-code-wrapper svg { width: 256px !important; height: 256px !important; display: block; }
                    </style>

                    <!-- Verification Form -->
                    <form method="POST" action="{{ route('profile.2fa.enable') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="code" class="block text-sm font-medium {{ $isAdmin ? 'text-white' : 'text-gray-700 dark:text-gray-300' }} mb-2">
                                Enter 6-digit code from your authenticator app
                            </label>
                            <input 
                                type="text" 
                                id="code" 
                                name="code" 
                                required 
                                maxlength="6" 
                                pattern="[0-9]{6}"
                                autocomplete="off"
                                class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2.5 text-lg text-center tracking-widest font-mono text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                placeholder="000000"
                            >
                            @error('code')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('profile.edit') }}" class="flex-1 flex items-center justify-center rounded-lg h-12 px-6 border border-gray-300 dark:border-[#324d67] {{ $isAdmin ? 'text-white hover:bg-background-dark/50' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }} text-base font-medium transition-all">
                                Cancel
                            </a>
                            <button type="submit" class="flex-1 flex items-center justify-center rounded-lg h-12 px-6 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 active:scale-[0.98] transition-all shadow-md hover:shadow-lg">
                                Verify & Enable
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

