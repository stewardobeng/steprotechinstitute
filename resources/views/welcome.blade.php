<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @php
            $appName = \App\Models\Setting::getValue('app_name', config('app.name', 'StepProClass'));
            $whatsappNumber = \App\Models\Setting::getValue('whatsapp_number', env('WHATSAPP_NUMBER', '233244775129'));
            // Clean phone number for WhatsApp link (remove non-numeric characters)
            $whatsappNumber = preg_replace('/[^0-9]/', '', (string) $whatsappNumber);
            if (empty($whatsappNumber) || strlen($whatsappNumber) < 12) {
                $whatsappNumber = '233244775129';
            }
        @endphp
    <title>AI Literacy Professional Certification Program | {{ $appName }}</title>
    <meta name="description" content="Join our 5-day intensive AI Literacy Professional Certification Program. Online live sessions with recordings available. Get certified today!">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Scripts -->
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    
            <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease;
        }
        .faq-item.active .faq-content {
            max-height: 1000px;
            opacity: 1 !important;
        }
        
        .testimonial-card {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }
        .testimonial-card.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Optimize Material Symbols icon rendering */
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-family: 'Material Symbols Outlined';
            font-style: normal;
            font-weight: 400;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }
        
        /* Hide icon text initially - will be visible once font loads and replaces text with icon */
        html:not(.fonts-loaded) .material-symbols-outlined {
            opacity: 0;
            visibility: hidden;
        }
        
        /* Show icons once font is loaded */
        html.fonts-loaded .material-symbols-outlined {
            opacity: 1;
            visibility: visible;
            transition: opacity 0.2s ease;
        }
        
        /* Fallback: show icons after short delay */
        @keyframes showIcons {
            to {
                opacity: 1;
                visibility: visible;
            }
        }
        
        .material-symbols-outlined {
            animation: showIcons 0s 0.3s forwards;
        }
            </style>
    </head>
