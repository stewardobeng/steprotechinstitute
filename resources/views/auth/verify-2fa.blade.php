<x-guest-layout>
    <div class="relative flex min-h-screen w-full flex-col items-center justify-center bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full w-full grow flex-col">
            <div class="flex flex-1 items-center justify-center py-5">
                <main class="flex w-full max-w-6xl flex-1 flex-col items-center justify-center lg:flex-row">
                    <!-- Left Column: Branding & Welcome Message -->
                    <div class="hidden lg:flex lg:w-1/2 flex-col gap-8 p-12 justify-center">
                        <div class="flex flex-col gap-6">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-4xl">security</span>
                                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ config('app.name', 'Portal') }}</h1>
                            </div>
                            <div class="flex flex-col gap-2 text-left">
                                <h2 class="text-slate-900 dark:text-white text-5xl font-black leading-tight tracking-[-0.033em]">
                                    Two-Factor Authentication
                                </h2>
                                <p class="text-slate-600 dark:text-[#92adc9] text-base font-normal leading-normal">
                                    Enter the 6-digit code from your authenticator app to complete login.
                                </p>
                            </div>
                        </div>
                        <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl" data-alt="Abstract gradient" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBmq294oSeDOLG5svfKPUaijmROhkbO-Rd-M9tOSP3JbVG8r11QZbkqha6wVO1QsWkgKqfCI9kOOJck9_fvxLsL9f78SqLq1Pjl5DXo6Xw-l7ayEp0w5h7QNH6GsHIVGmvKo77xxdRcobZ8xh6PG01GnhQQk7_pQlqcS16SNajnGa1WumYFjVTNvNLBNfFlbchdxywyb3j7d4hDmjcTfHya6jqXBndtP6-w0A_MEff5uf5Lkng6AWF1vBNY77vgEr0rqjAzWX6iSGQ");'></div>
                    </div>

                    <!-- Right Column: 2FA Verification Form -->
                    <div class="flex w-full max-w-md flex-col items-center justify-center gap-6 p-6 lg:w-1/2 lg:max-w-none lg:p-12">
                        <div class="w-full max-w-[480px]">
                            <div class="flex flex-wrap justify-between gap-3 pb-4">
                                <div class="flex flex-col gap-2">
                                    <p class="text-slate-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Verify Code</p>
                                    <p class="text-slate-600 dark:text-[#92adc9] text-base font-normal leading-normal">Enter the 6-digit code from your authenticator app</p>
                                </div>
                            </div>

                            @if($errors->any())
                                <div class="mb-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
                                    <ul class="list-disc list-inside text-sm">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login.2fa.verify') }}" class="flex w-full flex-col gap-4">
                                @csrf

                                <label class="flex flex-col flex-1">
                                    <p class="text-slate-800 dark:text-white text-base font-medium leading-normal pb-2">Verification Code</p>
                                    <input 
                                        type="text" 
                                        id="code" 
                                        name="code" 
                                        required 
                                        maxlength="6" 
                                        pattern="[0-9]{6}"
                                        autocomplete="off"
                                        autofocus
                                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-slate-800 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary border border-slate-300 dark:border-[#324d67] bg-background-light dark:bg-[#192633] h-14 placeholder:text-slate-400 dark:placeholder:text-[#92adc9] p-[15px] text-lg text-center tracking-widest font-mono font-normal leading-normal" 
                                        placeholder="000000"
                                    />
                                </label>

                                <button type="submit" class="flex min-w-[84px] w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-14 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] mt-4 hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark">
                                    <span class="truncate">Verify & Continue</span>
                                </button>

                                <div class="text-sm text-center text-slate-600 dark:text-slate-400 pt-2">
                                    <p>
                                        <a href="{{ route('login') }}" class="font-medium text-primary cursor-pointer">Back to Login</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </main>
            </div>

            <footer class="flex items-center justify-center p-6 border-t border-slate-200 dark:border-slate-800">
                <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-slate-600 dark:text-slate-400">
                    <p>© {{ date('Y') }} {{ config('app.name', 'Portal') }}. All Rights Reserved.</p>
                    <a class="hover:text-primary" href="#">Terms of Service</a>
                    <a class="hover:text-primary" href="#">Privacy Policy</a>
                    <a class="hover:text-primary" href="#">Support</a>
                </div>
            </footer>
        </div>
    </div>
</x-guest-layout>

