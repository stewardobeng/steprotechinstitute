<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title . ' - ' : '' }}{{ config('app.name', 'Laravel') }}</title>

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
        <div class="relative flex h-auto min-h-screen w-full flex-col">
            <div class="flex h-full w-full">
                <!-- SideNavBar -->
                <aside class="flex w-64 flex-col border-r border-border-dark bg-surface-dark/30 p-4">
                    <div class="flex flex-col gap-4">
                        <!-- User Profile -->
                        <div class="flex items-center gap-3">
                            @php
                                use Illuminate\Support\Facades\Storage;
                            @endphp
                            @if(auth()->user()->profile_image)
                                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border-2 border-primary/20 dark:border-primary/30" style="background-image: url('{{ asset('storage/' . auth()->user()->profile_image) }}');"></div>
                            @else
                                <div class="bg-primary/10 dark:bg-primary/20 aspect-square rounded-full size-10 flex items-center justify-center border-2 border-primary/20 dark:border-primary/30">
                                    <span class="text-primary text-base font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div class="flex flex-col">
                                <h1 class="text-gray-900 dark:text-white text-base font-medium leading-normal">{{ auth()->user()->name }}</h1>
                                <p class="text-gray-600 dark:text-text-muted-dark text-sm font-normal leading-normal">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <nav class="flex flex-col gap-2 mt-4">
                            <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary/20 text-primary' : 'text-white/80 hover:bg-white/5' }}" href="{{ route('admin.dashboard') }}">
                                <span class="material-symbols-outlined">dashboard</span>
                                <p class="text-sm font-medium leading-normal">Dashboard</p>
                            </a>
                            <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.affiliate-agents.*') ? 'bg-primary/20 text-primary' : 'text-white/80 hover:bg-white/5' }}" href="{{ route('admin.affiliate-agents.index') }}">
                                <span class="material-symbols-outlined">group</span>
                                <p class="text-sm font-medium leading-normal">Affiliates</p>
                            </a>
                            <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.students.*') ? 'bg-primary/20 text-primary' : 'text-white/80 hover:bg-white/5' }}" href="{{ route('admin.students.index') }}">
                                <span class="material-symbols-outlined">school</span>
                                <p class="text-sm font-medium leading-normal">Students</p>
                            </a>
                            <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.invite-codes.*') ? 'bg-primary/20 text-primary' : 'text-white/80 hover:bg-white/5' }}" href="{{ route('admin.invite-codes.index') }}">
                                <span class="material-symbols-outlined">key</span>
                                <p class="text-sm font-medium leading-normal">Invite Codes</p>
                            </a>
                            <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.withdrawals.*') ? 'bg-primary/20 text-primary' : 'text-white/80 hover:bg-white/5' }}" href="{{ route('admin.withdrawals.index') }}">
                                <span class="material-symbols-outlined">credit_card</span>
                                <p class="text-sm font-medium leading-normal">Payments</p>
                            </a>
                        </nav>
                    </div>

                    <div class="mt-auto flex flex-col gap-1">
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'bg-primary/20 text-primary' : 'text-white/80 hover:bg-white/5' }}" href="{{ route('admin.settings.index') }}">
                            <span class="material-symbols-outlined">settings</span>
                            <p class="text-sm font-medium leading-normal">Settings</p>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-3 py-2 rounded-lg text-white/80 hover:bg-white/5 w-full text-left">
                                <span class="material-symbols-outlined">logout</span>
                                <p class="text-sm font-medium leading-normal">Logout</p>
                            </button>
                        </form>
                    </div>
                </aside>

                <div class="flex flex-1 flex-col">
                    <!-- TopNavBar -->
                    <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-border-dark px-10 py-3 bg-surface-dark/30">
                        <div class="flex items-center gap-4 text-white">
                            <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">{{ $title ?? 'Administrator Dashboard' }}</h2>
                        </div>
                        <div class="flex flex-1 justify-end gap-4">
                            <label class="flex flex-col min-w-40 !h-10 max-w-64">
                                <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                                    <div class="text-text-muted-dark flex bg-surface-dark items-center justify-center pl-4 rounded-l-lg">
                                        <span class="material-symbols-outlined">search</span>
                                    </div>
                                    <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-white focus:outline-0 focus:ring-0 border-none bg-surface-dark h-full placeholder:text-text-muted-dark px-4 rounded-l-none pl-2 text-base font-normal leading-normal" placeholder="Search..." type="search" id="admin-search" />
                                </div>
                            </label>
                            <div class="flex gap-2">
                                <button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 w-10 bg-surface-dark text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 hover:bg-surface-dark/80">
                                    <span class="material-symbols-outlined text-xl">notifications</span>
                                </button>
                                <button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 w-10 bg-surface-dark text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 hover:bg-surface-dark/80">
                                    <span class="material-symbols-outlined text-xl">help</span>
                                </button>
                            </div>
                            @if(auth()->user()->profile_image)
                                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border-2 border-primary/20 dark:border-primary/30" style="background-image: url('{{ asset('storage/' . auth()->user()->profile_image) }}');"></div>
                            @else
                                <div class="bg-primary/10 dark:bg-primary/20 aspect-square rounded-full size-10 flex items-center justify-center border-2 border-primary/20 dark:border-primary/30">
                                    <span class="text-primary text-base font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                    </header>

                    <!-- Main Content -->
                    <main class="flex-1 overflow-y-auto p-8">
                        <div class="mx-auto max-w-7xl space-y-8">
                            @if(session('success'))
                                <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if(session('status'))
                                <div class="bg-blue-100 dark:bg-blue-900/30 border border-blue-400 dark:border-blue-800 text-blue-700 dark:text-blue-400 px-4 py-3 rounded-lg">
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

