<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Usuários</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/gerenciamentousuarios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
</head>
<body>
    <div class="pagina-usuarios">
        <aside class="conteiner-esq">
            <div class="logo-sistema">E-Commerce</div>
            <ul class="menu-lateral">
                <li class="item-menu"><a href="#" class="link-menu">Dashboard</a></li>
                <li class="item-menu"><a href="{{ route('admin.usuarios.index') }}" class="link-menu ativo">Usuários</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Produtos</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Pedidos</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Relatórios</a></li>
            </ul>
        </aside>

        <section class="conteiner-dir">
            <header class="topo-pagina">
                <div class="titulo-topo">Gerenciamento de Usuários</div>
                <div class="usuario-topo">{{ Auth::user()->nome ?? 'Administrador logado' }}</div>
            </header>

            <main class="conteudo-pagina">
                <div class="cabecalho-pagina">
                    <h1 class="titulo-pagina">Cadastro de Usuários</h1>
                    <p class="texto-pagina">Crie, visualize, edite e exclua usuários comuns.</p>
                </div>

                <div class="grid-layout">
                    <section class="card-formulario">
                        <h2 class="titulo-card">Novo Usuário</h2>
                        <form id="formUsuario">
                            <div class="grade-campos">
                                <input class="campo-texto" name="nome" type="text" placeholder="Nome" required>
                                <input class="campo-texto" name="email" type="email" placeholder="E-mail" required>
                                <input class="campo-texto" name="senha" type="password" placeholder="Senha" required>

                                <input class="campo-texto" name="cep" type="text" placeholder="CEP" id="cep" maxlength="9" required>
                                <input class="campo-texto" name="numero" type="text" placeholder="Número" required>

                                <input class="campo-texto campo-automatico" name="logradouro" type="text" placeholder="Logradouro" id="logradouro" readonly>
                                <input class="campo-texto campo-automatico" name="bairro" type="text" placeholder="Bairro" id="bairro" readonly>
                                <input class="campo-texto campo-automatico" name="cidade" type="text" placeholder="Cidade" id="cidade" readonly>
                                <input class="campo-texto campo-automatico" name="estado" type="text" placeholder="Estado" id="estado" readonly>

                                <input class="campo-texto" name="complemento" type="text" placeholder="Complemento (opcional)">
                                <input class="campo-texto" name="telefone" type="text" placeholder="Telefone">
                                                                <input class="campo-texto" name="datanascimento" type="date" placeholder="Data de nascimento">
                                <input class="campo-texto" name="cpf" type="text" placeholder="CPF" maxlength="14" required>
                                <input class="campo-texto" name="saldo" type="number" step="0.01" placeholder="Saldo" min="0">
                                <input class="campo-texto" name="foto" type="file" accept="image/*">
                            </div>

                            <div class="acoes-formulario">
                                <button type="submit" class="botao-principal" id="botaoSalvar">
                                    <i class="fas fa-save"></i> Salvar usuário
                                </button>
                                <button type="button" class="botao-secundario" onclick="limparFormulario()">
                                    Limpar
                                </button>
                            </div>

                            <div class="mensagem-formulario" id="mensagemFormulario">
                                Endereço preenchido automaticamente pelo CEP.
                            </div>
                        </form>
                    </section>

                    <section class="card-lista">
                        <h2 class="titulo-card">Usuários cadastrados ({{ $usuarios->count() ?? 0 }})</h2>

                        <div class="tabela-usuarios">
                            <div class="linha-cabecalho">
                                <span>Nome</span>
                                <span>E-mail</span>
                                <span>Telefone</span>
                                <span>Saldo</span>
                                <span>Ações</span>
                            </div>

                            @forelse($usuarios ?? [] as $usuario)
                            <div class="linha-usuario" data-id="{{ $usuario->id }}">
                                <span>{{ $usuario->nome }}</span>
                                <span>{{ $usuario->email }}</span>
                                <span>{{ $usuario->telefone ?? 'Não informado' }}</span>
                                <span>R$ {{ number_format($usuario->saldo ?? 0, 2, ',', '.') }}</span>
                                <div class="acoes-linha">
                                    <button class="botao-acao visualizar" onclick="abrirVisualizar({{ $usuario->id }})">
                                        <i class="fas fa-eye"></i> Ver
                                    </button>
                                    <button class="botao-acao editar" onclick="abrirEditar({{ $usuario->id }})">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button class="botao-acao excluir" onclick="abrirDeletar({{ $usuario->id }})">
                                        <i class="fas fa-trash"></i> Excluir
                                    </button>
                                </div>
                            </div>
                            @empty
                            <div class="linha-usuario vazio">
                                <span>Nenhum usuário cadastrado</span>
                            </div>
                            @endforelse
                        </div>
                    </section>
                </div>
            </main>
        </section>
    </div>

    {{-- Modal Visualizar --}}
    <div id="modal-visualizar" class="modal-janela" style="display: none;">
        <div class="modal-container">
            <header class="modal-cabecalho">
                <div class="modal-titulo">
                    <i class="fas fa-id-card"></i> Dados do Usuário
                </div>
                <div class="modal-fechar" onclick="fecharModal('modal-visualizar')">&times;</div>
            </header>
            <div class="modal-instrucao">Informações detalhadas do registro selecionado.</div>
            
            <div class="form-corpo">
                <div class="campo-inteiro visualizacao-item">
                    <label class="etiqueta">Nome completo</label>
                    <p class="texto-dados" id="view-nome">-</p>
                </div>
                <div class="campo-inteiro visualizacao-item">
                    <label class="etiqueta">E-mail</label>
                    <p class="texto-dados" id="view-email">-</p>
                </div>
                <div class="campo-metade visualizacao-item">
                    <label class="etiqueta">CPF</label>
                    <p class="texto-dados" id="view-cpf">-</p>
                </div>
                <div class="campo-metade visualizacao-item">
                    <label class="etiqueta">Saldo</label>
                    <p class="texto-dados" id="view-saldo">-</p>
                </div>
                <div class="campo-metade visualizacao-item">
                    <label class="etiqueta">Telefone</label>
                    <p class="texto-dados" id="view-telefone">-</p>
                </div>
                <div class="campo-metade visualizacao-item">
                    <label class="etiqueta">Bairro</label>
                    <p class="texto-dados" id="view-bairro">-</p>
                </div>
                
                <footer class="modal-rodape">
                    <button class="btn-base btn-cancelar" onclick="fecharModal('modal-visualizar')">Fechar</button>
                </footer>
            </div>
        </div>
    </div>

    {{-- Modal Editar --}}
    <div id="modal-editar" class="modal-janela" style="display: none;">
        <div class="modal-container">
            <header class="modal-cabecalho">
                <div class="modal-titulo">
                    <i class="fas fa-edit"></i> Editar Usuário
                </div>
                <div class="modal-fechar" onclick="fecharModal('modal-editar')">&times;</div>
            </header>
            <div class="modal-instrucao">Atualize as informações do usuário.</div>
            
            <form id="formEditarUsuario" class="form-corpo">
                <input type="hidden" id="edit-id">
                <div class="campo-inteiro">
                    <label class="etiqueta">Nome completo <span class="obrigatorio">*</span></label>
                    <div class="caixa-input">
                        <i class="fas fa-user"></i>
                        <input type="text" id="edit-nome" name="nome" required>
                    </div>
                </div>

                <div class="campo-inteiro">
                    <label class="etiqueta">E-mail <span class="obrigatorio">*</span></label>
                    <div class="caixa-input">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="edit-email" name="email" required>
                    </div>
                </div>

                <div class="campo-metade">
                    <label class="etiqueta">CPF <span class="obrigatorio">*</span></label>
                    <input type="text" id="edit-cpf" name="cpf" class="input-simples" required>
                </div>

                <div class="campo-metade">
                    <label class="etiqueta">Telefone</label>
                    <input type="text" id="edit-telefone" name="telefone" class="input-simples">
                </div>

                <div class="campo-metade">
                    <label class="etiqueta">CEP <span class="obrigatorio">*</span></label>
                    <input type="text" id="edit-cep" name="cep" class="input-simples" maxlength="9" required onblur="buscarCEPEdit(this.value)">
                </div>

                <div class="campo-metade">
                    <label class="etiqueta">Número <span class="obrigatorio">*</span></label>
                    <input type="text" id="edit-numero" name="numero" class="input-simples" required>
                </div>

                <div class="campo-metade">
                    <label class="etiqueta">Logradouro</label>
                    <input type="text" id="edit-logradouro" name="logradouro" class="input-simples campo-automatico" readonly>
                </div>

                <div class="campo-metade">
                    <label class="etiqueta">Bairro</label>
                    <input type="text" id="edit-bairro" name="bairro" class="input-simples campo-automatico" readonly>
                </div>

                <div class="campo-metade">
                    <label class="etiqueta">Cidade</label>
                    <input type="text" id="edit-cidade" name="cidade" class="input-simples campo-automatico" readonly>
                </div>

                <div class="campo-metade">
                    <label class="etiqueta">Estado</label>
                    <input type="text" id="edit-estado" name="estado" class="input-simples campo-automatico" readonly>
                </div>

                <div class="campo-inteiro">
                    <label class="etiqueta">Foto de Perfil (Opcional)</label>
                    <input type="file" id="edit-foto" name="foto" class="input-simples">
                </div>

                <footer class="modal-rodape">
                    <button type="button" class="btn-base btn-cancelar" onclick="fecharModal('modal-editar')">Cancelar</button>
                    <button type="submit" class="btn-base btn-acao-principal">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                </footer>
            </form>
        </div>
    </div>

    {{-- Modal Deletar --}}
    <div id="modal-deletar" class="modal-janela" style="display: none;">
        <div class="modal-container container-pequeno">
            <header class="modal-cabecalho alerta">
                <div class="modal-titulo">
                    <i class="fas fa-trash"></i> Excluir Usuário
                </div>
                <div class="modal-fechar" onclick="fecharModal('modal-deletar')">&times;</div>
            </header>
            
            <div class="form-corpo centralizado">
                <div class="icone-alerta-circular">
                    <i class="fas fa-trash"></i>
                </div>
                <h3 class="titulo-confirmacao" id="delete-titulo">Tem certeza?</h3>
                <p class="subtitulo-aviso">Esta ação não poderá ser desfeita.</p>

                <div class="cartao-dados-exclusao" id="delete-dados">
                    <!-- Preenchido via JS -->
                </div>

                <ul class="lista-alertas">
                    <li>O usuário será removido do sistema.</li>
                    <li>Todas as sessões ativas serão encerradas.</li>
                    <li>Esta ação é irreversível.</li>
                </ul>

                <footer class="modal-rodape">
                    <button type="button" class="btn-base btn-cancelar" onclick="fecharModal('modal-deletar')">Cancelar</button>
                    <button type="button" class="btn-base btn-perigo" id="btnDeletar">
                        <i class="fas fa-trash"></i> Excluir Usuário
                    </button>
                </footer>
            </div>
        </div>
    </div>
