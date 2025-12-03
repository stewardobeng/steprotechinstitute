<x-app-layout>
    <x-slot name="title">Administrator Dashboard</x-slot>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-blue-600/20 to-blue-800/20 dark:from-blue-600/20 dark:to-blue-800/20 border border-blue-500/30 dark:border-blue-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">Total Registered Students</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">{{ number_format($stats['total_students']) }}</p>
            @if($stats['students_change'] != 0)
                <p class="{{ $stats['students_change'] > 0 ? 'text-success' : 'text-danger' }} text-base font-medium leading-normal">
                    {{ $stats['students_change'] > 0 ? '+' : '' }}{{ $stats['students_change'] }}%
                </p>
            @endif
        </div>
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-green-600/20 to-green-800/20 dark:from-green-600/20 dark:to-green-800/20 border border-green-500/30 dark:border-green-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">Total Income</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">₵{{ number_format($stats['total_income'], 2) }}</p>
            @if($stats['income_change'] != 0)
                <p class="{{ $stats['income_change'] > 0 ? 'text-success' : 'text-danger' }} text-base font-medium leading-normal">
                    {{ $stats['income_change'] > 0 ? '+' : '' }}{{ $stats['income_change'] }}%
                </p>
            @endif
        </div>
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-purple-600/20 to-purple-800/20 dark:from-purple-600/20 dark:to-purple-800/20 border border-purple-500/30 dark:border-purple-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">Total Commissions Paid</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">₵{{ number_format($stats['total_commissions'], 2) }}</p>
            @if($stats['commissions_change'] != 0)
                <p class="{{ $stats['commissions_change'] > 0 ? 'text-success' : 'text-danger' }} text-base font-medium leading-normal">
                    {{ $stats['commissions_change'] > 0 ? '+' : '' }}{{ $stats['commissions_change'] }}%
                </p>
            @endif
        </div>
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-yellow-600/20 to-yellow-800/20 dark:from-yellow-600/20 dark:to-yellow-800/20 border border-yellow-500/30 dark:border-yellow-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">Profit</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">₵{{ number_format($stats['profit'], 2) }}</p>
            @if($stats['profit_change'] != 0)
                <p class="{{ $stats['profit_change'] > 0 ? 'text-success' : 'text-danger' }} text-base font-medium leading-normal">
                    {{ $stats['profit_change'] > 0 ? '+' : '' }}{{ $stats['profit_change'] }}%
                </p>
            @endif
        </div>
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-cyan-600/20 to-cyan-800/20 dark:from-cyan-600/20 dark:to-cyan-800/20 border border-cyan-500/30 dark:border-cyan-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">New Affiliate Signups</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">{{ $stats['new_affiliate_signups'] }}</p>
            @if($stats['signups_change'] != 0)
                <p class="{{ $stats['signups_change'] > 0 ? 'text-success' : 'text-danger' }} text-base font-medium leading-normal">
                    {{ $stats['signups_change'] > 0 ? '+' : '' }}{{ $stats['signups_change'] }}%
                </p>
            @endif
        </div>
        <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-orange-600/20 to-orange-800/20 dark:from-orange-600/20 dark:to-orange-800/20 border border-orange-500/30 dark:border-orange-500/30">
            <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">Pending Payouts</p>
            <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">₵{{ number_format($stats['pending_payouts'], 2) }}</p>
            @if($stats['payouts_change'] != 0)
                <p class="{{ $stats['payouts_change'] > 0 ? 'text-success' : 'text-danger' }} text-base font-medium leading-normal">
                    {{ $stats['payouts_change'] > 0 ? '+' : '' }}{{ $stats['payouts_change'] }}%
                </p>
            @endif
        </div>
    </div>

    <!-- Charts Section -->
    <div class="mt-4 sm:mt-6">
        <h2 class="text-gray-900 dark:text-white text-lg sm:text-xl lg:text-[22px] font-bold leading-tight tracking-[-0.015em] mb-4">Analytics & Insights</h2>
        <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-2">
            <!-- Revenue Over Time Chart -->
            <div class="flex flex-col gap-4 rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] p-4 sm:p-6 overflow-hidden">
                <p class="text-gray-900 dark:text-white text-base sm:text-lg font-bold leading-tight tracking-[-0.015em]">Revenue Over Time</p>
                <canvas id="revenueChart" class="max-h-80 w-full"></canvas>
            </div>

            <!-- Student Registrations Chart -->
            <div class="flex flex-col gap-4 rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] p-4 sm:p-6 overflow-hidden">
                <p class="text-gray-900 dark:text-white text-base sm:text-lg font-bold leading-tight tracking-[-0.015em]">Student Registrations</p>
                <canvas id="studentsChart" class="max-h-80 w-full"></canvas>
            </div>

            <!-- Commissions Over Time Chart -->
            <div class="flex flex-col gap-4 rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] p-4 sm:p-6 overflow-hidden">
                <p class="text-gray-900 dark:text-white text-base sm:text-lg font-bold leading-tight tracking-[-0.015em]">Commissions Over Time</p>
                <canvas id="commissionsChart" class="max-h-80 w-full"></canvas>
            </div>

            <!-- Payment Status Distribution -->
            <div class="flex flex-col gap-4 rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] p-4 sm:p-6 overflow-hidden">
                <p class="text-gray-900 dark:text-white text-base sm:text-lg font-bold leading-tight tracking-[-0.015em]">Payment Status Distribution</p>
                <canvas id="paymentStatusChart" class="max-h-80 w-full"></canvas>
            </div>
        </div>
    </div>

    <!-- Invite Code Tools -->
    <div>
        <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] mb-4">Invite Code Tools</h2>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Generate New Code -->
            <div class="flex flex-col gap-4 rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] p-4 sm:p-6">
                <p class="text-gray-900 dark:text-white text-base sm:text-lg font-bold leading-tight tracking-[-0.015em]">Generate New Code</p>
                <p class="text-gray-500 dark:text-[#92adc9] text-xs sm:text-sm font-normal leading-normal">Set a usage limit and generate a unique invite code for new affiliates. Leave code empty for auto-generation.</p>
                <form method="POST" action="{{ route('admin.invite-codes.store') }}" class="flex flex-col gap-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Custom Code (optional)</label>
                            <input 
                                class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent" 
                                placeholder="Leave empty for auto-generation" 
                                type="text" 
                                name="code" 
                                value="{{ old('code') }}"
                            />
                            @error('code')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Usage Limit</label>
                            <input 
                                class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent" 
                                placeholder="Usage Limit (e.g., 10)" 
                                type="number" 
                                name="max_uses" 
                                value="{{ old('max_uses', 1) }}" 
                                required 
                                min="1"
                            />
                            @error('max_uses')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Expires At (optional)</label>
                        <input 
                            class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent" 
                            type="datetime-local" 
                            name="expires_at" 
                            value="{{ old('expires_at') }}"
                        />
                        @error('expires_at')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="flex w-full sm:w-auto cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-primary text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] px-4">
                        <span>Generate Code</span>
                        <span class="material-symbols-outlined text-base">add_circle</span>
                    </button>
                </form>
                @if(session('generated_code'))
                    <div class="mt-2 flex items-center justify-between rounded-lg border border-dashed border-gray-300 dark:border-[#324d67] bg-gray-50 dark:bg-[#111a22] p-3">
                        <span class="text-gray-700 dark:text-[#92adc9] font-mono text-sm" id="generated-code">{{ session('generated_code') }}</span>
                        <button onclick="copyCode()" class="text-gray-600 dark:text-white/70 hover:text-gray-900 dark:hover:text-white">
                            <span class="material-symbols-outlined text-xl">content_copy</span>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Validate Existing Code -->
            <div class="flex flex-col gap-4 rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] p-6">
                <p class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">Validate Existing Code</p>
                <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">Enter an invite code to check its current status and usage.</p>
                <div class="flex flex-col gap-4 sm:flex-row">
                    <input 
                        class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent" 
                        placeholder="Enter Invite Code" 
                        type="text" 
                        id="validate-code-input"
                    />
                    <button onclick="validateCode()" class="flex w-full sm:w-auto cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] px-4 hover:bg-gray-200 dark:hover:bg-white/20">
                        <span>Validate</span>
                        <span class="material-symbols-outlined text-base">verified</span>
                    </button>
                </div>
                <div id="validate-result" class="mt-2 hidden"></div>
            </div>
        </div>
    </div>

    <!-- Pending Affiliate Registrations -->
    <div>
        <h2 class="text-gray-900 dark:text-white text-lg sm:text-xl lg:text-[22px] font-bold leading-tight tracking-[-0.015em] mb-4">Pending Affiliate Registrations</h2>
        <div class="rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] overflow-hidden">
            @if($pendingAffiliates->count() > 0)
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <table class="w-full text-left text-xs sm:text-sm min-w-[640px]">
                        <thead class="bg-gray-50 dark:bg-gray-800 text-xs uppercase">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-gray-600 dark:text-gray-300" scope="col">Name</th>
                                <th class="px-3 sm:px-6 py-3 text-gray-600 dark:text-gray-300" scope="col">Email</th>
                                <th class="px-3 sm:px-6 py-3 text-gray-600 dark:text-gray-300" scope="col">Date Submitted</th>
                                <th class="px-3 sm:px-6 py-3 text-right text-gray-600 dark:text-gray-300" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-[#324d67]">
                            @foreach($pendingAffiliates as $affiliate)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="whitespace-nowrap px-3 sm:px-6 py-3 sm:py-4 font-medium text-gray-900 dark:text-white">{{ $affiliate->user->name }}</td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-500 dark:text-gray-400 break-all">{{ $affiliate->user->email }}</td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $affiliate->created_at->format('Y-m-d') }}</td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-right space-x-1 sm:space-x-2">
                                        <form method="POST" action="{{ route('admin.affiliate-agents.reject', $affiliate) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="rounded bg-red-100 dark:bg-red-900/30 px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium text-red-700 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50" onclick="return confirm('Are you sure you want to reject this registration?')">Decline</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.affiliate-agents.approve', $affiliate) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="rounded bg-green-100 dark:bg-green-900/30 px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium text-green-700 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50">Approve</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-center text-gray-500 dark:text-[#92adc9]">No pending affiliate registrations.</div>
            @endif
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        function copyCode() {
            const code = document.getElementById('generated-code').textContent;
            navigator.clipboard.writeText(code).then(() => {
                alert('Code copied to clipboard!');
            });
        }

        function validateCode() {
            const code = document.getElementById('validate-code-input').value;
            const resultDiv = document.getElementById('validate-result');
            
            if (!code) {
                alert('Please enter an invite code');
                return;
            }

            fetch('{{ route("admin.invite-codes.validate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code })
            })
            .then(response => response.json())
            .then(data => {
                resultDiv.classList.remove('hidden');
                if (data.valid) {
                    resultDiv.innerHTML = `
                        <div class="flex items-center gap-2 rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 p-3">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                            <span class="text-green-700 dark:text-green-400 text-sm">Valid, ${data.remaining_uses}/${data.max_uses} uses remaining.</span>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="flex items-center gap-2 rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 p-3">
                            <span class="material-symbols-outlined text-red-600 dark:text-red-400">cancel</span>
                            <span class="text-red-700 dark:text-red-400 text-sm">${data.message}</span>
                        </div>
                    `;
                }
            })
            .catch(error => {
                resultDiv.classList.remove('hidden');
                resultDiv.innerHTML = `
                    <div class="flex items-center gap-2 rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 p-3">
                        <span class="material-symbols-outlined text-red-600 dark:text-red-400">cancel</span>
                        <span class="text-red-700 dark:text-red-400 text-sm">Error validating code. Please try again.</span>
                    </div>
                `;
            });
        }

        // Chart Data
        const chartData = @json($chartData);
        
        // Ensure chartData has default values
        const safeChartData = {
            labels: chartData.labels || Array.from({length: 12}, (_, i) => {
                const date = new Date();
                date.setMonth(date.getMonth() - (11 - i));
                return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
            }),
            revenue: chartData.revenue || Array(12).fill(0),
            students: chartData.students || Array(12).fill(0),
            commissions: chartData.commissions || Array(12).fill(0),
            payment_status: chartData.payment_status || { paid: 0, pending: 0, failed: 0 }
        };

        // Theme-aware colors
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#9ca3af' : '#6b7280';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
        const legendColor = isDark ? '#fff' : '#374151';

        // Set Chart.js defaults
        Chart.defaults.color = textColor;
        Chart.defaults.borderColor = gridColor;

        // Wait for DOM and initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            initializeAdminCharts();
        });

        function initializeAdminCharts() {
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded.');
                return;
            }

            // Revenue Over Time Chart
            const revenueCanvas = document.getElementById('revenueChart');
            if (revenueCanvas) {
                const revenueCtx = revenueCanvas.getContext('2d');
                new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: safeChartData.labels,
                        datasets: [{
                            label: 'Revenue (₵)',
                            data: safeChartData.revenue,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: 'rgb(34, 197, 94)',
                            pointBorderColor: isDark ? '#1f2937' : '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: legendColor,
                                    font: { size: 12 }
                                }
                            },
                            tooltip: {
                                backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                                titleColor: isDark ? '#fff' : '#111827',
                                bodyColor: isDark ? '#fff' : '#374151',
                                borderColor: 'rgb(34, 197, 94)',
                                borderWidth: 1,
                                callbacks: {
                                    label: function(context) {
                                        return 'Revenue: ₵' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: textColor,
                                    callback: function(value) {
                                        return '₵' + value.toLocaleString();
                                    }
                                },
                                grid: { color: gridColor }
                            },
                            x: {
                                ticks: { color: textColor },
                                grid: { color: gridColor }
                            }
                        }
                    }
                });
            }

            // Student Registrations Chart
            const studentsCanvas = document.getElementById('studentsChart');
            if (studentsCanvas) {
                const studentsCtx = studentsCanvas.getContext('2d');
                new Chart(studentsCtx, {
                    type: 'bar',
                    data: {
                        labels: safeChartData.labels,
                        datasets: [{
                            label: 'Students Registered',
                            data: safeChartData.students,
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(147, 51, 234, 0.8)',
                                'rgba(236, 72, 153, 0.8)',
                                'rgba(251, 146, 60, 0.8)',
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(6, 182, 212, 0.8)',
                                'rgba(168, 85, 247, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(14, 165, 233, 0.8)',
                                'rgba(139, 92, 246, 0.8)',
                                'rgba(20, 184, 166, 0.8)'
                            ],
                            borderColor: [
                                'rgb(59, 130, 246)',
                                'rgb(147, 51, 234)',
                                'rgb(236, 72, 153)',
                                'rgb(251, 146, 60)',
                                'rgb(34, 197, 94)',
                                'rgb(6, 182, 212)',
                                'rgb(168, 85, 247)',
                                'rgb(239, 68, 68)',
                                'rgb(245, 158, 11)',
                                'rgb(14, 165, 233)',
                                'rgb(139, 92, 246)',
                                'rgb(20, 184, 166)'
                            ],
                            borderWidth: 2,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: legendColor,
                                    font: { size: 12 }
                                }
                            },
                            tooltip: {
                                backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                                titleColor: isDark ? '#fff' : '#111827',
                                bodyColor: isDark ? '#fff' : '#374151',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 1
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: textColor,
                                    stepSize: 1
                                },
                                grid: { color: gridColor }
                            },
                            x: {
                                ticks: { color: textColor },
                                grid: { color: gridColor }
                            }
                        }
                    }
                });
            }

            // Commissions Over Time Chart
            const commissionsCanvas = document.getElementById('commissionsChart');
            if (commissionsCanvas) {
                const commissionsCtx = commissionsCanvas.getContext('2d');
                new Chart(commissionsCtx, {
                    type: 'line',
                    data: {
                        labels: safeChartData.labels,
                        datasets: [{
                            label: 'Commissions (₵)',
                            data: safeChartData.commissions,
                            borderColor: 'rgb(168, 85, 247)',
                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: 'rgb(168, 85, 247)',
                            pointBorderColor: isDark ? '#1f2937' : '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: legendColor,
                                    font: { size: 12 }
                                }
                            },
                            tooltip: {
                                backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                                titleColor: isDark ? '#fff' : '#111827',
                                bodyColor: isDark ? '#fff' : '#374151',
                                borderColor: 'rgb(168, 85, 247)',
                                borderWidth: 1,
                                callbacks: {
                                    label: function(context) {
                                        return 'Commissions: ₵' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: textColor,
                                    callback: function(value) {
                                        return '₵' + value.toLocaleString();
                                    }
                                },
                                grid: { color: gridColor }
                            },
                            x: {
                                ticks: { color: textColor },
                                grid: { color: gridColor }
                            }
                        }
                    }
                });
            }

            // Payment Status Distribution Chart
            const paymentStatusCanvas = document.getElementById('paymentStatusChart');
            if (paymentStatusCanvas) {
                const paymentStatusCtx = paymentStatusCanvas.getContext('2d');
                new Chart(paymentStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Paid', 'Pending', 'Failed'],
                        datasets: [{
                            data: [
                                safeChartData.payment_status.paid || 0,
                                safeChartData.payment_status.pending || 0,
                                safeChartData.payment_status.failed || 0
                            ],
                            backgroundColor: [
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(251, 146, 60, 0.8)',
                                'rgba(239, 68, 68, 0.8)'
                            ],
                            borderColor: [
                                'rgb(34, 197, 94)',
                                'rgb(251, 146, 60)',
                                'rgb(239, 68, 68)'
                            ],
                            borderWidth: 3,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    color: legendColor,
                                    font: { size: 12 },
                                    padding: 15
                                }
                            },
                            tooltip: {
                                backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                                titleColor: isDark ? '#fff' : '#111827',
                                bodyColor: isDark ? '#fff' : '#374151',
                                borderColor: isDark ? '#fff' : '#374151',
                                borderWidth: 1,
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    </script>
</x-app-layout>
