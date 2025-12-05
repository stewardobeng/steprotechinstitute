<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @php
            $appName = \App\Models\Setting::getValue('app_name', config('app.name', 'Laravel'));
        @endphp

        <title>{{ $title ? $title . ' - ' : '' }}{{ $appName }}</title>
        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">

        <!-- Theme initialization - prevents flash of wrong theme -->
        <script>
            (function() {
                const darkMode = localStorage.getItem('darkMode');
                if (darkMode === 'true') {
                    document.documentElement.classList.add('dark');
                } else {
                    // Default to light theme
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>

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

        <div class="relative flex h-auto min-h-screen w-full flex-col" x-data="{ 
            sidebarOpen: false,
            isMobile: false,
            init() {
                // Check if mobile on init
                this.isMobile = window.innerWidth < 1024;
                // On desktop, sidebar should always be visible (we use CSS for this)
                // On mobile, start with sidebar closed
                this.sidebarOpen = !this.isMobile;
                
                // Handle window resize
                const handleResize = () => {
                    const wasMobile = this.isMobile;
                    this.isMobile = window.innerWidth < 1024;
                    
                    // If switching from mobile to desktop, open sidebar
                    if (wasMobile && !this.isMobile) {
                        this.sidebarOpen = true;
                    }
                    // If switching from desktop to mobile, close sidebar
                    else if (!wasMobile && this.isMobile) {
                        this.sidebarOpen = false;
                    }
                };
                
                window.addEventListener('resize', handleResize);
                
                // Cleanup on component destroy
                this.$watch('sidebarOpen', (value) => {
                    // Prevent sidebar from closing on desktop
                    if (!this.isMobile && !value) {
                        this.sidebarOpen = true;
                    }
                });
            }
        }">
            <div class="flex h-full w-full">
                <!-- Mobile Overlay -->
                <div x-show="sidebarOpen && isMobile" 
                     @click="sidebarOpen = false"
                     x-transition:enter="transition-opacity ease-linear duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-linear duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-600 bg-opacity-75 z-30 lg:hidden"
                     style="display: none;"></div>
                
                <!-- SideNavBar -->
                <aside :class="{
                    'translate-x-0': sidebarOpen || !isMobile,
                    '-translate-x-full': !sidebarOpen && isMobile
                }"
                       class="fixed left-0 top-0 h-screen w-64 flex flex-col border-r {{ $isAdmin ? 'border-gray-200 dark:border-border-dark bg-white dark:bg-surface-dark/30' : 'border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22]' }} p-4 z-40 overflow-y-auto transition-transform duration-300 ease-in-out lg:translate-x-0">
                    <div class="flex flex-col gap-4">
                        <!-- App Name Header -->
                        <div class="flex items-center gap-3 py-3 border-b {{ $isAdmin ? 'border-gray-200 dark:border-border-dark' : 'border-gray-200 dark:border-[#324d67]' }}">
                            <h1 class="{{ $isAdmin ? 'text-gray-900 dark:text-white' : 'text-gray-900 dark:text-white' }} text-xl font-bold leading-tight">{{ \App\Models\Setting::getValue('app_name', config('app.name', 'Laravel')) }}</h1>
                        </div>

                        <!-- Navigation -->
                        <nav class="flex flex-col gap-2 mt-4">
                            @if($isAdmin)
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}" href="{{ route('admin.dashboard') }}" @click="if (isMobile) { sidebarOpen = false; }">
                                    <span class="material-symbols-outlined">dashboard</span>
                                    <p class="text-sm font-medium leading-normal">Dashboard</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.affiliate-agents.*') ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}" href="{{ route('admin.affiliate-agents.index') }}" @click="if (isMobile) { sidebarOpen = false; }">
                                    <span class="material-symbols-outlined">group</span>
                                    <p class="text-sm font-medium leading-normal">Affiliates</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.students.*') ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}" href="{{ route('admin.students.index') }}" @click="if (isMobile) { sidebarOpen = false; }">
                                    <span class="material-symbols-outlined">school</span>
                                    <p class="text-sm font-medium leading-normal">Students</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.invite-codes.*') ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}" href="{{ route('admin.invite-codes.index') }}" @click="if (isMobile) { sidebarOpen = false; }">
                                    <span class="material-symbols-outlined">key</span>
                                    <p class="text-sm font-medium leading-normal">Invite Codes</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.withdrawals.*') ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}" href="{{ route('admin.withdrawals.index') }}" @click="if (isMobile) { sidebarOpen = false; }">
                                    <span class="material-symbols-outlined">credit_card</span>
                                    <p class="text-sm font-medium leading-normal">Payments</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.classroom.*') ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' }}" href="{{ route('admin.classroom.index') }}" @click="if (isMobile) { sidebarOpen = false; }">
                                    <span class="material-symbols-outlined">class</span>
                                    <p class="text-sm font-medium leading-normal">Classroom</p>
                                </a>
                            @elseif($isAffiliate)
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('affiliate.dashboard') ? 'bg-primary/10 dark:bg-[#233648]' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('affiliate.dashboard') }}" @click="if (isMobile) { sidebarOpen = false; }">
                                    <span class="material-symbols-outlined {{ request()->routeIs('affiliate.dashboard') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }}">dashboard</span>
                                    <p class="{{ request()->routeIs('affiliate.dashboard') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Dashboard</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('affiliate.students.*') ? 'bg-primary/10 dark:bg-[#233648]' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('affiliate.students.index') }}" @click="if (isMobile) { sidebarOpen = false; }">
                                    <span class="material-symbols-outlined {{ request()->routeIs('affiliate.students.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }}">group</span>
                                    <p class="{{ request()->routeIs('affiliate.students.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Students</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('affiliate.withdrawals.*') ? 'bg-primary/10 dark:bg-[#233648]' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('affiliate.withdrawals.index') }}" @click="if (isMobile) { sidebarOpen = false; }">
                                    <span class="material-symbols-outlined {{ request()->routeIs('affiliate.withdrawals.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }}">payments</span>
                                    <p class="{{ request()->routeIs('affiliate.withdrawals.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Withdrawals</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('affiliate.analytics') ? 'bg-primary/10 dark:bg-[#233648]' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('affiliate.analytics') }}" @click="if (isMobile) { sidebarOpen = false; }">
                                    <span class="material-symbols-outlined {{ request()->routeIs('affiliate.analytics') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }}">analytics</span>
                                    <p class="{{ request()->routeIs('affiliate.analytics') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Analytics</p>
                                </a>
                            @elseif($isStudent)
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('student.dashboard') ? 'bg-primary/10 dark:bg-[#233648]' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('student.dashboard') }}" @click="if (isMobile) { sidebarOpen = false; }">
                                    <span class="material-symbols-outlined {{ request()->routeIs('student.dashboard') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }}">dashboard</span>
                                    <p class="{{ request()->routeIs('student.dashboard') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Dashboard</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('student.resources.*') ? 'bg-primary/10 dark:bg-[#233648]' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('student.resources.recordings') }}" @click="if (isMobile) { sidebarOpen = false; }">
                                    <span class="material-symbols-outlined {{ request()->routeIs('student.resources.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }}">video_library</span>
                                    <p class="{{ request()->routeIs('student.resources.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Resources</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('student.classroom.*') ? 'bg-primary/10 dark:bg-[#233648]' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('student.classroom.index') }}" @click="if (isMobile) { sidebarOpen = false; }">
                                    <span class="material-symbols-outlined {{ request()->routeIs('student.classroom.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }}">class</span>
                                    <p class="{{ request()->routeIs('student.classroom.*') ? 'text-primary dark:text-white' : 'text-gray-600 dark:text-white' }} text-sm font-medium leading-normal">Classroom</p>
                                </a>
                            @endif
                            @if($user)
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('profile.edit') ? ($isAdmin ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'bg-primary/10 dark:bg-[#233648]') : ($isAdmin ? 'text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/5' : 'hover:bg-gray-100 dark:hover:bg-gray-800') }}" href="{{ route('profile.edit') }}" @click="if (isMobile) { sidebarOpen = false; }">
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

                <div class="flex flex-1 flex-col lg:ml-64 min-h-screen">
                    @if($isAdmin)
                        <!-- TopNavBar for Admin -->
                        <header class="sticky top-0 z-30 flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 dark:border-border-dark px-4 sm:px-6 lg:px-10 py-3 bg-white dark:bg-surface-dark/30 backdrop-blur-sm">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <!-- Mobile Menu Button -->
                                <button @click.prevent="sidebarOpen = !sidebarOpen" type="button" class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path x-show="!sidebarOpen" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" style="display: none;"></path>
                                        <path x-show="sidebarOpen" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;"></path>
                                    </svg>
                                </button>
                                <h2 class="text-gray-900 dark:text-white text-base sm:text-lg font-bold leading-tight tracking-[-0.015em]">{{ $title ?? 'Administrator Dashboard' }}</h2>
                            </div>
                            <div class="flex flex-1 justify-end gap-2 sm:gap-4">
                                <label class="hidden sm:flex flex-col min-w-40 !h-10 max-w-64">
                                    <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                                        <div class="text-gray-500 dark:text-text-muted-dark flex bg-gray-100 dark:bg-surface-dark items-center justify-center pl-4 rounded-l-lg">
                                            <span class="material-symbols-outlined">search</span>
                                        </div>
                                        <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-0 border-none bg-gray-100 dark:bg-surface-dark h-full placeholder:text-gray-500 dark:placeholder:text-text-muted-dark px-4 rounded-l-none pl-2 text-sm sm:text-base font-normal leading-normal" placeholder="Search..." type="search" id="admin-search" />
                                    </div>
                                </label>
                                <div class="flex gap-2">
                                    <button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 w-10 bg-gray-100 dark:bg-surface-dark text-gray-700 dark:text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 hover:bg-gray-200 dark:hover:bg-surface-dark/80 transition">
                                        <span class="material-symbols-outlined text-lg sm:text-xl">notifications</span>
                                    </button>
                                    <button class="hidden sm:flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 w-10 bg-gray-100 dark:bg-surface-dark text-gray-700 dark:text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 hover:bg-gray-200 dark:hover:bg-surface-dark/80 transition">
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

                    <!-- Mobile Menu Button for Non-Admin -->
                    @if(!$isAdmin)
                        <div class="lg:hidden sticky top-0 z-20 flex items-center px-4 sm:px-6 py-3 bg-white dark:bg-background-dark border-b border-gray-200 dark:border-[#324d67]">
                            <button @click.prevent="sidebarOpen = !sidebarOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 focus:outline-none focus:ring-2 focus:ring-primary">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path x-show="!sidebarOpen" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" style="display: none;"></path>
                                    <path x-show="sidebarOpen" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;"></path>
                                </svg>
                            </button>
                            <h2 class="ml-3 text-gray-900 dark:text-white text-base sm:text-lg font-bold leading-tight">{{ $title ?? 'Dashboard' }}</h2>
                        </div>
                    @endif

                    <!-- Main Content -->
                    <main class="flex-1 {{ $isAdmin ? 'p-4 sm:p-6 lg:p-8' : 'p-4 sm:p-6 lg:p-10' }} overflow-y-auto">
                        <div class="{{ $isAdmin ? 'mx-auto max-w-7xl space-y-6 sm:space-y-8' : 'w-full max-w-7xl mx-auto' }}">
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
