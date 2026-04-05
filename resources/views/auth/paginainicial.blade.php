
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Página Inicialgit</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/paginainicial.css', 'resources/css/sidebar.css'])
</head>
<body>
    <div class="pagina">
        <aside class="conteiner-esq">
            <div class="logo-sistema">E-Commerce</div>
            <ul class="menu-lateral">
                <li class="item-menu">
                    <a href="{{ route('paginainicial') }}" class="link-menu">Página Inicial</a>
                </li>
                <li class="item-menu">
                    <a href="{{ route('categorias.index') }}" class="link-menu ativo">Categorias</a>
                </li>
                <li class="item-menu"><a href="#" class="link-menu">Usuários</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Pedidos</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Relatórios</a></li>
            </ul>
        </aside>

        <section class="conteiner-dir">
            <header class="topo">
                <div class="titulo-topo">Página Inicial</div>
                <div class="usuario-topo">Olá, {{ auth()->user()->nome }}</div>
            </header>

            <main class="conteudo">
                <div class="cabecalho-pagina">
                    <h1 class="titulo-pagina">Produtos anunciados</h1>
                    <p class="texto-pagina">Busque produtos pelo nome e filtre por categoria.</p>
                </div>

               <div class="linha-resumo">
    <div class="card-resumo">
        <div class="titulo-resumo">Produtos anunciados</div>
        <div class="numero-resumo">{{ $totalProdutos }}</div>
    </div>

    <div class="card-resumo">
        <div class="titulo-resumo">Itens vendidos</div>
        <div class="numero-resumo">{{ $totalVendas }}</div>
    </div>

    <div class="card-resumo">
        <div class="titulo-resumo">Categorias ativas</div>
        <div class="numero-resumo">{{ $totalCategorias }}</div>
    </div>
</div>

                <div class="bloco-principal">
                    <h2 class="titulo-bloco">Lista de produtos</h2>

                    <form action="{{ route('home') }}" method="GET" class="barra-filtro">
                        <input type="text" name="busca" class="input-busca" value="{{ request('busca') }}" placeholder="Buscar produto pelo nome..." />
                        
                        <select name="categoria" class="select-categoria">
        <option value="">Todas as categorias</option>
        @forelse((array) ($categorias ?? []) as $cat)
            <option value="{{ $cat->id ?? '' }}" 
                    {{ request('categoria') == ($cat->id ?? null) ? 'selected' : '' }}>
                {{ $cat->nome ?? '' }}
            </option>
        @empty
            <option disabled>Nenhuma categoria encontrada</option>
        @endforelse
    </select>
                        
                        <button type="submit" class="botao-filtrar">Filtrar</button>
                    </form>

                    <div class="grade-produtos">
                        @forelse($produtos as $produto)
                            <div class="card-produto">
                                <div class="imagem-produto">
                                    <img src="{{ asset('storage/' . $produto->foto) }}" alt="{{ $produto->nome }}" onerror="this.src='https://via.placeholder.com/150'">
                                </div>
                                <div class="corpo-produto">
                                    <span class="categoria-produto">{{ $produto->categoria->nome }}</span>
                                    <div class="nome-produto">{{ $produto->nome }}</div>
                                    <div class="preco-produto">R$ {{ number_format($produto->preco, 2, ',', '.') }}</div>
                                    <div class="acoes-produto">
                                        <button class="botao-comprar">Comprar</button>
                                        <button class="botao-ver">Ver</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="mensagem-vazia">Nenhum produto encontrado.</div>
                        @endforelse
                    </div>

                    <div class="paginacao">
                        {{ $produtos->links() }}
                    </div>
                </div>
            </main>
        </section>
    </div>

    @vite(['resources/js/paginainicial.js'])
</body>
</html>