<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Produtos - E-Commerce</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    @vite([
        'resources/css/global.css', 
        'resources/css/modal.css', 
        'resources/css/gerenciamentoadm.css', 
        'resources/css/gerenciamentoproduto.css',
        'resources/js/modal.js',
        'resources/js/gerenciamentoproduto.js'
    ])
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="pagina-admins">
        <aside class="conteiner-esq">
            <div class="logo-sistema">E-Commerce</div>
            <ul class="menu-lateral">
                <li class="item-menu"><a href="#" class="link-menu">Dashboard</a></li>
                <li class="item-menu"><a href="#" class="link-menu ativo">Produtos</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Vendas</a></li>
            </ul>
        </aside>

        <section class="conteiner-dir">
            <header class="topo-pagina">
                <div class="titulo-topo">Gerenciamento de Produtos</div>
                <div class="usuario-topo">Olá, {{ Auth::user()->nome }}</div>
            </header>

            <main class="conteudo-pagina">
                <div class="cabecalho-pagina">
                    <h1 class="titulo-pagina">Catálogo de Produtos</h1>
                    <p class="texto-pagina">Visualize e gerencie todos os itens anunciados no sistema.</p>
                </div>

                <div id="area-grafico-admin" class="card-lista" style="margin-bottom: 20px;">
                    <h2 class="titulo-card">Evolução de Cadastros (Últimos 12 meses)</h2>
                    <div style="height: 250px;">
                        <canvas id="graficoProdutosMensal"></canvas>
                    </div>
                </div>

                <section class="card-lista">
                    <div class="cabecalho-card-acoes"
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 class="titulo-card">Produtos Cadastrados</h2>
                        <button class="botao-principal" id="btnNovoProduto" onclick="abrirModal('modal-produto')">
                            <i class="fas fa-plus-circle"></i> Adicionar Produto
                        </button>
                    </div>

                    <div class="tabela-admins">
                        <div class="linha-cabecalho">
                            <span>Foto</span>
                            <span>Nome</span>
                            <span>Categoria</span>
                            <span>Ações</span>
                        </div>

                        @foreach($produtos as $produto)
                        <div class="linha-admin">
                            <span>
                                <img src="{{ asset('storage/' . $produto->foto) }}" class="foto-perfil-tabela" alt="Produto">
                            </span>
                            <span>{{ $produto->nome }}</span>
                            <span>{{ $produto->categoria->nome }}</span>
                            <div class="acoes-linha">
                                <button class="botao-acao visualizar" onclick="abrirModalVisualizar({{ $produto->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button class="botao-acao editar" onclick="abrirModalEditar({{ $produto->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="botao-acao excluir" onclick="abrirModalExcluir({{ $produto->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
            </main>
        </section>
    </div>

    <div id="modal-produto" class="modal-janela">
        <div class="modal-container">
            <header class="modal-cabecalho">
                <div class="modal-titulo"><i class="fas fa-box"></i> <span>Dados do Produto</span></div>
                <div class="modal-fechar" onclick="fecharModal('modal-produto')">&times;</div>
            </header>
            <form class="form-corpo" id="formProduto" method="POST" action="{{ route('produtos.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="campo-inteiro">
                    <label class="etiqueta">Nome do Produto <span class="obrigatorio">*</span></label>
                    <div class="caixa-input">
                        <i class="fas fa-tag"></i>
                        <input type="text" name="nome" placeholder="Ex: iPhone 15 Pro" required>
                    </div>
                </div>
                <div class="campo-inteiro">
                    <label class="etiqueta">Descrição <span class="obrigatorio">*</span></label>
                    <textarea class="input-simples" name="descricao" rows="3" placeholder="Detalhes do produto..." required></textarea>
                </div>
                <div class="campo-metade">
                    <label class="etiqueta">Categoria <span class="obrigatorio">*</span></label>
                    <select class="select-simples" name="categoria_id" required>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="campo-metade">
                    <label class="etiqueta">Foto</label>
                    <input type="file" name="foto" class="input-simples">
                </div>
                <footer class="modal-rodape">
                    <button type="button" class="btn-base btn-cancelar"
                        onclick="fecharModal('modal-produto')">Cancelar</button>
                    <button type="submit" class="btn-base btn-acao-principal">Salvar</button>
                </footer>
            </form>
        </div>
    </div>

    <div id="modal-visualizar-produto" class="modal-janela">
        <div class="modal-container">
            <header class="modal-cabecalho">
                <div class="modal-titulo">
                    <i class="fas fa-eye"></i>
                    <span>Detalhes do Produto</span>
                </div>
                <div class="modal-fechar" onclick="fecharModal('modal-visualizar-produto')">&times;</div>
            </header>

            <div class="modal-instrucao">Informações técnicas e categoria do item selecionado.</div>

            <div class="form-corpo">
                <div class="campo-inteiro visualizacao-item" style="text-align: center;">
                    <label class="etiqueta">Imagem do Produto</label>
                    <div style="margin-top: 10px;">
                        <img src="" id="view_foto" alt="Produto"
                            style="border-radius: 8px; border: 1px solid var(--cinza-borda); max-width: 150px;">
                    </div>
                </div>

                <div class="campo-inteiro visualizacao-item">
                    <label class="etiqueta">Nome do Produto</label>
                    <p class="texto-dados" id="view_nome"></p>
                </div>

                <div class="campo-inteiro visualizacao-item">
                    <label class="etiqueta">Descrição</label>
                    <p class="texto-dados" id="view_descricao"></p>
                </div>

                <div class="campo-metade visualizacao-item">
                    <label class="etiqueta">Categoria</label>
                    <p class="texto-dados" id="view_categoria"></p>
                </div>

                <div class="campo-metade visualizacao-item">
                    <label class="etiqueta">Anunciante</label>
                    <p class="texto-dados" id="view_vendedor"></p>
                </div>

                <footer class="modal-rodape">
                    <button type="button" class="btn-base btn-cancelar"
                        onclick="fecharModal('modal-visualizar-produto')">Fechar</button>
                </footer>
            </div>
        </div>
    </div>

    <div id="modal-deletar-produto" class="modal-janela">
        <div class="modal-container container-pequeno">
            <header class="modal-cabecalho alerta">
                <div class="modal-titulo">
                    <i class="fas fa-trash-alt"></i>
                    <span>Remover Anúncio</span>
                </div>
                <div class="modal-fechar" onclick="fecharModal('modal-deletar-produto')">&times;</div>
            </header>

            <div class="form-corpo centralizado">
                <div class="icone-alerta-circular">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3 class="titulo-confirmacao">Remover este produto?</h3>
                <p class="subtitulo-aviso">O item deixará de ser exibido na vitrine principal para compradores.</p>

                <div class="cartao-dados-exclusao">
                    <p><strong>Produto:</strong> <span id="del_nome"></span></p>
                    <p><strong>Categoria:</strong> <span id="del_categoria"></span></p>
                    <p><strong>Preço:</strong> R$ <span id="del_preco"></span></p>
                </div>

                <footer class="modal-rodape">
                    <button class="btn-base btn-cancelar" onclick="fecharModal('modal-deletar-produto')">Manter
                        Produto</button>
                    <form id="formDeletar" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-base btn-perigo">Confirmar Exclusão</button>
                    </form>
                </footer>
            </div>
        </div>
    </div>
</body>
</html>