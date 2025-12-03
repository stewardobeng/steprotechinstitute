<x-guest-layout>
    <div class="relative flex min-h-screen w-full flex-col items-center justify-center bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full w-full grow flex-col">
            <div class="flex flex-1 items-center justify-center py-5">
                <main class="flex w-full max-w-6xl flex-1 flex-col items-center justify-center lg:flex-row">
                    <!-- Left Column: Branding & Welcome Message -->
                    <div class="hidden lg:flex lg:w-1/2 flex-col gap-6 lg:gap-8 p-8 lg:p-12 justify-center">
                        <div class="flex flex-col gap-4 lg:gap-6">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-3xl lg:text-4xl">login</span>
                                <h1 class="text-xl lg:text-2xl font-bold text-slate-800 dark:text-white">{{ \App\Models\Setting::getValue('app_name', config('app.name', 'Portal')) }}</h1>
                            </div>
                            <div class="flex flex-col gap-2 text-left">
                                <h2 class="text-slate-900 dark:text-white text-3xl lg:text-4xl xl:text-5xl font-black leading-tight tracking-[-0.033em]">
                                    Welcome Back!
                                </h2>
                                <p class="text-slate-600 dark:text-[#92adc9] text-sm lg:text-base font-normal leading-normal">
                                    Sign in to access your dashboard and manage your account.
                                </p>
                            </div>
                        </div>
                        <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl" data-alt="Abstract gradient" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBmq294oSeDOLG5svfKPUaijmROhkbO-Rd-M9tOSP3JbVG8r11QZbkqha6wVO1QsWkgKqfCI9kOOJck9_fvxLsL9f78SqLq1Pjl5DXo6Xw-l7ayEp0w5h7QNH6GsHIVGmvKo77xxdRcobZ8xh6PG01GnhQQk7_pQlqcS16SNajnGa1WumYFjVTNvNLBNfFlbchdxywyb3j7d4hDmjcTfHya6jqXBndtP6-w0A_MEff5uf5Lkng6AWF1vBNY77vgEr0rqjAzWX6iSGQ");'></div>
                    </div>

                    <!-- Right Column: Login Form -->
                    <div class="flex w-full max-w-md flex-col items-center justify-center gap-4 sm:gap-6 p-4 sm:p-6 lg:w-1/2 lg:max-w-none lg:p-8 xl:p-12">
                        <div class="w-full max-w-[480px]">
                            <div class="flex flex-wrap justify-between gap-3 pb-3 sm:pb-4">
                                <div class="flex flex-col gap-2">
                                    <p class="text-slate-900 dark:text-white text-2xl sm:text-3xl lg:text-4xl font-black leading-tight tracking-[-0.033em]">Login</p>
                                    <p class="text-slate-600 dark:text-[#92adc9] text-sm sm:text-base font-normal leading-normal">Enter your credentials to access your dashboard.</p>
                                </div>
                            </div>

                            @if(session('status'))
                                <div class="mb-4 bg-blue-100 dark:bg-blue-900/30 border border-blue-400 dark:border-blue-800 text-blue-700 dark:text-blue-400 px-4 py-3 rounded-lg">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" class="flex w-full flex-col gap-4">
                                @csrf

                                <label class="flex flex-col flex-1">
                                    <p class="text-slate-800 dark:text-white text-base font-medium leading-normal pb-2">Email Address</p>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email') }}" 
                                        required 
                                        autofocus
                                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-slate-800 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary border border-slate-300 dark:border-[#324d67] bg-background-light dark:bg-[#192633] h-14 placeholder:text-slate-400 dark:placeholder:text-[#92adc9] p-[15px] text-base font-normal leading-normal" 
                                        placeholder="you@example.com"
                                    />
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </label>

                                <label class="flex flex-col flex-1">
                                    <p class="text-slate-800 dark:text-white text-base font-medium leading-normal pb-2">Password</p>
                                    <div class="flex w-full flex-1 items-stretch rounded-lg">
                                        <input 
                                            type="password" 
                                            id="password" 
                                            name="password" 
                                            required
                                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-slate-800 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary border border-slate-300 dark:border-[#324d67] bg-background-light dark:bg-[#192633] h-14 placeholder:text-slate-400 dark:placeholder:text-[#92adc9] p-[15px] rounded-r-none border-r-0 pr-2 text-base font-normal leading-normal" 
                                            placeholder="Enter your password"
                                        />
                                        <div class="text-slate-500 dark:text-[#92adc9] flex border border-slate-300 dark:border-[#324d67] bg-background-light dark:bg-[#192633] items-center justify-center pr-[15px] rounded-r-lg border-l-0 cursor-pointer" onclick="togglePassword()">
                                            <span class="material-symbols-outlined" id="password-toggle-icon">visibility</span>
                                        </div>
                                    </div>
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </label>

                                <div class="flex items-center justify-between pt-1">
                                    <label for="remember_me" class="inline-flex items-center">
                                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 dark:border-[#324d67] text-primary focus:ring-primary">
                                        <span class="ms-2 text-sm text-slate-600 dark:text-[#92adc9]">Remember me</span>
                                    </label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-primary text-sm font-normal leading-normal underline cursor-pointer">Forgot Password?</a>
                                    @endif
                                </div>

                                <button type="submit" class="flex min-w-[84px] w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-14 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] mt-4 hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark">
                                    <span class="truncate">Login</span>
                                </button>

                                <div class="text-sm text-center text-slate-600 dark:text-slate-400 pt-2 space-y-2">
                                    <p>
                                        Don't have an account? 
                                        <a href="{{ route('register.affiliate') }}" class="font-medium text-primary cursor-pointer">Register as Affiliate Agent</a>
                                    </p>
                                    <p>
                                        <a href="{{ route('register') }}" class="font-medium text-primary cursor-pointer">Register as Student</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </main>
            </div>

            <footer class="flex items-center justify-center p-6 border-t border-slate-200 dark:border-slate-800">
                <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-slate-600 dark:text-slate-400">
                    <p>© {{ date('Y') }} {{ \App\Models\Setting::getValue('app_name', config('app.name', 'Portal')) }}. All Rights Reserved.</p>
                    <a class="hover:text-primary" href="#">Terms of Service</a>
                    <a class="hover:text-primary" href="#">Privacy Policy</a>
                    <a class="hover:text-primary" href="#">Support</a>
                </div>
            </footer>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = 'visibility';
            }
        }
    </script>
</x-guest-layout>
