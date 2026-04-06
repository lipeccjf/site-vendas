@extends('layouts.admin')

@section('content')
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gerenciamento de Administradores</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    @vite(['resources/css/gerenciamentoadm.css', 'resources/css/modal.css', 'resources/css/global.css'])
</head>
<body>
    <?php include 'sidebar.blade.php'; ?>
    <div class="pagina-admins">
        <aside class="conteiner-esq">
            <div class="logo-sistema">E-Commerce</div>
            <ul class="menu-lateral">
                <li class="item-menu"><a href="#" class="link-menu">Dashboard</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Usuários</a></li>
                <li class="item-menu"><a href="#" class="link-menu ativo">Administradores</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Produtos</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Relatórios</a></li>
            </ul>
        </aside>

        <section class="conteiner-dir">
            <header class="topo-pagina">
                <div class="titulo-topo">Gerenciamento de Administradores</div>
                <div class="usuario-topo">Olá, {{ Auth::user()->nome }}</div>
            </header>

            <main class="conteudo-pagina">
                <div class="cabecalho-pagina">
                    <h1 class="titulo-pagina">Cadastro de Administradores</h1>
                    <p class="texto-pagina">Crie, visualize, edite e exclua administradores do sistema.</p>
                </div>

                <div class="grid-layout">
                    <section class="card-formulario">
                        <h2 class="titulo-card">Novo Administrador</h2>

                        <form action="{{ route('admins.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grade-campos">
                                <input class="campo-texto" type="text" name="nome" placeholder="Nome" required>
                                <input class="campo-texto" type="email" name="email" placeholder="E-mail" required>
                                <input class="campo-texto" type="password" name="senha" placeholder="Senha" required>
                                <input class="campo-texto" type="text" name="logradouro" placeholder="Endereço">
                                <input class="campo-texto" type="text" name="telefone" placeholder="Telefone">
                                <input class="campo-texto" type="date" name="data_nascimento" placeholder="Data de nascimento">
                                <input class="campo-texto" type="text" name="cpf" placeholder="CPF" required>
                                <input class="campo-texto" type="file" name="foto">
                            </div>

                            <div class="acoes-formulario">
                                <button type="submit" class="botao-principal" id="botaoSalvar">Salvar administrador</button>
                                <button class="botao-secundario" type="reset">Limpar</button>
                            </div>
                        </form>

                        @if(session('success'))
                            <div class="mensagem-formulario" id="mensagemFormulario" style="display: block;">
                                {{ session('success') }}
                            </div>
                        @endif
                    </section>

                    <section class="card-lista">
                        <h2 class="titulo-card">Administradores cadastrados</h2>

                        <div class="tabela-admins">
                            <div class="linha-cabecalho">
                                <span>Nome</span>
                                <span>E-mail</span>
                                <span>Telefone</span>
                                <span>Ações</span>
                            </div>

                            @foreach($administradores as $admin)
                            <div class="linha-admin">
                                <span>{{ $admin->nome }}</span>
                                <span>{{ $admin->email }}</span>
                                <span>{{ $admin->telefone ?? 'N/A' }}</span>
                                <div class="acoes-linha">
                                    <button class="botao-acao visualizar" 
                                            onclick="abrirModalVisualizarAdmin({{ $admin->id }})"
                                            data-nome="{{ $admin->nome }}"
                                            data-email="{{ $admin->email }}"
                                            data-cpf="{{ $admin->cpf }}"
                                            data-telefone="{{ $admin->telefone }}">Ver</button>
                                    
                                    <button class="botao-acao editar" 
                                            onclick="abrirModalEditarAdmin({{ $admin->id }})"
                                            data-nome="{{ $admin->nome }}"
                                            data-email="{{ $admin->email }}"
                                            data-cpf="{{ $admin->cpf }}"
                                            data-logradouro="{{ $admin->endereco->logradouro ?? '' }}"
                                            data-numero="{{ $admin->endereco->numero ?? '' }}"
                                            data-bairro="{{ $admin->endereco->bairro ?? '' }}"
                                            data-telefone="{{ $admin->telefone }}">Editar</button>
                                    
                                    <button class="botao-acao excluir" 
                                            onclick="abrirModalDeletarAdmin({{ $admin->id }})"
                                            data-nome="{{ $admin->nome }}"
                                            data-email="{{ $admin->email }}"
                                            data-cpf="{{ $admin->cpf }}">Excluir</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </main>
        </section>
    </div>

    <div id="modal-visualizar" class="modal-janela">
        <div class="modal-container">
            <header class="modal-cabecalho">
                <div class="modal-titulo"><i class="fas fa-eye"></i> <span>Visualizar Administrador</span></div>
                <div class="modal-fechar" onclick="fecharModal('modal-visualizar')">&times;</div>
            </header>
            <div class="form-corpo">
                <div class="campo-inteiro visualizacao-item">
                    <label class="etiqueta">Nome</label>
                    <p class="texto-dados" id="view-nome"></p>
                </div>
                <div class="campo-inteiro visualizacao-item">
                    <label class="etiqueta">E-mail</label>
                    <p class="texto-dados" id="view-email"></p>
                </div>
                <div class="campo-metade visualizacao-item">
                    <label class="etiqueta">CPF</label>
                    <p class="texto-dados" id="view-cpf"></p>
                </div>
                <div class="campo-metade visualizacao-item">
                    <label class="etiqueta">Telefone</label>
                    <p class="texto-dados" id="view-telefone"></p>
                </div>
                <footer class="modal-rodape">
                    <button type="button" class="btn-base btn-cancelar" onclick="fecharModal('modal-visualizar')">Fechar</button>
                </footer>
            </div>
        </div>
    </div>

    <div id="modal-editar" class="modal-janela">
        <div class="modal-container">
            <header class="modal-cabecalho">
                <div class="modal-titulo"><i class="fas fa-edit"></i> <span>Editar Administrador</span></div>
                <div class="modal-fechar" onclick="fecharModal('modal-editar')">&times;</div>
            </header>
            <div class="modal-instrucao">Atualize as informações do administrador.</div>
            <form class="form-corpo" id="formEditarAdmin" method="POST">
                @csrf
                @method('PUT')
                <div class="campo-inteiro">
                    <label class="etiqueta">Nome completo <span class="obrigatorio">*</span></label>
                    <div class="caixa-input">
                        <i class="fas fa-user"></i>
                        <input type="text" name="nome" id="edit-nome" required>
                    </div>
                </div>
                <div class="campo-inteiro">
                    <label class="etiqueta">E-mail <span class="obrigatorio">*</span></label>
                    <div class="caixa-input">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" id="edit-email" required>
                    </div>
                </div>
                <div class="campo-inteiro">
                    <label class="etiqueta">CPF <span class="obrigatorio">*</span></label>
                    <div class="caixa-input">
                        <input type="text" name="cpf" id="edit-cpf" required>
                    </div>
                </div>
                <div class="campo-metade">
                    <label class="etiqueta">Telefone</label>
                    <input type="text" name="telefone" id="edit-telefone" class="input-simples">
                </div>
                <div class="campo-metade">
                    <label class="etiqueta">Número</label>
                    <input type="text" name="numero" id="edit-numero" class="input-simples">
                </div>
                <div class="campo-metade">
                    <label class="etiqueta">Logradouro</label>
                    <input type="text" name="logradouro" id="edit-logradouro" class="input-simples">
                </div>
                <div class="campo-metade">
                    <label class="etiqueta">Bairro</label>
                    <input type="text" name="bairro" id="edit-bairro" class="input-simples">
                </div>
                <footer class="modal-rodape">
                    <button type="button" class="btn-base btn-cancelar" onclick="fecharModal('modal-editar')">Cancelar</button>
                    <button type="submit" class="btn-base btn-acao-principal"><i class="fas fa-save"></i> Salvar Alterações</button>
                </footer>
            </form>
        </div>
    </div>

    <div id="modal-deletar" class="modal-janela">
        <div class="modal-container container-pequeno">
            <header class="modal-cabecalho alerta">
                <div class="modal-titulo"><i class="fas fa-trash"></i> <span>Excluir Administrador</span></div>
                <div class="modal-fechar" onclick="fecharModal('modal-deletar')">&times;</div>
            </header>
            <div class="form-corpo centralizado">
                <div class="icone-alerta-circular" style="color: #c53030; background: #fff5f5;">
                    <i class="fas fa-trash"></i>
                </div>
                <h3 class="titulo-confirmacao">Tem certeza que deseja excluir?</h3>
                <p class="subtitulo-aviso">Esta ação não poderá ser desfeita.</p>
                
                <div class="cartao-dados-exclusao">
                   <p><strong>Nome:</strong> <span id="del-nome"></span></p>
                   <p><strong>E-mail:</strong> <span id="del-email"></span></p>
                   <p><strong>CPF:</strong> <span id="del-cpf"></span></p>
                </div>

                <footer class="modal-rodape">
                    <button class="btn-base btn-cancelar" onclick="fecharModal('modal-deletar')">Cancelar</button>
                    <form id="formDeletarAdmin" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-base btn-perigo"><i class="fas fa-trash"></i> Excluir Administrador</button>
                    </form>
                </footer>
            </div>
        </div>
    </div>

    @vite(['resources/js/modal.js'])
</body>
</html>
@endsection