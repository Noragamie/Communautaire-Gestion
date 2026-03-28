import {
    ArcElement,
    BarController,
    BarElement,
    CategoryScale,
    Chart,
    DoughnutController,
    Legend,
    LinearScale,
    LineController,
    LineElement,
    PointElement,
    Tooltip,
} from 'chart.js';

Chart.register(
    ArcElement,
    BarController,
    BarElement,
    CategoryScale,
    DoughnutController,
    Legend,
    LinearScale,
    LineController,
    LineElement,
    PointElement,
    Tooltip,
);

const readPayload = () => {
    const el = document.getElementById('dashboard-charts-payload');
    if (!el?.textContent?.trim()) {
        return null;
    }
    try {
        return JSON.parse(el.textContent);
    } catch {
        return null;
    }
};

const chartHeight = { maintainAspectRatio: false };

export function initDashboardCharts() {
    const payload = readPayload();
    if (!payload) {
        return;
    }

    const gridColor = 'rgba(148, 163, 184, 0.25)';
    const textMuted = '#64748b';

    if (payload.status && document.getElementById('chart-status')) {
        new Chart(document.getElementById('chart-status'), {
            type: 'doughnut',
            data: {
                labels: payload.status.labels,
                datasets: [{
                    data: payload.status.data,
                    backgroundColor: [
                        'rgba(234, 179, 8, 0.85)',
                        'rgba(34, 197, 94, 0.85)',
                        'rgba(239, 68, 68, 0.85)',
                        'rgba(100, 116, 139, 0.85)',
                    ],
                    borderWidth: 0,
                }],
            },
            options: {
                ...chartHeight,
                plugins: {
                    legend: { position: 'bottom', labels: { color: textMuted, boxWidth: 12 } },
                },
            },
        });
    }

    if (payload.categories && document.getElementById('chart-categories')) {
        new Chart(document.getElementById('chart-categories'), {
            type: 'bar',
            data: {
                labels: payload.categories.labels,
                datasets: [{
                    label: 'Profils',
                    data: payload.categories.data,
                    backgroundColor: 'rgba(14, 165, 233, 0.75)',
                    borderRadius: 8,
                }],
            },
            options: {
                ...chartHeight,
                scales: {
                    x: { ticks: { color: textMuted }, grid: { display: false } },
                    y: {
                        beginAtZero: true,
                        ticks: { color: textMuted, stepSize: 1 },
                        grid: { color: gridColor },
                    },
                },
                plugins: { legend: { display: false } },
            },
        });
    }

    if (payload.activity && document.getElementById('chart-activity')) {
        new Chart(document.getElementById('chart-activity'), {
            type: 'line',
            data: {
                labels: payload.activity.labels,
                datasets: [{
                    label: 'Nouveaux profils',
                    data: payload.activity.data,
                    borderColor: 'rgb(14, 165, 233)',
                    backgroundColor: 'rgba(14, 165, 233, 0.12)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                }],
            },
            options: {
                ...chartHeight,
                scales: {
                    x: { ticks: { color: textMuted }, grid: { color: gridColor } },
                    y: {
                        beginAtZero: true,
                        ticks: { color: textMuted, stepSize: 1 },
                        grid: { color: gridColor },
                    },
                },
                plugins: { legend: { labels: { color: textMuted } } },
            },
        });
    }

    if (payload.content && document.getElementById('chart-content')) {
        new Chart(document.getElementById('chart-content'), {
            type: 'line',
            data: {
                labels: payload.content.labels,
                datasets: [
                    {
                        label: 'Annonces',
                        data: payload.content.announcements,
                        borderColor: 'rgb(168, 85, 247)',
                        backgroundColor: 'rgba(168, 85, 247, 0.08)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                    },
                    {
                        label: 'Actualités',
                        data: payload.content.actualities,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.08)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                    },
                ],
            },
            options: {
                ...chartHeight,
                scales: {
                    x: { ticks: { color: textMuted }, grid: { color: gridColor } },
                    y: {
                        beginAtZero: true,
                        ticks: { color: textMuted, stepSize: 1 },
                        grid: { color: gridColor },
                    },
                },
                plugins: { legend: { labels: { color: textMuted } } },
            },
        });
    }

    if (payload.communes && document.getElementById('chart-communes')) {
        new Chart(document.getElementById('chart-communes'), {
            type: 'bar',
            data: {
                labels: payload.communes.labels,
                datasets: [{
                    label: 'Profils approuvés',
                    data: payload.communes.data,
                    backgroundColor: 'rgba(34, 197, 94, 0.75)',
                    borderRadius: 6,
                }],
            },
            options: {
                indexAxis: 'y',
                ...chartHeight,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { color: textMuted, stepSize: 1 },
                        grid: { color: gridColor },
                    },
                    y: { ticks: { color: textMuted }, grid: { display: false } },
                },
                plugins: { legend: { display: false } },
            },
        });
    }
}
