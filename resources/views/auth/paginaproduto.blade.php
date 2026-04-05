<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Página de Produto - {{ $product->nome }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
    @vite(['resources/css/paginaproduto.css'])
    
</head>
<body>
    @include('auth.sidebar')
    
    <div class="pagina-produto">
        <aside class="conteiner-esq">
            <div class="logo-sistema">E-Commerce</div>
            <ul class="menu-lateral">
                <li class="item-menu"><a href="{{ route('dashboard') }}" class="link-menu">Dashboard</a></li>
                <li class="item-menu"><a href="#" class="link-menu ativo">Produtos</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Categorias</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Pedidos</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Relatórios</a></li>
            </ul>
        </aside>

        <section class="conteiner-dir">
            <header class="topo-pagina">
                <div class="titulo-topo">Página de Produto</div>
                <div class="usuario-topo">{{ Auth::user()->nome }} logado</div>
            </header>

            <main class="conteudo-pagina">
                <div class="cabecalho-pagina">
                    <h1 class="titulo-pagina">Detalhes do Produto</h1>
                    <p class="texto-pagina">Visualização individual com todas as informações exigidas no requisito.</p>
                </div>

                <div class="card-produto-detalhe">
                    <div class="grid-produto-detalhe">
                        <div class="coluna-imagem-produto">
                            @if($product->foto)
                                <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nome }}" class="caixa-imagem-produto">
                            @else
                                <div class="caixa-imagem-produto">👟</div>
                            @endif
                        </div>

                        <div class="coluna-info-produto">
                            <div class="cabecalho-info-produto">
                                <span class="badge-categoria">{{ $product->categoria->nome }}</span>
                                <h2 class="nome-produto">{{ $product->nome }}</h2>
                                <div class="preco-produto">R$ {{ number_format($product->preco, 2, ',', '.') }}</div>
                            </div>

                            <div class="lista-info-produto">
                                <div class="linha-info-produto">
                                    <span class="rotulo-info-produto">Quantidade:</span>
                                    <span class="valor-info-produto">{{ $product->quantidade }} unidades</span>
                                </div>

                                <div class="linha-info-produto">
                                    <span class="rotulo-info-produto">Categoria:</span>
                                    <span class="valor-info-produto">{{ $product->categoria->nome }}</span>
                                </div>

                                <div class="linha-info-produto">
                                    <span class="rotulo-info-produto">Anunciante:</span>
                                    <span class="valor-info-produto">{{ $product->usuario->nome }}</span>
                                </div>

                                <div class="linha-info-produto">
                                    <span class="rotulo-info-produto">Telefone:</span>
                                    <span class="valor-info-produto">{{ $product->usuario->telefone ?? '(32) 99999-0000' }}</span>
                                </div>
                            </div>

                            <div class="bloco-descricao-produto">
                                <h3 class="titulo-descricao">Descrição</h3>
                                <p class="texto-descricao">{{ $product->descricao }}</p>
                            </div>

                            <div class="acoes-produto">
                                @if(!Auth::user()->isAdmin())
                                    <button class="botao-comprar" id="botaoComprar">Comprar</button>
                                @endif
                                <a href="{{ route('dashboard') }}" class="botao-secundario">Voltar</a>
                            </div>

                            @if(Auth::user()->isAdmin())
                                <div class="mensagem-admin">
                                    O botão comprar foi ocultado porque o usuário logado é administrador.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </section>
    </div>

    <script src="{{ asset('js/paginaproduto.js') }}"></script>
</body>
</html>