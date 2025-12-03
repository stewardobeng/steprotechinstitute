<form method="post" action="{{ route('password.update') }}" class="space-y-5">
    @csrf
    @method('put')

    <div class="grid grid-cols-1 md:grid-cols-1 gap-5">
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
            <input type="password" id="update_password_current_password" name="current_password" autocomplete="current-password" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-transparent">
            @error('current_password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
            <input type="password" id="update_password_password" name="password" autocomplete="new-password" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-transparent">
            @error('password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
            <input type="password" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-transparent">
            @error('password_confirmation', 'updatePassword')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-[#324d67]">
        <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
            <span class="truncate">Update Password</span>
        </button>

        @if (session('status') === 'password-updated')
            <p class="text-sm text-green-600 dark:text-green-400 font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">check_circle</span>
                {{ __('Password updated successfully.') }}
            </p>
        @endif
    </div>
</form>
