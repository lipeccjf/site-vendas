function gerarRelatorioPDF() {
    window.open("/gerar-pdf-compras", "_blank");
}

function abrirDetalhes(id) {
    fetch(`/purchase/detalhes/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modal-vendedor').textContent = data.seller;
            document.getElementById('modal-categoria').textContent = data.category;
            document.getElementById('modal-metodo').textContent = data.payment_method;
            document.getElementById('modal-detalhes-compra').style.display = 'block';
        })
        .catch(error => console.error('Erro:', error));
}

function fecharModal() {
    document.getElementById('modal-detalhes-compra').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    console.log("Página de Histórico de Compras carregada.");
});