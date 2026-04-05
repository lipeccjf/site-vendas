document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('graficoProdutosMensal');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    new Chart(ctx, {
        type: 'bar', // Gráfico de barras para melhor visualização de volume mensal
        data: {
            labels: window.chartLabels || [],
            datasets: [{
                label: 'Novos Produtos',
                data: window.chartData || [],
                backgroundColor: '#2c5282',
                borderColor: '#1a365d',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            if (Math.floor(value) === value) return value;
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
});