@php
    $user = auth()->user();
    $isAdmin = $user->isAdmin();
@endphp

<div class="space-y-6">
    @if(session('error'))
        <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 rounded-xl p-4">
            <p class="text-sm text-red-800 dark:text-red-400">{{ session('error') }}</p>
        </div>
    @endif

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
        <div class="bg-white p-2 rounded-lg border-2 border-gray-200 dark:border-[#324d67] mb-4 flex items-center justify-center qr-container" style="width: 280px; height: 280px;">
            <div id="qr-code-container" class="qr-code-wrapper" style="width: 256px; height: 256px;"></div>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 text-center mb-4">
            Can't scan? Enter this code manually: <span id="secret-code" class="font-mono font-bold text-gray-900 dark:text-white">{{ $secret }}</span>
        </p>
    </div>

    <style>
        .qr-code-wrapper svg {
            width: 256px !important;
            height: 256px !important;
            display: block;
        }
    </style>

    <!-- Verification Form -->
    <form method="POST" action="{{ route('profile.2fa.enable') }}" id="2fa-verify-form" class="space-y-4">
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
            <button type="button" onclick="close2FAModal()" class="flex-1 flex items-center justify-center rounded-lg h-12 px-6 border border-gray-300 dark:border-[#324d67] {{ $isAdmin ? 'text-white hover:bg-background-dark/50' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }} text-base font-medium transition-all">
                Cancel
            </button>
            <button type="submit" class="flex-1 flex items-center justify-center rounded-lg h-12 px-6 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 active:scale-[0.98] transition-all shadow-md hover:shadow-lg">
                Verify & Enable
            </button>
        </div>
    </form>
</div>

<script>
    function close2FAModal() {
        const modal = document.getElementById('2fa-setup-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Handle form submission
    document.getElementById('2fa-verify-form')?.addEventListener('submit', function(e) {
        // Form will submit normally, modal will close on redirect
    });
</script>

