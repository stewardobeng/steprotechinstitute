<x-app-layout>
    <x-slot name="title">Classroom Access</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center">
        <h1 class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Classroom Access</h1>
    </div>

    <div class="mt-8 grid gap-6">
        <!-- Instructions Card -->
        <div class="flex flex-col rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] p-6">
            <h2 class="text-gray-900 dark:text-white text-2xl font-bold leading-tight mb-4">How to Access the Online Classroom</h2>
            
            <div class="space-y-6">
                <!-- Step 1: Download App -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold">1</div>
                    <div class="flex-1">
                        <h3 class="text-gray-900 dark:text-white text-lg font-semibold mb-2">Download the TeachMint App</h3>
                        <p class="text-gray-600 dark:text-[#92adc9] text-sm mb-3">Download the TeachMint app from your app store:</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="https://play.google.com/store/apps/details?id=com.teachmint.teachmint" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                <span class="material-symbols-outlined">phone_android</span>
                                <span class="text-gray-900 dark:text-white text-sm font-medium">Google Play Store</span>
                            </a>
                            <a href="https://apps.apple.com/gh/app/teachmint-connected-classroom/id1544210597" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                <span class="material-symbols-outlined">phone_iphone</span>
                                <span class="text-gray-900 dark:text-white text-sm font-medium">App Store</span>
                            </a>
                            <span class="text-gray-500 dark:text-gray-400 text-sm self-center">or</span>
                            <a href="https://teachmint.com" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                                <span class="material-symbols-outlined">language</span>
                                <span class="text-sm font-medium">Use Web Version</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Registration -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold">2</div>
                    <div class="flex-1">
                        <h3 class="text-gray-900 dark:text-white text-lg font-semibold mb-2">Register on TeachMint</h3>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 space-y-2">
                            <p class="text-blue-800 dark:text-blue-300 text-sm font-semibold">When prompted, enter the following:</p>
                            <ul class="space-y-2 text-sm text-blue-700 dark:text-blue-400">
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-lg">email</span>
                                    <span><strong>Email:</strong> Use the same email you used for registration: <code class="bg-blue-100 dark:bg-blue-900 px-1 rounded">{{ $registration->user->email }}</code></span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-lg">person</span>
                                    <span><strong>Role:</strong> Select <strong>"Student"</strong> when you see the role selection screen</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-lg">badge</span>
                                    <span><strong>Name:</strong> Enter your official name: <code class="bg-blue-100 dark:bg-blue-900 px-1 rounded">{{ $registration->user->name }}</code></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Classroom ID -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold">3</div>
                    <div class="flex-1">
                        <h3 class="text-gray-900 dark:text-white text-lg font-semibold mb-2">Enter Classroom ID</h3>
                        <p class="text-gray-600 dark:text-[#92adc9] text-sm mb-3">When prompted to enter a Classroom ID, use the ID below:</p>
                        
                        @if($classroomId)
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-[#324d67]">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <p class="text-gray-500 dark:text-[#92adc9] text-xs font-medium mb-1">Classroom ID</p>
                                        <p class="text-gray-900 dark:text-white text-lg font-mono font-bold" id="classroom-id">{{ $classroomId }}</p>
                                    </div>
                                    <button onclick="copyClassroomId()" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                                        <span class="material-symbols-outlined text-lg">content_copy</span>
                                        <span class="text-sm font-medium">Copy</span>
                                    </button>
                                </div>
                            </div>
                            <p class="text-gray-500 dark:text-[#92adc9] text-xs mt-2">Click the copy button above to copy the Classroom ID to your clipboard</p>
                        @else
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                <p class="text-yellow-800 dark:text-yellow-300 text-sm">Classroom ID has not been set yet. Please contact the administrator.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center font-bold text-gray-600 dark:text-gray-400">4</div>
                    <div class="flex-1">
                        <h3 class="text-gray-900 dark:text-white text-lg font-semibold mb-2">Your Status</h3>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $registration->classroom_approved ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400' }}">
                                {{ $registration->classroom_approved ? 'Approved' : 'Pending Approval' }}
                            </span>
                            @if(!$registration->classroom_approved)
                                <p class="text-gray-500 dark:text-[#92adc9] text-sm">Your classroom access is pending approval from the administrator.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links Card -->
        <div class="flex flex-col rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] p-6">
            <h3 class="text-gray-900 dark:text-white text-lg font-semibold mb-4">Quick Links</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="https://teachmint.com" target="_blank" class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <span class="material-symbols-outlined text-primary text-2xl">language</span>
                    <div>
                        <p class="text-gray-900 dark:text-white font-medium">TeachMint Web</p>
                        <p class="text-gray-500 dark:text-[#92adc9] text-xs">Access via browser</p>
                    </div>
                </a>
                <a href="https://play.google.com/store/apps/details?id=com.teachmint.teachmint" target="_blank" class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <span class="material-symbols-outlined text-primary text-2xl">phone_android</span>
                    <div>
                        <p class="text-gray-900 dark:text-white font-medium">Android App</p>
                        <p class="text-gray-500 dark:text-[#92adc9] text-xs">Download from Play Store</p>
                    </div>
                </a>
                <a href="https://apps.apple.com/gh/app/teachmint-connected-classroom/id1544210597" target="_blank" class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <span class="material-symbols-outlined text-primary text-2xl">phone_iphone</span>
                    <div>
                        <p class="text-gray-900 dark:text-white font-medium">iOS App</p>
                        <p class="text-gray-500 dark:text-[#92adc9] text-xs">Download from App Store</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyClassroomId() {
            const classroomId = document.getElementById('classroom-id').textContent;
            navigator.clipboard.writeText(classroomId).then(function() {
                // Show success message
                const button = event.target.closest('button');
                const originalText = button.innerHTML;
                button.innerHTML = '<span class="material-symbols-outlined text-lg">check</span><span class="text-sm font-medium">Copied!</span>';
                button.classList.add('bg-green-500');
                button.classList.remove('bg-primary', 'hover:bg-primary/90');
                
                setTimeout(function() {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-500');
                    button.classList.add('bg-primary', 'hover:bg-primary/90');
                }, 2000);
            }).catch(function(err) {
                alert('Failed to copy. Please manually copy: ' + classroomId);
            });
        }
    </script>
</x-app-layout>

