<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Vendas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
 
    @vite([
        'resources/css/global.css', 
        'resources/css/modal.css',
        'resources/css/estatisticaproduto.css',      
        'resources/js/modal.js',
        'resources/js/vendas.js ',
        'resources/css/historicovendas.css'
    ])
</head>
<body>
    <?php include 'sidebar.blade.php'; ?>
    <div class="pagina-admins">
        <aside class="conteiner-esq">
            <div class="logo-sistema">E-Commerce</div>
            <ul class="menu-lateral">
                <li class="item-menu"><a href="#" class="link-menu">Dashboard</a></li>
                <li class="item-menu"><a href="#" class="link-menu ativo">Minhas Vendas</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Produtos</a></li>
            </ul>
        </aside>

        <section class="conteiner-dir">
            <header class="topo-pagina">
                <div class="titulo-topo">RF009 - Histórico de Vendas</div>
                <div class="usuario-topo">Olá, <span id="nome-usuario">Vendedor</span></div>
            </header>

            <main class="conteudo-pagina">
                <div class="cabecalho-pagina">
                    <h1 class="titulo-pagina">Painel de Vendas</h1>
                    <p class="texto-pagina">Acompanhe seus lucros e gere relatórios de desempenho.</p>
                </div>

                <!-- Gráfico de Vendas -->
                <div id="container-grafico-vendas" class="card-lista" style="margin-bottom: 20px;">
                    <h2 class="titulo-card">Desempenho de Vendas (Linha)</h2>
                    <div style="height: 200px;">
                        <canvas id="graficoVendasMensal"></canvas>
                    </div>
                </div>

                <section class="card-lista">
                    <div class="cabecalho-card-acoes">
                        <h2 class="titulo-card">Relatórios de Transações</h2>
                        <div class="grupo-botoes-relatorio">
                            <button class="btn-base btn-cancelar" onclick="gerarRelatorio('pdf')">
                                <i class="fas fa-file-pdf"></i> Exportar PDF
                            </button>
                            <button id="btn-xlsx-admin" class="btn-base btn-acao-principal" onclick="gerarRelatorio('xlsx')" style="display: inline-block;">
                                <i class="fas fa-file-excel"></i> Exportar Excel
                            </button>
                        </div>
                    </div>

                    <!-- Tabela populada pelos dados reais do SQLite -->
                    <div class="tabela-admins" id="tabela-vendas">
                        <!-- Cabeçalho fixo + linhas serão inseridas por JS -->
                    </div>
                </section>
            </main>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // =====================================================
        // DADOS REAIS EXTRAÍDOS DO SEU BANCO SQLite (.sql)
        // =====================================================
        const usuarios = {
            2: { id: 2, nome: "Comprador Teste", email: "comprador@teste.com" },
            3: { id: 3, nome: "Vendedor", email: "vendedor@teste.com", isadmin: 1 }
        };

        const produtos = {
            1: { id: 1, nome: "Produto Teste", foto: "https://via.placeholder.com/40" }
        };

        // VENDAS REAIS do seu banco SQLite
        const vendasBanco = [
            {
                id: 1,
                valor_unitario: 1500,
                quantidade: 1,
                data_venda: "2026-04-05",
                comprador_id: 2,
                vendedor_id: 3,
                produto_id: 1
            }
            // Adicione mais vendas aqui se houver no seu .sql completo
        ];

        // Processa os dados para exibição
        const vendasProcessadas = vendasBanco.map(venda => ({
            foto: produtos[venda.produto_id]?.foto || "https://via.placeholder.com/40",
            produto: produtos[venda.produto_id]?.nome || "Produto",
            data: new Date(venda.data_venda).toLocaleDateString("pt-BR"),
            valor: `R$ ${Number(venda.valor_unitario * venda.quantidade).toLocaleString("pt-BR", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })}`,
            comprador: `${usuarios[venda.comprador_id]?.nome || "Comprador"} (${usuarios[venda.comprador_id]?.email || "email@example.com"})`
        }));

        // Dados para gráfico (agrupado por mês)
        const graficoLabels = ["Abr/2026"];
        const graficoDados = [1500]; // Total da venda: 1500 * 1

        // =====================================================
        // FUNÇÕES PARA MONTAR A PÁGINA
        // =====================================================

        // Monta a tabela com dados reais
        function montarTabelaVendas() {
            const tabelaElement = document.getElementById("tabela-vendas");
            
            let html = `
                <div class="linha-cabecalho">
                    <span>Foto</span>
                    <span>Produto</span>
                    <span>Data</span>
                    <span>Valor</span>
                    <span>Comprador</span>
                </div>
            `;

            if (vendasProcessadas.length === 0) {
                html += `
                    <div class="linha-admin" style="justify-content: center; padding: 40px;">
                        <span class="texto-pagina">Nenhuma transação encontrada no histórico.</span>
                    </div>
                `;
            } else {
                vendasProcessadas.forEach(venda => {
                    html += `
                        <div class="linha-admin">
                            <span><img src="${venda.foto}" class="foto-venda-tabela" alt="Produto"></span>
                            <span>${venda.produto}</span>
                            <span>${venda.data}</span>
                            <span class="valor-venda">${venda.valor}</span>
                            <span>${venda.comprador}</span>
                        </div>
                    `;
                });
            }

            tabelaElement.innerHTML = html;
        }

        // Monta o gráfico com dados reais
        function montarGraficoVendas() {
            const ctx = document.getElementById("graficoVendasMensal");

            new Chart(ctx, {
                type: "line",
                data: {
                    labels: graficoLabels,
                    datasets: [{
                        label: "Valor de vendas (R$)",
                        data: graficoDados,
                        borderColor: "#4f46e5",
                        backgroundColor: "rgba(79, 70, 229, 0.1)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true, position: "top" }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => `R$ ${value.toLocaleString("pt-BR", {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })}`
                            }
                        }
                    }
                }
            });
        }

        // Simula geração de relatório
        function gerarRelatorio(formato) {
            const totalVendas = vendasProcessadas.length;
            const totalValor = graficoDados.reduce((sum, v) => sum + v, 0);
            
            alert(`Relatório ${formato.toUpperCase()} gerado!\n${totalVendas} vendas\nR$ ${totalValor.toLocaleString("pt-BR")}`);
        }

        // Configura visibilidade (admin vê Excel)
        function configurarPagina() {
            const usuarioAtual = usuarios[3]; // vendedor_id = 3
            document.getElementById("nome-usuario").textContent = usuarioAtual.nome;
            
            if (usuarioAtual.isadmin) {
                document.getElementById("btn-xlsx-admin").style.display = "inline-block";
            }
        }

        // Inicializa tudo quando carrega
        document.addEventListener("DOMContentLoaded", () => {
            montarTabelaVendas();
            montarGraficoVendas();
            configurarPagina();
        });
    </script>
</body>
</html>