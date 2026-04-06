<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Compras</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/gerenciamentoadm.css') }}">
    <link rel="stylesheet" href="{{ asset('css/historicodecompra.css') }}">
</head>
<body>

<?php include 'sidebar.blade.php'; ?>
    <div class="pagina-admins">
        <aside class="conteiner-esq">
            <div class="logo-sistema">E-Commerce</div>
            <ul class="menu-lateral">
                <li class="item-menu"><a href="#" class="link-menu">Dashboard</a></li>
                <li class="item-menu"><a href="#" class="link-menu ativo">Minhas Compras</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Produtos</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Configurações</a></li>
            </ul>
        </aside>

        <section class="conteiner-dir">
            <header class="topo-pagina">
                <div class="titulo-topo">RF008 - Histórico de Compras</div>
                <div class="usuario-topo">Olá, {{ Auth::user()->name ?? 'Usuário' }}</div>
            </header>

            <main class="conteudo-pagina">
                <div class="cabecalho-pagina">
                    <h1 class="titulo-pagina">Histórico de Compras</h1>
                    <p class="texto-pagina">Consulte suas transações passadas e emita relatórios oficiais em PDF.</p>
                </div>

                <section class="card-lista">
                    <div class="cabecalho-card-acoes">
                        <h2 class="titulo-card">Suas Transações</h2>
                        <a href="{{ route('pdf.compras') }}" class="btn-base btn-acao-principal" target="_blank">
                            <i class="fas fa-file-pdf"></i> Gerar Relatório PDF
                        </a>
                    </div>

                    <div class="tabela-admins">
                        <div class="linha-cabecalho">
                            <span>Foto</span>
                            <span>Produto</span>
                            <span>Data</span>
                            <span>Valor</span>
                            <span>Ações</span>
                        </div>

                        @forelse($purchases as $purchase)
                        <div class="linha-admin" data-id="{{ $purchase->id }}">
                            <span>
                                <img src="{{ $purchase->product_image ?? 'https://via.placeholder.com/40' }}" 
                                     class="foto-produto-tabela" alt="Produto">
                            </span>
                            <span>{{ $purchase->product_name }}</span>
                            <span>{{ $purchase->purchase_date->format('d/m/Y') }}</span>
                            <span class="valor-compra">R$ {{ number_format($purchase->value, 2, ',', '.') }}</span>
                            <div class="acoes-linha">
                                <button class="botao-acao visualizar" onclick="abrirDetalhes({{ $purchase->id }})">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="linha-admin">
                            <span colspan="5">Nenhuma compra encontrada.</span>
                        </div>
                        @endforelse
                    </div>
                </section>
            </main>
        </section>
    </div>

    <div id="modal-detalhes-compra" class="modal-janela" style="display: none;">
        <div class="modal-container">
            <header class="modal-cabecalho">
                <div class="modal-titulo"><i class="fas fa-receipt"></i> Comprovante</div>
                <div class="modal-fechar" onclick="fecharModal()">&times;</div>
            </header>
            <div class="form-corpo">
                <div class="campo-inteiro visualizacao-item">
                    <label class="etiqueta">Vendedor</label>
                    <p class="texto-dados" id="modal-vendedor">-</p>
                </div>
                <div class="campo-metade visualizacao-item">
                    <label class="etiqueta">Categoria</label>
                    <p class="texto-dados" id="modal-categoria">-</p>
                </div>
                <div class="campo-metade visualizacao-item">
                    <label class="etiqueta">Método</label>
                    <p class="texto-dados" id="modal-metodo">-</p>
                </div>
            </div>
            <footer class="modal-rodape">
                <button class="btn-base btn-cancelar" onclick="fecharModal()">Fechar</button>
            </footer>
        </div>
    </div>

    <script>
        function abrirDetalhes(id) {
            fetch(`/purchase/detalhes/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-vendedor').textContent = data.seller;
                    document.getElementById('modal-categoria').textContent = data.category;
                    document.getElementById('modal-metodo').textContent = data.payment_method;
                    document.getElementById('modal-detalhes-compra').style.display = 'block';
                });
        }

        function fecharModal() {
            document.getElementById('modal-detalhes-compra').style.display = 'none';
        }

        function gerarRelatorioPDF() {
            window.open("{{ route('pdf.compras') }}", '_blank');
        }
    </script>
</body>
</html>