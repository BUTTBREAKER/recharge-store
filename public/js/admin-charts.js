// Admin Dashboard Charts
// Inicializa los gráficos de Chart.js para el dashboard administrativo

document.addEventListener('DOMContentLoaded', function() {
    // Configuración de colores consistente con Tailwind
    const colors = {
        primary: '#8b5cf6', // violet-600
        primaryLight: '#a78bfa', // violet-400
        success: '#10b981', // green-500
        warning: '#f59e0b', // amber-500
        danger: '#ef4444', // red-500
        info: '#3b82f6', // blue-500
        gray: '#6b7280' // gray-500
    };

    // Configuración común para todos los gráficos
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                borderRadius: 8,
                titleColor: '#fff',
                bodyColor: '#fff',
                displayColors: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: colors.gray,
                    callback: function(value) {
                        return '$' + value.toFixed(2);
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                ticks: {
                    color: colors.gray
                },
                grid: {
                    display: false
                }
            }
        }
    };

    // Helper para crear gradiente
    function createGradient(ctx, color1, color2) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    }

    // Gráfico de Ventas Diarias
    const dailyCanvas = document.getElementById('dailyChart');
    if (dailyCanvas) {
        const ctx = dailyCanvas.getContext('2d');
        const gradient = createGradient(ctx, colors.primary, colors.primaryLight);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: window.chartData.daily.labels,
                datasets: [{
                    label: 'Ventas',
                    data: window.chartData.daily.data,
                    borderColor: colors.primary,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: commonOptions
        });
    }

    // Gráfico de Ventas Semanales
    const weeklyCanvas = document.getElementById('weeklyChart');
    if (weeklyCanvas) {
        const ctx = weeklyCanvas.getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: window.chartData.weekly.labels,
                datasets: [{
                    label: 'Ventas',
                    data: window.chartData.weekly.data,
                    backgroundColor: colors.info,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: commonOptions
        });
    }

    // Gráfico de Ventas Mensuales
    const monthlyCanvas = document.getElementById('monthlyChart');
    if (monthlyCanvas) {
        const ctx = monthlyCanvas.getContext('2d');
        const gradient = createGradient(ctx, colors.success, '#34d399');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: window.chartData.monthly.labels,
                datasets: [{
                    label: 'Ventas',
                    data: window.chartData.monthly.data,
                    borderColor: colors.success,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: colors.success,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: commonOptions
        });
    }
});