<body class="bg-background-light dark:bg-background-dark font-display">
    <!-- Navigation -->
    <nav x-data="{ open: false }" class="fixed top-0 w-full z-50 bg-white/80 dark:bg-surface-dark/30 backdrop-blur-sm border-b border-gray-200 dark:border-border-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $appName }}
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-4">
                    <a href="#about" class="text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white transition px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5">About</a>
                    <a href="#curriculum" class="text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white transition px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5">Curriculum</a>
                    <a href="#testimonials" class="text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white transition px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5">Testimonials</a>
                    <a href="#faq" class="text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white transition px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5">FAQ</a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white transition px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">Register Now</a>
                    @endauth
                </div>
                <!-- Hamburger Menu Button -->
                <button @click="open = !open" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-white/20">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="md:hidden bg-white dark:bg-surface-dark/95 backdrop-blur-sm border-t border-gray-200 dark:border-border-dark"
             style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="#about" @click="open = false" class="block text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition px-3 py-2 rounded-lg">About</a>
                <a href="#curriculum" @click="open = false" class="block text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition px-3 py-2 rounded-lg">Curriculum</a>
                <a href="#testimonials" @click="open = false" class="block text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition px-3 py-2 rounded-lg">Testimonials</a>
                <a href="#faq" @click="open = false" class="block text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition px-3 py-2 rounded-lg">FAQ</a>
                <div class="pt-2 border-t border-gray-200 dark:border-white/10">
                    @auth
                        <a href="{{ url('/dashboard') }}" @click="open = false" class="block px-3 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition text-center">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" @click="open = false" class="block text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition px-3 py-2 rounded-lg">Login</a>
                        <a href="{{ route('register') }}" @click="open = false" class="block px-3 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition text-center mt-2">Register Now</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20 sm:pt-24 bg-background-light dark:bg-background-dark">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-100/30 via-blue-100/30 to-purple-100/30 dark:from-purple-900/20 dark:via-blue-900/20 dark:to-purple-900/20 pointer-events-none"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-12 sm:py-16 lg:py-24 xl:py-32">
            <div class="grid lg:grid-cols-2 gap-8 sm:gap-12 lg:gap-16 items-center">
                <div class="animate-fadeInUp">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500/10 to-blue-500/10 border border-purple-500/20 dark:border-purple-500/20 rounded-full mb-8 backdrop-blur-sm">
                        <span class="flex h-2 w-2 rounded-full bg-purple-400"></span>
                        <span class="text-purple-600 dark:text-purple-200 font-medium text-sm tracking-wide">Professional Certification Program</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-extrabold mb-6 sm:mb-8 text-gray-900 dark:text-white leading-[1.1] tracking-tight">
                        Master AI Literacy in <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400">Just 5 Days</span>
                    </h1>
                    <p class="text-base sm:text-lg lg:text-xl text-gray-600 dark:text-gray-400 mb-6 sm:mb-8 lg:mb-10 leading-relaxed max-w-2xl">
                        Join our intensive, hands-on training program designed for professionals. Gain practical skills, attend live sessions, and earn a recognized certification to future-proof your career.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 mb-8 sm:mb-10 lg:mb-12">
                        <a href="{{ route('register') }}" class="group px-6 sm:px-8 py-3 sm:py-4 bg-primary text-white rounded-xl font-semibold text-base sm:text-lg hover:bg-primary/90 transition-all duration-300 shadow-lg shadow-primary/25 flex items-center justify-center gap-3">
                            <span>Enroll Now</span>
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </a>
                        <a href="#curriculum" class="px-6 sm:px-8 py-3 sm:py-4 bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white rounded-xl font-semibold text-base sm:text-lg border border-gray-300 dark:border-white/10 hover:bg-gray-200 dark:hover:bg-white/10 transition-all duration-300 flex items-center justify-center gap-2 backdrop-blur-sm">
                            <span class="material-symbols-outlined">menu_book</span>
                            <span>View Curriculum</span>
                        </a>
                    </div>
                    <div class="flex flex-wrap items-center gap-y-3 sm:gap-y-4 gap-x-4 sm:gap-x-8 text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">
                        <div class="flex items-center gap-2.5">
                            <div class="p-1.5 rounded-full bg-primary/10 text-primary">
                                <span class="material-symbols-outlined text-[20px]">schedule</span>
                            </div>
                            <span>5 Days Intensive</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="p-1.5 rounded-full bg-primary/10 text-primary">
                                <span class="material-symbols-outlined text-[20px]">videocam</span>
                            </div>
                            <span>Live Online Sessions</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="p-1.5 rounded-full bg-primary/10 text-primary">
                                <span class="material-symbols-outlined text-[20px]">verified</span>
                            </div>
                            <span>Certified Program</span>
                        </div>
                    </div>
                </div>
                <div class="relative animate-fadeInUp hidden lg:block" style="animation-delay: 0.2s">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-tr from-purple-600/30 to-blue-600/30 rounded-[2rem] blur-3xl"></div>
                        <div class="relative bg-white/80 dark:bg-surface-dark/80 backdrop-blur-xl rounded-[2rem] p-10 border border-gray-200 dark:border-white/10 shadow-2xl">
                            <div class="aspect-[4/3] bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800/50 dark:to-gray-900/50 rounded-2xl flex items-center justify-center mb-8 border border-gray-300 dark:border-white/5 relative overflow-hidden group">
                                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1677442136019-21780ecad995?q=80&w=2832&auto=format&fit=crop')] bg-cover bg-center opacity-40 group-hover:scale-105 transition-transform duration-700"></div>
                                <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-surface-dark via-transparent to-transparent"></div>
                                <div class="text-center relative z-10">
                                    <div class="h-20 w-20 bg-white/80 dark:bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-4 border border-gray-300 dark:border-white/20 shadow-xl animate-float">
                                        <span class="text-4xl">🤖</span>
                                    </div>
                                    <div class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">AI Literacy</div>
                                    <div class="text-purple-600 dark:text-purple-300 font-medium mt-1">Certification</div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/5 rounded-xl hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                    <div class="h-10 w-10 rounded-lg bg-purple-500/20 flex items-center justify-center text-purple-400">
                                        <span class="material-symbols-outlined">school</span>
                                    </div>
                                    <div>
                                        <div class="text-gray-900 dark:text-white font-semibold">Industry Recognized</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Boost your professional profile</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/5 rounded-xl hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                    <div class="h-10 w-10 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400">
                                        <span class="material-symbols-outlined">groups</span>
                                    </div>
                                    <div>
                                        <div class="text-gray-900 dark:text-white font-semibold">Expert Led Training</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Learn from seasoned professionals</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="about" class="py-12 sm:py-16 lg:py-24 bg-background-light dark:bg-background-dark relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-white/10 to-transparent"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
            <div class="text-center mb-12 sm:mb-16 lg:mb-20 max-w-3xl mx-auto">
                <h2 class="text-gray-900 dark:text-white text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 sm:mb-6 tracking-tight">Why Choose Our Program?</h2>
                <p class="text-gray-600 dark:text-text-muted-dark text-base sm:text-lg leading-relaxed px-4">We provide a comprehensive learning experience that combines theory with practical application, ensuring you're ready for the AI-driven future.</p>
            </div>
            <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Feature Cards with refined styling -->
                <div class="group p-6 sm:p-8 rounded-2xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/5 hover:border-purple-500/30 hover:bg-purple-50 dark:hover:bg-purple-500/5 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 sm:p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                        <span class="material-symbols-outlined text-6xl sm:text-8xl">school</span>
                    </div>
                    <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400 mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-xl sm:text-2xl">school</span>
                    </div>
                    <h3 class="text-gray-900 dark:text-white text-lg sm:text-xl font-bold mb-2 sm:mb-3">Professional Certification</h3>
                    <p class="text-gray-600 dark:text-text-muted-dark text-sm sm:text-base leading-relaxed">Earn a recognized AI Literacy Professional certificate upon completion to validate your expertise.</p>
                </div>

                <div class="group p-8 rounded-2xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/5 hover:border-blue-500/30 hover:bg-blue-50 dark:hover:bg-blue-500/5 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                        <span class="material-symbols-outlined text-8xl">videocam</span>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-2xl">videocam</span>
                    </div>
                    <h3 class="text-gray-900 dark:text-white text-xl font-bold mb-3">Live Online Sessions</h3>
                    <p class="text-gray-600 dark:text-text-muted-dark leading-relaxed">Interactive live sessions with expert instructors, real-time Q&A, and collaborative learning.</p>
                </div>

                <div class="group p-8 rounded-2xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/5 hover:border-green-500/30 hover:bg-green-50 dark:hover:bg-green-500/5 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                        <span class="material-symbols-outlined text-8xl">video_library</span>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-green-500/10 flex items-center justify-center text-green-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-2xl">video_library</span>
                    </div>
                    <h3 class="text-gray-900 dark:text-white text-xl font-bold mb-3">Lifetime Recordings</h3>
                    <p class="text-gray-600 dark:text-text-muted-dark leading-relaxed">Never miss a beat with lifetime access to all session recordings and learning materials.</p>
                </div>

                <div class="group p-8 rounded-2xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/5 hover:border-yellow-500/30 hover:bg-yellow-50 dark:hover:bg-yellow-500/5 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                        <span class="material-symbols-outlined text-8xl">bolt</span>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-yellow-500/10 flex items-center justify-center text-yellow-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-2xl">bolt</span>
                    </div>
                    <h3 class="text-gray-900 dark:text-white text-xl font-bold mb-3">5-Day Intensive</h3>
                    <p class="text-gray-600 dark:text-text-muted-dark leading-relaxed">Fast-track your AI knowledge with our comprehensive, structured 5-day program.</p>
                </div>

                <div class="group p-8 rounded-2xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/5 hover:border-cyan-500/30 hover:bg-cyan-50 dark:hover:bg-cyan-500/5 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                        <span class="material-symbols-outlined text-8xl">groups</span>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-cyan-500/10 flex items-center justify-center text-cyan-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-2xl">groups</span>
                    </div>
                    <h3 class="text-gray-900 dark:text-white text-xl font-bold mb-3">Expert Instructors</h3>
                    <p class="text-gray-600 dark:text-text-muted-dark leading-relaxed">Learn directly from industry experts with years of practical AI implementation experience.</p>
                </div>

                <div class="group p-8 rounded-2xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/5 hover:border-orange-500/30 hover:bg-orange-50 dark:hover:bg-orange-500/5 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                        <span class="material-symbols-outlined text-8xl">target</span>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-2xl">target</span>
                    </div>
                    <h3 class="text-gray-900 dark:text-white text-xl font-bold mb-3">Hands-on Projects</h3>
                    <p class="text-gray-600 dark:text-text-muted-dark leading-relaxed">Apply your knowledge immediately with real-world AI projects and practical case studies.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Curriculum Section -->
    <section id="curriculum" class="py-12 sm:py-16 lg:py-24 bg-background-light dark:bg-background-dark border-t border-gray-200 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
            <div class="text-center mb-12 sm:mb-16 lg:mb-20 max-w-3xl mx-auto">
                <h2 class="text-gray-900 dark:text-white text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 sm:mb-6 tracking-tight">5-Day Curriculum</h2>
                <p class="text-gray-600 dark:text-text-muted-dark text-base sm:text-lg leading-relaxed px-4">A structured learning path designed to take you from fundamentals to advanced application.</p>
            </div>
            <div class="space-y-4 sm:space-y-6 max-w-5xl mx-auto">
                @foreach([
                    ['day' => 'Day 1', 'title' => 'Fundamentals of Artificial Intelligence', 'topics' => ['Definition of AI and some Key Termininologies', 'Practical Use of Artificial Intelligence', 'Several Ways of Accessing AI', 'Advantages and Disadvantages of AI'], 'color' => 'blue', 'icon' => 'lightbulb'],
                    ['day' => 'Day 2', 'title' => 'Effective Use of LLM Chatbots', 'topics' => ['Identifying the Best LLMs', 'Overview of Frontier LLM Chatbots', 'The AI Tools Ecosystem', 'Hands-on Practice'], 'color' => 'purple', 'icon' => 'hub'],
                    ['day' => 'Day 3', 'title' => 'Artificial Intelligence for Productivity', 'topics' => ['Using AI to boost Productivity', 'AI Resources for Professionals', 'Effective Prompting Techniques', 'Guiding Principles of Professional AI Usage'], 'color' => 'green', 'icon' => 'chat'],
                    ['day' => 'Day 4', 'title' => 'Artificial Intelligence for Content Creation', 'topics' => ['Using AI to Generate Written Contents', 'Using AI to Generate Images/Designs', 'Using AI to Generate Songs/Sound Effects', 'Using AI to Generate Videos/Animations'], 'color' => 'cyan', 'icon' => 'image'],
                    ['day' => 'Day 5', 'title' => 'Artificial Intelligence for Coding', 'topics' => ['Preparing Your Coding Environments', 'Understanding and Building Project Structure and PRD', 'Building Complete Websites/Webapps with AI', 'Editing and Debugging AI Generated Codes'], 'color' => 'yellow', 'icon' => 'verified']
                ] as $index => $day)
                <div class="group flex flex-col md:flex-row gap-4 sm:gap-6 lg:gap-8 p-6 sm:p-8 rounded-2xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/5 hover:border-{{ $day['color'] }}-500/30 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-{{ $day['color'] }}-500 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <div class="flex-shrink-0 flex md:flex-col items-center gap-3 sm:gap-4 md:w-32">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-{{ $day['color'] }}-500/10 flex items-center justify-center text-{{ $day['color'] }}-400 group-hover:scale-110 transition-transform duration-300">
                            <span class="material-symbols-outlined text-2xl sm:text-3xl">{{ $day['icon'] }}</span>
                        </div>
                        <div class="text-{{ $day['color'] }}-400 font-bold text-base sm:text-lg tracking-wide">{{ $day['day'] }}</div>
                    </div>
                    
                    <div class="flex-1">
                        <h3 class="text-gray-900 dark:text-white text-xl sm:text-2xl font-bold mb-4 sm:mb-6 group-hover:text-{{ $day['color'] }}-400 transition-colors">{{ $day['title'] }}</h3>
                        <div class="grid sm:grid-cols-2 gap-3 sm:gap-4">
                            @foreach($day['topics'] as $topic)
                            <div class="flex items-center gap-3 text-gray-600 dark:text-text-muted-dark group-hover:text-gray-900 dark:group-hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-{{ $day['color'] }}-400/70 text-xl">check_circle</span>
                                <span>{{ $topic }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-12 sm:py-16 lg:py-24 bg-background-light dark:bg-background-dark border-t border-gray-200 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
            <div class="text-center mb-12 sm:mb-16 lg:mb-20 max-w-3xl mx-auto">
                <h2 class="text-gray-900 dark:text-white text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 sm:mb-6 tracking-tight">Success Stories</h2>
                <p class="text-gray-600 dark:text-text-muted-dark text-base sm:text-lg leading-relaxed px-4">Join hundreds of professionals who have transformed their careers through our program.</p>
            </div>
            <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach([
                    ['name' => 'Sarah Johnson', 'role' => 'Data Scientist', 'text' => 'This program transformed my understanding of AI. The live sessions were engaging, and having access to recordings helped me review complex topics. Highly recommended!'],
                    ['name' => 'Michael Chen', 'role' => 'Software Engineer', 'text' => 'The 5-day intensive format was perfect for my schedule. The instructors were knowledgeable, and the hands-on projects were incredibly valuable. Worth every penny!'],
                    ['name' => 'Emily Rodriguez', 'role' => 'Business Analyst', 'text' => 'As someone new to AI, I was worried it would be too technical. But the program breaks everything down perfectly. I now feel confident discussing AI in my role.'],
                    ['name' => 'David Kim', 'role' => 'Product Manager', 'text' => 'The certification has opened doors for me. The curriculum is comprehensive, and the lifetime access to recordings means I can always refer back. Excellent investment!'],
                    ['name' => 'Lisa Thompson', 'role' => 'Marketing Director', 'text' => 'I loved the interactive live sessions. Being able to ask questions in real-time made all the difference. The recordings are a lifesaver for busy professionals.'],
                    ['name' => 'James Wilson', 'role' => 'Entrepreneur', 'text' => 'This program gave me the AI literacy I needed to make informed decisions for my startup. The practical focus and expert guidance were exactly what I needed.']
                ] as $index => $testimonial)
                <div class="testimonial-card flex flex-col gap-4 sm:gap-6 p-6 sm:p-8 rounded-2xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/5 hover:border-gray-300 dark:hover:border-white/10 transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 p-[2px] flex-shrink-0">
                            <div class="h-full w-full rounded-full bg-white dark:bg-surface-dark flex items-center justify-center">
                                <span class="text-gray-900 dark:text-white font-bold text-base sm:text-lg">{{ strtoupper(substr($testimonial['name'], 0, 1)) }}</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-gray-900 dark:text-white font-bold text-base sm:text-lg">{{ $testimonial['name'] }}</h4>
                            <p class="text-gray-600 dark:text-text-muted-dark text-xs sm:text-sm">{{ $testimonial['role'] }}</p>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        @for($i = 0; $i < 5; $i++)
                        <span class="material-symbols-outlined text-yellow-400 text-lg sm:text-xl fill-current">star</span>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base leading-relaxed italic">"{{ $testimonial['text'] }}"</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-12 sm:py-16 lg:py-24 bg-background-light dark:bg-background-dark border-t border-gray-200 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
            <div class="text-center mb-12 sm:mb-16 lg:mb-20 max-w-3xl mx-auto">
                <h2 class="text-gray-900 dark:text-white text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 sm:mb-6 tracking-tight">Frequently Asked Questions</h2>
                <p class="text-gray-600 dark:text-text-muted-dark text-base sm:text-lg leading-relaxed px-4">Everything you need to know about our program and what to expect.</p>
            </div>
            <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 max-w-6xl mx-auto">
                @foreach([
                    ['q' => 'What is the cost of the program?', 'a' => 'The AI Literacy Professional Certification Program is priced at ₵150. This includes all 5 days of intensive training, live online sessions, lifetime access to recordings, course materials, and your professional certification upon completion.'],
                    ['q' => 'How long is the program?', 'a' => 'The program is a 5-day intensive training course. Each day consists of live online sessions with expert instructors, hands-on projects, and interactive Q&A sessions. The exact schedule will be provided upon enrollment.'],
                    ['q' => 'Are the classes online or in-person?', 'a' => 'All classes are conducted online via live sessions. This allows you to participate from anywhere in the world. You\'ll join interactive sessions with instructors and fellow students in real-time.'],
                    ['q' => 'What if I miss a live session?', 'a' => 'No worries! All live sessions are recorded, and you\'ll have lifetime access to these recordings. You can watch them at your convenience, pause, rewind, and review any part of the training as many times as you need.'],
                    ['q' => 'Will I have access to recordings?', 'a' => 'Yes! You\'ll have lifetime access to all session recordings. This means you can review the material anytime, even after completing the program. The recordings are yours to keep forever.'],
                    ['q' => 'What will I receive upon completion?', 'a' => 'Upon successful completion of the program and final assessment, you\'ll receive a Certified AI Literacy Professional certificate. This certificate demonstrates your expertise in AI fundamentals and can enhance your professional profile.'],
                    ['q' => 'Do I need prior AI experience?', 'a' => 'No prior AI experience is required! Our program is designed for beginners and professionals alike. We start with the fundamentals and gradually build up to more advanced concepts, ensuring everyone can follow along.'],
                    ['q' => 'What materials or software do I need?', 'a' => 'You\'ll need a computer with internet connection to join the live sessions. We\'ll provide guidance on any free software or tools needed during the program. All course materials and resources will be provided digitally.']
                ] as $index => $faq)
                <div class="faq-item h-fit rounded-2xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/5 hover:border-gray-300 dark:hover:border-white/10 transition-all duration-300 overflow-hidden group">
                    <button class="w-full flex justify-between items-start sm:items-center p-4 sm:p-6 text-left focus:outline-none gap-3" onclick="toggleFaq({{ $index }})">
                        <h3 class="text-gray-900 dark:text-white text-sm sm:text-base font-semibold pr-2 sm:pr-8 group-hover:text-primary transition-colors flex-1">{{ $faq['q'] }}</h3>
                        <span class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center text-primary transition-all duration-300 group-hover:bg-primary/10" id="faq-icon-wrapper-{{ $index }}">
                            <span class="material-symbols-outlined text-xl sm:text-2xl" id="faq-icon-{{ $index }}">add</span>
                            </span>
                    </button>
                    <div class="faq-content px-4 sm:px-6 pb-0 text-gray-600 dark:text-text-muted-dark text-sm sm:text-base leading-relaxed opacity-0 transition-all duration-300">
                        <div class="pb-4 sm:pb-6 border-t border-gray-200 dark:border-white/5 pt-3 sm:pt-4">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-16 lg:py-24 bg-gradient-to-b from-gray-50 to-white dark:from-surface-dark dark:to-background-dark border-t border-gray-200 dark:border-white/5 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-purple-500/5 to-blue-500/5 pointer-events-none"></div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-12 text-center relative z-10">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-bold mb-4 sm:mb-6 text-gray-900 dark:text-white tracking-tight">Ready to Become an AI Professional?</h2>
            <p class="text-gray-600 dark:text-text-muted-dark text-base sm:text-lg lg:text-xl mb-6 sm:mb-8 lg:mb-10 max-w-2xl mx-auto px-4">Join hundreds of professionals who have transformed their careers with our AI Literacy Certification Program</p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8 sm:mb-10 lg:mb-12">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-primary text-white rounded-xl font-semibold text-base sm:text-lg hover:bg-primary/90 transition-all duration-300 shadow-lg shadow-primary/25 flex items-center justify-center gap-3 group">
                    <span>Enroll Now - Only ₵150</span>
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">
                <div class="flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-green-400">check_circle</span>
                    <span>5-Day Intensive Training</span>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-green-400">check_circle</span>
                    <span>Live Online Sessions</span>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-green-400">check_circle</span>
                    <span>Lifetime Recordings</span>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-green-400">check_circle</span>
                    <span>Professional Certification</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white dark:bg-surface-dark border-t border-gray-200 dark:border-border-dark py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
                <div>
                    <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-3 sm:mb-4">{{ $appName }}</div>
                    <p class="text-gray-600 dark:text-text-muted-dark text-xs sm:text-sm">Empowering professionals with AI literacy through comprehensive training and certification.</p>
                </div>
                <div>
                    <h4 class="text-gray-900 dark:text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#about" class="text-gray-600 dark:text-text-muted-dark hover:text-gray-900 dark:hover:text-white transition">About</a></li>
                        <li><a href="#curriculum" class="text-gray-600 dark:text-text-muted-dark hover:text-gray-900 dark:hover:text-white transition">Curriculum</a></li>
                        <li><a href="#testimonials" class="text-gray-600 dark:text-text-muted-dark hover:text-gray-900 dark:hover:text-white transition">Testimonials</a></li>
                        <li><a href="#faq" class="text-gray-600 dark:text-text-muted-dark hover:text-gray-900 dark:hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-gray-900 dark:text-white font-semibold mb-4">Program</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('register') }}" class="text-gray-600 dark:text-text-muted-dark hover:text-gray-900 dark:hover:text-white transition">Register</a></li>
                        <li><a href="{{ route('register.affiliate') }}" class="text-gray-600 dark:text-text-muted-dark hover:text-gray-900 dark:hover:text-white transition">Register as an Affiliate</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-600 dark:text-text-muted-dark hover:text-gray-900 dark:hover:text-white transition">Login</a></li>
                        @auth
                        <li><a href="{{ url('/dashboard') }}" class="text-gray-600 dark:text-text-muted-dark hover:text-gray-900 dark:hover:text-white transition">Dashboard</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 class="text-gray-900 dark:text-white font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-text-muted-dark">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">email</span>
                            <span>info@steprotech.com</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">phone</span>
                            <span>+233 24 477 5129</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-200 dark:border-border-dark mt-6 sm:mt-8 pt-6 sm:pt-8 text-center text-xs sm:text-sm text-gray-600 dark:text-text-muted-dark">
                <p>&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
                </div>
        </div>
    </footer>

    <script>
        // Font loading detection
        (function() {
            // Check if Material Symbols font is loaded
            if (document.fonts && document.fonts.check) {
                const checkFont = () => {
                    if (document.fonts.check('1em Material Symbols Outlined')) {
                        document.documentElement.classList.add('fonts-loaded');
                    } else {
                        setTimeout(checkFont, 50);
                    }
                };
                checkFont();
            } else {
                // Fallback: assume font is loaded after a short delay
                setTimeout(() => {
                    document.documentElement.classList.add('fonts-loaded');
                }, 100);
            }
            
            // Also check when fonts are ready
            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(() => {
                    document.documentElement.classList.add('fonts-loaded');
                });
            }
        })();
        
        // FAQ Toggle
        function toggleFaq(index) {
            const item = document.querySelectorAll('.faq-item')[index];
            const icon = document.getElementById(`faq-icon-${index}`);
            const content = item.querySelector('.faq-content');
            
            if (item.classList.contains('active')) {
                item.classList.remove('active');
                icon.textContent = 'add';
                icon.style.transform = 'rotate(0deg)';
                content.style.opacity = '0';
                content.style.maxHeight = '0';
            } else {
                // Close all other FAQs
                document.querySelectorAll('.faq-item').forEach((el, i) => {
                    if (i !== index) {
                        el.classList.remove('active');
                        const otherIcon = document.getElementById(`faq-icon-${i}`);
                        const otherContent = el.querySelector('.faq-content');
                        if (otherIcon) {
                            otherIcon.textContent = 'add';
                            otherIcon.style.transform = 'rotate(0deg)';
                        }
                        if (otherContent) {
                            otherContent.style.opacity = '0';
                            otherContent.style.maxHeight = '0';
                        }
                    }
                });
                
                item.classList.add('active');
                icon.textContent = 'remove';
                icon.style.transform = 'rotate(180deg)';
                content.style.opacity = '1';
                content.style.maxHeight = '1000px';
            }
        }

        // Testimonial Animation on Scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const testimonialObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, index * 100);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.testimonial-card').forEach(card => {
            testimonialObserver.observe(card);
        });

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-6 left-6 z-50">
        <!-- Theme Toggle Button -->
        <button 
            onclick="window.toggleTheme()"
            class="w-14 h-14 rounded-full bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center border border-gray-200 dark:border-gray-700 hover:scale-110 group"
            title="Toggle Theme"
            aria-label="Toggle Theme"
        >
            <span class="material-symbols-outlined text-gray-700 dark:text-yellow-400 text-2xl group-hover:rotate-180 transition-transform duration-300 opacity-100" id="theme-icon" style="opacity: 1 !important; visibility: visible !important;">dark_mode</span>
        </button>
    </div>

    <script>
        // Update theme icon on page load and after toggle
        function updateThemeIcon() {
            const isDark = document.documentElement.classList.contains('dark');
            const themeIcon = document.getElementById('theme-icon');
            if (themeIcon) {
                // Set icon text directly (not wrapped in span)
                themeIcon.textContent = isDark ? 'light_mode' : 'dark_mode';
                // Ensure icon is always visible
                themeIcon.style.opacity = '1';
                themeIcon.style.visibility = 'visible';
            }
        }
        
        // Update icon on page load
        updateThemeIcon();
        
        // Override toggleTheme to update icon
        const originalToggleTheme = window.toggleTheme;
        window.toggleTheme = function() {
            const result = originalToggleTheme();
            setTimeout(updateThemeIcon, 100);
            return result;
        };
        
        // Also update icon when theme changes via other means
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    updateThemeIcon();
                }
            });
        });
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    </script>

    <!-- Rybbit Analytics -->
    <script
        src="https://app.rybbit.io/api/script.js"
        data-site-id="2239bf4300e7"
        defer
    ></script>
    </body>
</html>