</body>

<script>
let usuarioEditando = null;

document.getElementById('formUsuario').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('{{ route('admin.usuarios.store') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erro ao salvar: ' + (data.message || 'Tente novamente'));
        }
    });
});

document.getElementById('formEditarUsuario').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch(`/admin/usuarios/${usuarioEditando}`, {
        method: 'PUT',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erro ao atualizar: ' + (data.message || 'Tente novamente'));
        }
    });
});

function abrirVisualizar(id) {
    fetch(`/admin/usuarios/${id}`)
        .then(r => r.json())
        .then(usuario => {
            document.getElementById('view-nome').textContent = usuario.nome;
            document.getElementById('view-email').textContent = usuario.email;
            document.getElementById('view-cpf').textContent = usuario.cpf;
            document.getElementById('view-saldo').textContent = 'R$ ' + parseFloat(usuario.saldo).toLocaleString('pt-BR', {minimumFractionDigits: 2});
            document.getElementById('view-telefone').textContent = usuario.telefone || 'Não informado';
            document.getElementById('view-bairro').textContent = usuario.endereco?.bairro || 'Não informado';
            document.getElementById('modal-visualizar').style.display = 'block';
        });
}

function abrirEditar(id) {
    fetch(`/admin/usuarios/${id}`)
        .then(r => r.json())
        .then(usuario => {
            usuarioEditando = id;
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nome').value = usuario.nome;
            document.getElementById('edit-email').value = usuario.email;
            document.getElementById('edit-cpf').value = usuario.cpf;
            document.getElementById('edit-telefone').value = usuario.telefone || '';
            document.getElementById('edit-cep').value = usuario.endereco?.cep || '';
            document.getElementById('edit-numero').value = usuario.endereco?.numero || '';
            document.getElementById('edit-logradouro').value = usuario.endereco?.logradouro || '';
            document.getElementById('edit-bairro').value = usuario.endereco?.bairro || '';
            document.getElementById('edit-cidade').value = usuario.endereco?.cidade || '';
            document.getElementById('edit-estado').value = usuario.endereco?.estado || '';
            document.getElementById('modal-editar').style.display = 'block';
        });
}

