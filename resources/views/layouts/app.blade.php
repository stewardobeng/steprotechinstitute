<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title . ' - ' : '' }}{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-background-light dark:bg-background-dark font-display">
        @php
            use Illuminate\Support\Facades\Storage;
            $user = auth()->user();
            $isAdmin = $user && $user->isAdmin();
            $isAffiliate = $user && $user->isAffiliateAgent();
            $isStudent = $user && $user->isStudent();
        @endphp

        <div class="relative flex h-auto min-h-screen w-full flex-col">
            <div class="flex h-full w-full">
                <!-- SideNavBar -->
                <aside class="fixed left-0 top-0 h-screen w-64 flex flex-col border-r {{ $isAdmin ? 'border-gray-200 dark:border-border-dark bg-white dark:bg-surface-dark/30' : 'border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]' }} p-4 z-40 overflow-y-auto">
                    <div class="flex flex-col gap-4">
                        <!-- App Name Header -->
                        <div class="flex items-center gap-3 py-3 border-b {{ $isAdmin ? 'border-gray-200 dark:border-border-dark' : 'border-gray-200 dark:border-[#324d67]' }}">
                            <h1 class="{{ $isAdmin ? 'text-gray-900 dark:text-white' : 'text-gray-900 dark:text-white' }} text-xl font-bold leading-tight">{{ \App\Models\Setting::getValue('app_name', config('app.name', 'Laravel')) }}</h1>
                        </div>

                        <!-- Navigation -->
                        <nav class="flex flex-col gap-2 mt-4">
                            @if($isAdmin)
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}" href="{{ route('admin.dashboard') }}">
                                    <span class="material-symbols-outlined">dashboard</span>
                                    <p class="text-sm font-medium leading-normal">Dashboard</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.affiliate-agents.*') ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}" href="{{ route('admin.affiliate-agents.index') }}">
                                    <span class="material-symbols-outlined">group</span>
                                    <p class="text-sm font-medium leading-normal">Affiliates</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.students.*') ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}" href="{{ route('admin.students.index') }}">
                                    <span class="material-symbols-outlined">school</span>
                                    <p class="text-sm font-medium leading-normal">Students</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.invite-codes.*') ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}" href="{{ route('admin.invite-codes.index') }}">
                                    <span class="material-symbols-outlined">key</span>
                                    <p class="text-sm font-medium leading-normal">Invite Codes</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.withdrawals.*') ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}" href="{{ route('admin.withdrawals.index') }}">
                                    <span class="material-symbols-outlined">credit_card</span>
                                    <p class="text-sm font-medium leading-normal">Payments</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.classroom.*') ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}" href="{{ route('admin.classroom.index') }}">
                                    <span class="material-symbols-outlined">class</span>
                                    <p class="text-sm font-medium leading-normal">Classroom</p>
                                </a>
                            @elseif($isAffiliate)
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('affiliate.dashboard') ? 'bg-primary/10 dark:bg-[#233648]' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('affiliate.dashboard') }}">
                                    <span class="material-symbols-outlined {{ request()->routeIs('affiliate.dashboard') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }}">dashboard</span>
                                    <p class="{{ request()->routeIs('affiliate.dashboard') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Dashboard</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('affiliate.students.*') ? 'bg-primary/10 dark:bg-[#233648]' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('affiliate.students.index') }}">
                                    <span class="material-symbols-outlined {{ request()->routeIs('affiliate.students.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }}">group</span>
                                    <p class="{{ request()->routeIs('affiliate.students.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Students</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('affiliate.withdrawals.*') ? 'bg-primary/10 dark:bg-[#233648]' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('affiliate.withdrawals.index') }}">
                                    <span class="material-symbols-outlined {{ request()->routeIs('affiliate.withdrawals.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }}">payments</span>
                                    <p class="{{ request()->routeIs('affiliate.withdrawals.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Withdrawals</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('affiliate.analytics') ? 'bg-primary/10 dark:bg-[#233648]' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('affiliate.analytics') }}">
                                    <span class="material-symbols-outlined {{ request()->routeIs('affiliate.analytics') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }}">analytics</span>
                                    <p class="{{ request()->routeIs('affiliate.analytics') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Analytics</p>
                                </a>
                            @elseif($isStudent)
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('student.dashboard') ? 'bg-primary/10 dark:bg-[#233648]' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('student.dashboard') }}">
                                    <span class="material-symbols-outlined {{ request()->routeIs('student.dashboard') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }}">dashboard</span>
                                    <p class="{{ request()->routeIs('student.dashboard') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Dashboard</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('student.resources.*') ? 'bg-primary/10 dark:bg-[#233648]' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('student.resources.recordings') }}">
                                    <span class="material-symbols-outlined {{ request()->routeIs('student.resources.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }}">video_library</span>
                                    <p class="{{ request()->routeIs('student.resources.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Resources</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('student.classroom.*') ? 'bg-primary/10 dark:bg-[#233648]' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('student.classroom.index') }}">
                                    <span class="material-symbols-outlined {{ request()->routeIs('student.classroom.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }}">class</span>
                                    <p class="{{ request()->routeIs('student.classroom.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Classroom</p>
                                </a>
                            @endif
                            @if($user)
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('profile.edit') ? ($isAdmin ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'bg-primary/10 dark:bg-[#233648]') : ($isAdmin ? 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' : 'hover:bg-gray-100 dark:hover:bg-gray-800') }}" href="{{ route('profile.edit') }}">
                                    <span class="material-symbols-outlined {{ $isAdmin ? 'text-gray-700 dark:text-white' : 'text-gray-600 dark:text-white' }}">person</span>
                                    <p class="{{ $isAdmin ? 'text-gray-700 dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Profile</p>
                                </a>
                            @endif
                        </nav>
                    </div>

                    @if($user)
                        <div class="mt-auto flex flex-col gap-3 pt-4 border-t {{ $isAdmin ? 'border-gray-200 dark:border-white/10' : 'border-gray-200 dark:border-[#324d67]' }}">
                            <!-- User Profile Dropdown -->
                            <div class="relative" x-data="{ open: false, isDark: document.documentElement.classList.contains('dark') }" x-init="setInterval(() => { isDark = document.documentElement.classList.contains('dark') }, 100)">
                                <button 
                                    @click="open = !open"
                                    class="flex items-center gap-3 w-full p-2 rounded-lg {{ $isAdmin ? 'hover:bg-gray-100 dark:hover:bg-white/5' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }} transition"
                                >
                                    @if($user && $user->profile_image)
                                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style="background-image: url('{{ asset('storage/' . $user->profile_image) }}');"></div>
                                    @elseif($user)
                                        <div class="bg-primary aspect-square rounded-full size-10 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    @if($user)
                                        <div class="flex flex-col flex-1 text-left">
                                            <h1 class="{{ $isAdmin ? 'text-gray-900 dark:text-white' : 'text-gray-900 dark:text-white' }} text-sm font-medium leading-normal truncate">{{ $user->name }}</h1>
                                            <p class="{{ $isAdmin ? 'text-gray-600 dark:text-text-muted-dark' : 'text-gray-500 dark:text-[#92adc9]' }} text-xs font-normal leading-normal truncate">{{ $user->email }}</p>
                                        </div>
                                    @endif
                                    <span class="material-symbols-outlined {{ $isAdmin ? 'text-gray-500 dark:text-white/60' : 'text-gray-500 dark:text-[#92adc9]' }} text-lg transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div 
                                    x-show="open"
                                    @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute bottom-full left-0 right-0 mb-2 {{ $isAdmin ? 'bg-white dark:bg-surface-dark' : 'bg-white dark:bg-[#111a22]' }} rounded-lg border {{ $isAdmin ? 'border-gray-200 dark:border-border-dark' : 'border-gray-200 dark:border-[#324d67]' }} shadow-lg overflow-hidden z-50"
                                    style="display: none;"
                                >
                                    <a 
                                        href="{{ route('profile.edit') }}"
                                        class="flex items-center gap-3 px-4 py-3 {{ $isAdmin ? 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' : 'text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }} transition"
                                    >
                                        <span class="material-symbols-outlined text-lg">person</span>
                                        <span class="text-sm font-medium">Profile</span>
                                    </a>
                                    @if($isAdmin)
                                        <a 
                                            href="{{ route('admin.settings.index') }}"
                                            class="flex items-center gap-3 px-4 py-3 {{ $isAdmin ? 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' : 'text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }} transition"
                                        >
                                            <span class="material-symbols-outlined text-lg">settings</span>
                                            <span class="text-sm font-medium">Settings</span>
                                        </a>
                                    @endif
                                    <button 
                                        type="button"
                                        @click="window.toggleTheme(); isDark = document.documentElement.classList.contains('dark')"
                                        class="flex items-center gap-3 px-4 py-3 w-full text-left {{ $isAdmin ? 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' : 'text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }} transition"
                                    >
                                        <span class="material-symbols-outlined text-lg" x-show="isDark">light_mode</span>
                                        <span class="material-symbols-outlined text-lg" x-show="!isDark" style="display: none;">dark_mode</span>
                                        <span class="text-sm font-medium" x-text="isDark ? 'Light Mode' : 'Dark Mode'"></span>
                                    </button>
                                    <div class="border-t {{ $isAdmin ? 'border-gray-200 dark:border-white/10' : 'border-gray-200 dark:border-[#324d67]' }}"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button 
                                            type="submit" 
                                            class="flex items-center gap-3 px-4 py-3 w-full text-left {{ $isAdmin ? 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' : 'text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }} transition"
                                        >
                                            <span class="material-symbols-outlined text-lg">logout</span>
                                            <span class="text-sm font-medium">Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </aside>

                <div class="flex flex-1 flex-col ml-64 min-h-screen">
                    @if($isAdmin)
                        <!-- TopNavBar for Admin -->
                        <header class="sticky top-0 z-30 flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 dark:border-border-dark px-10 py-3 bg-white dark:bg-surface-dark/30 backdrop-blur-sm">
                            <div class="flex items-center gap-4">
                                <h2 class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">{{ $title ?? 'Administrator Dashboard' }}</h2>
                            </div>
                            <div class="flex flex-1 justify-end gap-4">
                                <label class="flex flex-col min-w-40 !h-10 max-w-64">
                                    <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                                        <div class="text-gray-500 dark:text-text-muted-dark flex bg-gray-100 dark:bg-surface-dark items-center justify-center pl-4 rounded-l-lg">
                                            <span class="material-symbols-outlined">search</span>
                                        </div>
                                        <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-0 border-none bg-gray-100 dark:bg-surface-dark h-full placeholder:text-gray-500 dark:placeholder:text-text-muted-dark px-4 rounded-l-none pl-2 text-base font-normal leading-normal" placeholder="Search..." type="search" id="admin-search" />
                                    </div>
                                </label>
                                <div class="flex gap-2">
                                    <button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 w-10 bg-gray-100 dark:bg-surface-dark text-gray-700 dark:text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 hover:bg-gray-200 dark:hover:bg-surface-dark/80 transition">
                                        <span class="material-symbols-outlined text-xl">notifications</span>
                                    </button>
                                    <button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 w-10 bg-gray-100 dark:bg-surface-dark text-gray-700 dark:text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 hover:bg-gray-200 dark:hover:bg-surface-dark/80 transition">
                                        <span class="material-symbols-outlined text-xl">help</span>
                                    </button>
                                </div>
                                @if($user && $user->profile_image)
                                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style="background-image: url('{{ asset('storage/' . $user->profile_image) }}');"></div>
                                @elseif($user)
                                    <div class="bg-primary aspect-square rounded-full size-10 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                    </div>
                </header>
                    @endif

                    <!-- Main Content -->
                    <main class="flex-1 {{ $isAdmin ? 'p-8' : 'p-6 lg:p-10' }} overflow-y-auto">
                        <div class="{{ $isAdmin ? 'mx-auto max-w-7xl space-y-8' : 'w-full max-w-7xl mx-auto' }}">
                            @if(session('success'))
                                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if(session('status'))
                                <div class="mb-6 bg-blue-100 dark:bg-blue-900/30 border border-blue-400 dark:border-blue-800 text-blue-700 dark:text-blue-400 px-4 py-3 rounded-lg">
                                    {{ session('status') }}
                                </div>
                            @endif

                {{ $slot }}
                        </div>
            </main>
                </div>
            </div>
        </div>
    </body>
</html>
