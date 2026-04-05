<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/paginacompra.css'])
    @vite(['resources/css/global.css'])
</head>
<body>
    <div class="pagina-compra">
        <!-- Sidebar igual ao HTML original -->
        <aside class="conteiner-esq">
            <div class="logo-sistema">E-Commerce</div>
            <ul class="menu-lateral">
                <li class="item-menu"><a href="#" class="link-menu">Dashboard</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Produtos</a></li>
                <li class="item-menu"><a href="#" class="link-menu ativo">Compra</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Pedidos</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Relatórios</a></li>
            </ul>
        </aside>

        <section class="conteiner-dir">
            <header class="topo-pagina">
                <div class="titulo-topo">Compra</div>
                <div class="usuario-topo">Usuário logado</div>
            </header>

            <main class="conteudo-pagina">
                <div class="cabecalho-pagina">
                    <h1 class="titulo-pagina">Finalizar Compra</h1>
                    <p class="texto-pagina">Resumo do pedido e forma de pagamento para checkout.</p>
                </div>

                @if (session('success'))
                    <div class="mensagem-sucesso">{{ session('success') }}</div>
                @endif

                <form action="{{ route('compra.confirmar') }}" method="POST" id="formCompra">
                    @csrf
                    <div class="grid-compra">
                        <section class="card-pedido">
                            <h2 class="titulo-card">Resumo do Pedido</h2>
                            
                            @foreach($carrinho as $item)
                                <div class="item-resumo">
                                    <div class="info-item">
                                        <span class="icone-item">🛒</span>
                                        <div>
                                            <strong>{{ $item->produto->nome }}</strong>
                                            <p>Quantidade: {{ $item->quantidade }}</p>
                                        </div>
                                    </div>
                                    <span class="preco-item">R$ {{ number_format($item->produto->preco * $item->quantidade, 2, ',', '.') }}</span>
                                </div>
                            @endforeach
                            
                            <div class="item-resumo">
                                <div class="info-item">
                                    <span class="icone-item">📦</span>
                                    <div>
                                        <strong>Frete estimado</strong>
                                        <p>Prazo médio de entrega</p>
                                    </div>
                                </div>
                                <span class="preco-item">R$ {{ number_format($frete, 2, ',', '.') }}</span>
                            </div>

                            <div class="item-resumo total">
                                <span>Total</span>
                                <strong>R$ {{ number_format($totalGeral, 2, ',', '.') }}</strong>
                            </div>
                        </section>

                        <section class="card-pagamento">
                            <h2 class="titulo-card">Forma de Pagamento</h2>
                            <div class="bloco-pagamento">
                                <label class="opcao-pagamento ativa">
                                    <input type="radio" name="pagamento" value="PagSeguro" checked>
                                    <span>PagSeguro</span>
                                </label>
                                <label class="opcao-pagamento">
                                    <input type="radio" name="pagamento" value="Cartão de crédito">
                                    <span>Cartão de crédito</span>
                                </label>
                                <label class="opcao-pagamento">
                                    <input type="radio" name="pagamento" value="Boleto">
                                    <span>Boleto</span>
                                </label>
                            </div>

                            <div class="bloco-dados">
                                <div class="linha-dado"><strong>Nome:</strong> {{ $carrinho->first()->usuario->nome ?? 'João da Silva' }}</div>
                                <div class="linha-dado"><strong>Quantidade total:</strong> {{ $carrinho->sum('quantidade') }}</div>
                                <div class="linha-dado"><strong>Preço itens:</strong> R$ {{ number_format($totalItens, 2, ',', '.') }}</div>
                            </div>

                            <button type="submit" class="botao-confirmar">Confirmar Compra</button>
                        </section>
                    </div>
                </form>
            </main>
        </section>
    </div>
</body>
</html>