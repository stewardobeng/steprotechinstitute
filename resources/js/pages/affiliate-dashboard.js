import Chart from 'chart.js/auto';

function onReady(callback) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback, { once: true });
        return;
    }
    callback();
}

function buildFallbackLabels() {
    return Array.from({ length: 12 }, (_, index) => {
        const date = new Date();
        date.setMonth(date.getMonth() - (11 - index));
        return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
    });
}

function normalizeArray(values, length) {
    if (!Array.isArray(values) || values.length === 0) {
        return Array(length).fill(0);
    }

    const normalized = values.slice(0, length).map((value) => Number(value) || 0);
    if (normalized.length < length) {
        return normalized.concat(Array(length - normalized.length).fill(0));
    }

    return normalized;
}

function getChartData() {
    const raw = window.__affiliateChartData || {};
    const labels = Array.isArray(raw.labels) && raw.labels.length > 0 ? raw.labels : buildFallbackLabels();
    const length = labels.length;

    return {
        labels,
        earnings: normalizeArray(raw.earnings, length),
        students: normalizeArray(raw.students, length),
        paidStudents: normalizeArray(raw.paid_students, length),
        paymentStatus: {
            paid: Number(raw.payment_status?.paid) || 0,
            pending: Number(raw.payment_status?.pending) || 0,
            failed: Number(raw.payment_status?.failed) || 0,
        },
    };
}

function applyThemeDefaults() {
    const isDark = document.documentElement.classList.contains('dark');
    Chart.defaults.color = isDark ? '#9ca3af' : '#6b7280';
    Chart.defaults.borderColor = isDark ? '#374151' : '#e5e7eb';
    Chart.defaults.backgroundColor = isDark ? '#1f2937' : '#f3f4f6';
    return isDark;
}

function renderEarningsChart(ctx, data, isDark) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Earnings (?)',
                    data: data.earnings,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: 'rgb(34, 197, 94)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: isDark ? '#fff' : '#374151',
                        font: { size: 12 },
                    },
                },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                    titleColor: isDark ? '#fff' : '#111827',
                    bodyColor: isDark ? '#fff' : '#111827',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1,
                    callbacks: {
                        label(context) {
                            return 'Earnings: ?' + context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        },
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: isDark ? '#9ca3af' : '#6b7280',
                        callback(value) {
                            return '?' + value.toLocaleString();
                        },
                    },
                    grid: { color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)' },
                },
                x: {
                    ticks: { color: isDark ? '#9ca3af' : '#6b7280' },
                    grid: { color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)' },
                },
            },
        },
    });
}

function renderStudentsChart(ctx, data, isDark) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Students Registered',
                    data: data.students,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)', 'rgba(147, 51, 234, 0.8)', 'rgba(236, 72, 153, 0.8)',
                        'rgba(251, 146, 60, 0.8)', 'rgba(34, 197, 94, 0.8)', 'rgba(6, 182, 212, 0.8)',
                        'rgba(168, 85, 247, 0.8)', 'rgba(239, 68, 68, 0.8)', 'rgba(245, 158, 11, 0.8)',
                        'rgba(14, 165, 233, 0.8)', 'rgba(139, 92, 246, 0.8)', 'rgba(20, 184, 166, 0.8)',
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)', 'rgb(147, 51, 234)', 'rgb(236, 72, 153)',
                        'rgb(251, 146, 60)', 'rgb(34, 197, 94)', 'rgb(6, 182, 212)',
                        'rgb(168, 85, 247)', 'rgb(239, 68, 68)', 'rgb(245, 158, 11)',
                        'rgb(14, 165, 233)', 'rgb(139, 92, 246)', 'rgb(20, 184, 166)',
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    labels: { color: isDark ? '#fff' : '#374151', font: { size: 12 } },
                },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                    titleColor: isDark ? '#fff' : '#111827',
                    bodyColor: isDark ? '#fff' : '#111827',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: isDark ? '#9ca3af' : '#6b7280', stepSize: 1 },
                    grid: { color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)' },
                },
                x: {
                    ticks: { color: isDark ? '#9ca3af' : '#6b7280' },
                    grid: { color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)' },
                },
            },
        },
    });
}

function renderPaidStudentsChart(ctx, data, isDark) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Paid Students',
                    data: data.paidStudents,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                },
                {
                    label: 'Total Students',
                    data: data.students,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    labels: { color: isDark ? '#fff' : '#374151', font: { size: 12 } },
                },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                    titleColor: isDark ? '#fff' : '#111827',
                    bodyColor: isDark ? '#fff' : '#111827',
                    borderColor: '#fff',
                    borderWidth: 1,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: isDark ? '#9ca3af' : '#6b7280', stepSize: 1 },
                    grid: { color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)' },
                },
                x: {
                    ticks: { color: isDark ? '#9ca3af' : '#6b7280' },
                    grid: { color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)' },
                },
            },
        },
    });
}

function renderPaymentStatusChart(ctx, data, isDark) {
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Paid', 'Pending', 'Failed'],
            datasets: [
                {
                    data: [data.paymentStatus.paid, data.paymentStatus.pending, data.paymentStatus.failed],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(251, 146, 60, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                    ],
                    borderColor: ['rgb(34, 197, 94)', 'rgb(251, 146, 60)', 'rgb(239, 68, 68)'],
                    borderWidth: 3,
                    hoverOffset: 10,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: isDark ? '#fff' : '#374151',
                        font: { size: 12 },
                        padding: 15,
                    },
                },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                    titleColor: isDark ? '#fff' : '#111827',
                    bodyColor: isDark ? '#fff' : '#111827',
                    borderColor: '#fff',
                    borderWidth: 1,
                    callbacks: {
                        label(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((sum, item) => sum + item, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        },
                    },
                },
            },
        },
    });
}

function renderCharts() {
    const chartData = getChartData();
    const isDark = applyThemeDefaults();

    const earningsCanvas = document.getElementById('earningsChart');
    if (earningsCanvas) {
        renderEarningsChart(earningsCanvas.getContext('2d'), chartData, isDark);
    }

    const studentsCanvas = document.getElementById('studentsChart');
    if (studentsCanvas) {
        renderStudentsChart(studentsCanvas.getContext('2d'), chartData, isDark);
    }

    const paidStudentsCanvas = document.getElementById('paidStudentsChart');
    if (paidStudentsCanvas) {
        renderPaidStudentsChart(paidStudentsCanvas.getContext('2d'), chartData, isDark);
    }

    const paymentStatusCanvas = document.getElementById('paymentStatusChart');
    if (paymentStatusCanvas) {
        renderPaymentStatusChart(paymentStatusCanvas.getContext('2d'), chartData, isDark);
    }
}

function bindReferralCopy() {
    const button = document.querySelector('[data-copy-referral]');
    const linkElement = document.getElementById('referral-link');

    if (!button || !linkElement) {
        return;
    }

    button.addEventListener('click', () => {
        const link = linkElement.textContent.trim();
        if (!link) {
            return;
        }

        navigator.clipboard.writeText(link)
            .then(() => alert('Link copied to clipboard!'))
            .catch(() => alert('Unable to copy link, please copy it manually.'));
    });
}

onReady(() => {
    window.Chart = Chart;
    bindReferralCopy();
    renderCharts();
});
