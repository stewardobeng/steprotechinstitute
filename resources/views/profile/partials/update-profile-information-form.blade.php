@php
    use Illuminate\Support\Facades\Storage;
    $user = auth()->user();
@endphp

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method('patch')

    <!-- Profile Image -->
    <div class="flex flex-col gap-3">
        <label for="profile_image" class="text-sm font-medium text-gray-700 dark:text-gray-300">Profile Image</label>
        <div class="flex items-center gap-4">
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" class="w-20 h-20 rounded-full object-cover border-2 border-primary/20 dark:border-primary/30">
            @else
                <div class="w-20 h-20 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center border-2 border-primary/20 dark:border-primary/30">
                    <span class="text-2xl font-bold text-primary">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
            @endif
            <div class="flex-1">
                <input type="file" id="profile_image" name="profile_image" accept="image/*" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max size: 2MB. Formats: JPEG, PNG, JPG, GIF</p>
            </div>
        </div>
        @error('profile_image')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-transparent">
            @error('name')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-transparent">
            @error('email')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
                    <p class="text-sm text-yellow-800 dark:text-yellow-300">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="text-primary hover:text-primary/80 underline font-medium">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600 dark:text-green-400 font-medium">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="+233 XX XXX XXXX">
            @error('phone')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-[#324d67]">
        <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
            <span class="truncate">Save Changes</span>
        </button>

        @if (session('status') === 'profile-updated')
            <p class="text-sm text-green-600 dark:text-green-400 font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">check_circle</span>
                {{ __('Saved successfully.') }}
            </p>
        @endif
    </div>
</form>