function abrirDeletar(id) {
    fetch(`/admin/usuarios/${id}`)
        .then(r => r.json())
        .then(usuario => {
            usuarioEditando = id;
            document.getElementById('delete-titulo').textContent = `Excluir ${usuario.nome}?`;
            document.getElementById('delete-dados').innerHTML = `
                <p><strong>Nome:</strong> ${usuario.nome}</p>
                <p><strong>E-mail:</strong> ${usuario.email}</p>
                <p><strong>CPF:</strong> ${usuario.cpf}</p>
            `;
            document.getElementById('btnDeletar').onclick = () => deletarUsuario(id);
            document.getElementById('modal-deletar').style.display = 'block';
        });
}

function deletarUsuario(id) {
    fetch(`/admin/usuarios/${id}`, { method: 'DELETE' })
        .then(() => location.reload());
}

function fecharModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function limparFormulario() {
    document.getElementById('formUsuario').reset();
    limparCamposEndereco();
}

document.getElementById('cep').addEventListener('blur', function() {
    buscarCEP(this.value);
});

document.getElementById('edit-cep').addEventListener('blur', function() {
    buscarCEPEdit(this.value);
});

function buscarCEP(cep) {
    if (cep.length === 9) {
        fetch(`https://viacep.com.br/ws/${cep.replace(/\D/g,'')}/json/`)
            .then(r => r.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('logradouro').value = data.logradouro;
                    document.getElementById('bairro').value = data.bairro;
                    document.getElementById('cidade').value = data.localidade;
                    document.getElementById('estado').value = data.uf;
                }
            });
    }
}

function buscarCEPEdit(cep) {
    buscarCEP(cep); 
}

function limparCamposEndereco() {
    ['logradouro', 'bairro', 'cidade', 'estado'].forEach(id => {
        document.getElementById(id).value = '';
    });
}
    @vite['resources/js/gerenciamentousuarios.js']
</script>
</body>
</html>
