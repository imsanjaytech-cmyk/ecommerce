/**
 * admin-charts.js
 * All Chart.js instances for the NexAdmin panel.
 * Place at: public/js/admin-charts.js
 *
 * Reads window.adminData set by each blade page's @push('page-data') block.
 * Only renders charts whose canvas elements exist on the current page.
 */

(function () {
    'use strict';

    /* ── THEME TOKENS ─────────────────────────────────────── */
    const C = {
        primary:   '#ff4d6d',
        secondary: '#ff8fab',
        green:     '#1f9c4a',
        blue:      '#1a7cd4',
        orange:    '#d97706',
        teal:      '#0891b2',
        purple:    '#7c3aed',
        muted:     '#9199a6',
        border:    '#eef0f4',
        font:      "'Poppins', sans-serif",
    };

    /* ── GLOBAL CHART DEFAULTS ─────────────────────────────── */
    Chart.defaults.font.family  = C.font;
    Chart.defaults.font.size    = 11;
    Chart.defaults.color        = C.muted;
    Chart.defaults.borderColor  = C.border;

    /* ── HELPERS ───────────────────────────────────────────── */
    function el(id) { return document.getElementById(id); }

    function linearGradient(ctx, h, colorTop, colorBot) {
        const g = ctx.createLinearGradient(0, 0, 0, h);
        g.addColorStop(0, colorTop);
        g.addColorStop(1, colorBot);
        return g;
    }

    const gridLine = { color: 'rgba(0,0,0,0.05)', drawBorder: false };
    const noLegend = { display: false };

    /* ══════════════════════════════════════════════════════
       1. REVENUE LINE CHART  (dashboard)
    ══════════════════════════════════════════════════════ */
    if (el('revenueChart') && window.adminData?.revenue) {
        const ctx = el('revenueChart').getContext('2d');
        const grad = linearGradient(ctx, 240,
            'rgba(255,77,109,0.18)',
            'rgba(255,77,109,0.0)'
        );
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: window.adminData.revenue.labels,
                datasets: [{
                    label: 'Revenue',
                    data:  window.adminData.revenue.data,
                    borderColor: C.primary,
                    backgroundColor: grad,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.42,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: C.primary,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: noLegend },
                scales: {
                    x: { grid: gridLine, ticks: { font: { size: 11 } } },
                    y: {
                        grid: gridLine,
                        ticks: {
                            font: { size: 11 },
                            callback: v => '$' + (v / 1000) + 'K'
                        }
                    }
                }
            }
        });
    }

    /* ══════════════════════════════════════════════════════
       2. CATEGORY DOUGHNUT CHART  (dashboard)
    ══════════════════════════════════════════════════════ */
    if (el('categoryChart') && window.adminData?.category) {
        new Chart(el('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: window.adminData.category.labels,
                datasets: [{
                    data: window.adminData.category.data,
                    backgroundColor: [C.primary, C.blue, C.green, C.orange],
                    borderWidth: 0,
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '74%',
                plugins: {
                    legend: noLegend,
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.label}: ${ctx.parsed}%`
                        }
                    }
                }
            }
        });
    }

    /* ══════════════════════════════════════════════════════
       3. WEEKLY ORDERS BAR CHART  (dashboard)
    ══════════════════════════════════════════════════════ */
    if (el('weeklyChart') && window.adminData?.weekly) {
        new Chart(el('weeklyChart'), {
            type: 'bar',
            data: {
                labels: window.adminData.weekly.labels,
                datasets: [{
                    label: 'Orders',
                    data:  window.adminData.weekly.data,
                    backgroundColor: ctx => {
                        const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 200);
                        g.addColorStop(0, 'rgba(255,77,109,0.85)');
                        g.addColorStop(1, 'rgba(255,143,171,0.35)');
                        return g;
                    },
                    borderRadius: 7,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: noLegend },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: gridLine }
                }
            }
        });
    }

    /* ══════════════════════════════════════════════════════
       4. CUSTOMER GROWTH AREA CHART  (dashboard)
    ══════════════════════════════════════════════════════ */
    if (el('growthChart') && window.adminData?.growth) {
        const ctx = el('growthChart').getContext('2d');
        const grad = linearGradient(ctx, 200,
            'rgba(26,124,212,0.2)',
            'rgba(26,124,212,0.0)'
        );
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: window.adminData.growth.labels,
                datasets: [{
                    label: 'New Customers',
                    data:  window.adminData.growth.data,
                    borderColor: C.blue,
                    backgroundColor: grad,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.42,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: noLegend },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                    y: { grid: gridLine, ticks: { font: { size: 10 } } }
                }
            }
        });
    }

    /* ══════════════════════════════════════════════════════
       5. KPI RADAR CHART  (dashboard)
    ══════════════════════════════════════════════════════ */
    if (el('radarChart') && window.adminData?.radar) {
        new Chart(el('radarChart'), {
            type: 'radar',
            data: {
                labels: window.adminData.radar.labels,
                datasets: [
                    {
                        label: 'Current',
                        data:  window.adminData.radar.current,
                        borderColor: C.primary,
                        backgroundColor: 'rgba(255,77,109,0.12)',
                        borderWidth: 2,
                        pointBackgroundColor: C.primary,
                        pointRadius: 3,
                    },
                    {
                        label: 'Target',
                        data:  window.adminData.radar.target,
                        borderColor: C.blue,
                        backgroundColor: 'rgba(26,124,212,0.08)',
                        borderWidth: 1.5,
                        borderDash: [5, 4],
                        pointRadius: 0,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: noLegend },
                scales: {
                    r: {
                        grid:    { color: 'rgba(0,0,0,0.06)' },
                        ticks:   { display: false },
                        pointLabels: { font: { size: 10 }, color: C.muted }
                    }
                }
            }
        });
    }

    /* ══════════════════════════════════════════════════════
       6. REVENUE vs EXPENSES GROUPED BAR  (reports / orders)
    ══════════════════════════════════════════════════════ */
    if (el('revExpChart') && window.adminData?.revExp) {
        const d = window.adminData.revExp;
        new Chart(el('revExpChart'), {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [
                    {
                        label: 'Revenue',
                        data:  d.revenue,
                        backgroundColor: ctx => {
                            const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 220);
                            g.addColorStop(0, 'rgba(255,77,109,0.85)');
                            g.addColorStop(1, 'rgba(255,143,171,0.3)');
                            return g;
                        },
                        borderRadius: 6,
                        borderSkipped: false,
                    },
                    {
                        label: 'Expenses',
                        data:  d.expenses,
                        backgroundColor: 'rgba(26,124,212,0.18)',
                        borderColor: C.blue,
                        borderWidth: 1.5,
                        borderRadius: 6,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: C.muted, font: { size: 11 }, usePointStyle: true, pointStyleWidth: 8 }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        grid: gridLine,
                        ticks: { callback: v => '$' + (v / 1000) + 'K' }
                    }
                }
            }
        });
    }

    /* ══════════════════════════════════════════════════════
       7. TRAFFIC SOURCES PIE  (reports)
    ══════════════════════════════════════════════════════ */
    if (el('sourceChart') && window.adminData?.source) {
        new Chart(el('sourceChart'), {
            type: 'pie',
            data: {
                labels: window.adminData.source.labels,
                datasets: [{
                    data: window.adminData.source.data,
                    backgroundColor: [C.primary, C.blue, C.green, C.orange, C.teal],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 10,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: C.muted,
                            font: { size: 11 },
                            padding: 14,
                            usePointStyle: true,
                            pointStyleWidth: 8
                        }
                    }
                }
            }
        });
    }

})();