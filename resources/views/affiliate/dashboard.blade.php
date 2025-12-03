<x-app-layout>
    <x-slot name="title">Affiliate Agent Dashboard</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-col sm:flex-row flex-wrap justify-between gap-3 items-start sm:items-center">
        <h1 class="text-gray-900 dark:text-white text-2xl sm:text-3xl lg:text-4xl font-black leading-tight tracking-[-0.033em]">Affiliate Dashboard</h1>
        <a href="{{ route('affiliate.withdrawals.index') }}" class="flex w-full sm:w-auto min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 sm:h-12 px-4 sm:px-5 bg-primary text-white text-sm sm:text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
            <span class="truncate">Request Withdrawal</span>
        </a>
    </div>

    <div class="mt-8 grid gap-6">
        <!-- ActionPanel -->
        <div class="@container">
            <div class="flex flex-1 flex-col items-start justify-between gap-4 rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] p-5 @[480px]:flex-row @[480px]:items-center">
                <div class="flex flex-col gap-1">
                    <p class="text-gray-900 dark:text-white text-base font-bold leading-tight">My Referral Link</p>
                    <p class="text-gray-500 dark:text-[#92adc9] text-base font-normal leading-normal break-all" id="referral-link">{{ url('/register?ref=' . $agent->referral_link) }}</p>
                </div>
                <button type="button" data-copy-referral class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-primary text-white text-sm font-medium leading-normal hover:bg-primary/90">
                    <span class="truncate">Copy Link</span>
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 sm:gap-6">
            <div class="flex flex-col gap-2 rounded-lg p-4 sm:p-6 bg-gradient-to-br from-green-600/20 to-green-800/20 dark:from-green-600/20 dark:to-green-800/20 border border-green-500/30 dark:border-green-500/30">
                <p class="text-gray-700 dark:text-white text-sm sm:text-base font-medium leading-normal">Total Earnings</p>
                <p class="text-gray-900 dark:text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">₵{{ number_format($stats['total_earnings'], 2) }}</p>
            </div>
            <div class="flex flex-col gap-2 rounded-lg p-6 bg-gradient-to-br from-blue-600/20 to-blue-800/20 dark:from-blue-600/20 dark:to-blue-800/20 border border-blue-500/30 dark:border-blue-500/30">
                <p class="text-gray-700 dark:text-white text-base font-medium leading-normal">Amount Withdrawn</p>
                <p class="text-gray-900 dark:text-white tracking-light text-3xl font-bold leading-tight">₵{{ number_format($stats['total_withdrawn'], 2) }}</p>
            </div>
            <div class="flex flex-col gap-2 rounded-lg p-6 bg-gradient-to-br from-purple-600/20 to-purple-800/20 dark:from-purple-600/20 dark:to-purple-800/20 border border-purple-500/30 dark:border-purple-500/30">
                <p class="text-gray-700 dark:text-white text-base font-medium leading-normal">Current Balance</p>
                <p class="text-gray-900 dark:text-white tracking-light text-3xl font-bold leading-tight">₵{{ number_format($stats['wallet_balance'], 2) }}</p>
            </div>
            <div class="flex flex-col gap-2 rounded-lg p-6 bg-gradient-to-br from-cyan-600/20 to-cyan-800/20 dark:from-cyan-600/20 dark:to-cyan-800/20 border border-cyan-500/30 dark:border-cyan-500/30">
                <p class="text-gray-700 dark:text-white text-base font-medium leading-normal">Total Students</p>
                <p class="text-gray-900 dark:text-white tracking-light text-3xl font-bold leading-tight">{{ number_format($stats['total_students']) }}</p>
            </div>
            <div class="flex flex-col gap-2 rounded-lg p-6 bg-gradient-to-br from-orange-600/20 to-orange-800/20 dark:from-orange-600/20 dark:to-orange-800/20 border border-orange-500/30 dark:border-orange-500/30">
                <p class="text-gray-700 dark:text-white text-base font-medium leading-normal">Pending Withdrawals</p>
                <p class="text-gray-900 dark:text-white tracking-light text-3xl font-bold leading-tight">{{ number_format($stats['pending_withdrawals']) }}</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="mt-4 sm:mt-6">
            <h2 class="text-gray-900 dark:text-white text-lg sm:text-xl lg:text-[22px] font-bold leading-tight tracking-[-0.015em] mb-4">Analytics & Insights</h2>
            <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-2">
                <!-- Earnings Over Time Chart -->
                <div class="flex flex-col gap-4 rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] p-6">
                    <p class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">Earnings Over Time</p>
                    <canvas id="earningsChart" class="max-h-80"></canvas>
                </div>

                <!-- Students Over Time Chart -->
                <div class="flex flex-col gap-4 rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] p-6">
                    <p class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">Students Over Time</p>
                    <canvas id="studentsChart" class="max-h-80"></canvas>
                </div>

                <!-- Paid vs Pending Students Chart -->
                <div class="flex flex-col gap-4 rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] p-6">
                    <p class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">Paid vs Pending Students</p>
                    <canvas id="paidStudentsChart" class="max-h-80"></canvas>
                </div>

                <!-- Payment Status Distribution -->
                <div class="flex flex-col gap-4 rounded-lg bg-white dark:bg-[#111a22] border border-gray-200 dark:border-[#324d67] p-6">
                    <p class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">Payment Status Distribution</p>
                    <canvas id="paymentStatusChart" class="max-h-80"></canvas>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="w-full bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-200 dark:border-[#324d67]">
                <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">My Referred Students</h2>
            </div>
            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <table class="w-full text-left min-w-[640px]">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-3 sm:px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Student Name</th>
                            <th class="px-3 sm:px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Registration Date</th>
                            <th class="px-3 sm:px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Status</th>
                            <th class="px-3 sm:px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 text-right">Commission Earned</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-[#324d67]">
                        @forelse($recentStudents as $student)
                            <tr>
                                <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                    <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white">{{ $student->user->name }}</p>
                                </td>
                                <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $student->payment_date ? $student->payment_date->format('Y-m-d') : $student->created_at->format('Y-m-d') }}</p>
                                </td>
                                <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-full {{ $student->payment_status === 'paid' ? 'bg-green-100 dark:bg-green-900/30 px-2 sm:px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400' : 'bg-orange-100 dark:bg-orange-900/30 px-2 sm:px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:text-orange-400' }}">
                                        {{ $student->payment_status === 'paid' ? 'Paid' : 'Unpaid' }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-5 py-3 sm:py-4 whitespace-nowrap text-right">
                                    <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white">₵{{ $student->payment_status === 'paid' ? '40.00' : '0.00' }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-4 text-center text-gray-500 dark:text-gray-400">No students yet. Share your referral link to start earning commissions!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // Copy link functionality
        document.addEventListener('DOMContentLoaded', function() {
            const copyBtn = document.querySelector('[data-copy-referral]');
            if (copyBtn) {
                copyBtn.addEventListener('click', function() {
                    const link = document.getElementById('referral-link').textContent;
                    navigator.clipboard.writeText(link).then(() => {
                        const originalText = copyBtn.innerHTML;
                        copyBtn.innerHTML = '<span class="truncate">Copied!</span>';
                        setTimeout(() => {
                            copyBtn.innerHTML = originalText;
                        }, 2000);
                    });
                });
            }
        });

        // Chart Data
        const chartData = @json($chartData ?? []);
        
        // Ensure chartData has default values
        const safeChartData = {
            labels: chartData.labels || Array.from({length: 12}, (_, i) => {
                const date = new Date();
                date.setMonth(date.getMonth() - (11 - i));
                return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
            }),
            earnings: chartData.earnings || Array(12).fill(0),
            students: chartData.students || Array(12).fill(0),
            paid_students: chartData.paid_students || Array(12).fill(0),
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

        // Wait for DOM to be ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
        });

        function initializeCharts() {
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded.');
                return;
            }

            // Earnings Over Time Chart
            const earningsCanvas = document.getElementById('earningsChart');
            if (earningsCanvas) {
                const earningsCtx = earningsCanvas.getContext('2d');
                new Chart(earningsCtx, {
                    type: 'line',
                    data: {
                        labels: safeChartData.labels,
                        datasets: [{
                            label: 'Earnings (₵)',
                            data: safeChartData.earnings,
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
                                        return 'Earnings: ₵' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
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

            // Students Over Time Chart
            const studentsCanvas = document.getElementById('studentsChart');
            if (studentsCanvas) {
                const studentsCtx = studentsCanvas.getContext('2d');
                new Chart(studentsCtx, {
                    type: 'bar',
                    data: {
                        labels: safeChartData.labels,
                        datasets: [{
                            label: 'Students Referred',
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

            // Paid vs Pending Students Chart
            const paidStudentsCanvas = document.getElementById('paidStudentsChart');
            if (paidStudentsCanvas) {
                const paidStudentsCtx = paidStudentsCanvas.getContext('2d');
                new Chart(paidStudentsCtx, {
                    type: 'line',
                    data: {
                        labels: safeChartData.labels,
                        datasets: [{
                            label: 'Paid Students',
                            data: safeChartData.paid_students,
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
