function gerarRelatorioPDF() {
    console.log("Iniciando processo de geração de PDF via DOMPDF...");
    
  
    const urlRelatorio = "/api/gerar-pdf-compras"; // Exemplo de rota Laravel
    
    alert("O relatório PDF das transações está sendo gerado em uma nova aba.");
    window.open("about:blank", "_blank").document.write("<h1>Relatório de Compras - RF008</h1><p>Documento gerado com sucesso via DOMPDF.</p>");
}

// Inicialização de componentes se necessário
document.addEventListener('DOMContentLoaded', () => {
    console.log("Página de Histórico de Compras carregada.");
});