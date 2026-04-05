document.addEventListener('DOMContentLoaded', () => {
    renderizarGraficoVendas();
});

function renderizarGraficoVendas() {
    const elementoGrafico = document.getElementById('graficoVendasLinha');
    
    if (!elementoGrafico) return;

    const ctx = elementoGrafico.getContext('2d');
    
    // Recupera os dados processados do Controller
    const labels = window.graficoLabels || [];
    const dados = window.graficoDados || [];

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Vendas Realizadas',
                data: dados,
                borderColor: '#2c5282',
                backgroundColor: 'rgba(44, 82, 130, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#2c5282'
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: { 
                    display: true, 
                    position: 'top' 
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        stepSize: 1,
                        // Garante que apenas números inteiros apareçam (quantidade de vendas)
                        callback: function(value) {
                            if (Math.floor(value) === value) {
                                return value;
                            }
                        }
                    }
                }
            }
        }
    });
}