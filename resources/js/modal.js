// Funções Gerais de Abertura e Fechamento
function abrirModal(idModal) {
    const modal = document.getElementById(idModal);
    if (modal) {
        modal.style.display = 'flex';
    }
}

function fecharModal(idModal) {
    const modal = document.getElementById(idModal);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Fecha o modal ao clicar na área externa
window.onclick = function(evento) {
    if (evento.target.classList.contains('modal-janela')) {
        evento.target.style.display = 'none';
    }
}

/**
 * Preenche e abre o modal de Visualização
 * Baseado na tabela 'produto' 
 */
function abrirModalVisualizar(id) {
    // Busca o botão que foi clicado para ler os atributos data-
    const btn = event.currentTarget;
    
    document.getElementById('view_nome').innerText = btn.getAttribute('data-nome');
    document.getElementById('view_descricao').innerText = btn.getAttribute('data-descricao') || 'Sem descrição';
    document.getElementById('view_categoria').innerText = btn.getAttribute('data-categoria');
    document.getElementById('view_vendedor').innerText = btn.getAttribute('data-vendedor');
    
    const fotoPath = btn.getAttribute('data-foto');
    document.getElementById('view_foto').src = fotoPath ? `/storage/${fotoPath}` : '/img/placeholder.png';

    abrirModal('modal-visualizar-produto');
}

/**
 * Preenche e abre o modal de Edição
 * Mapeia os inputs para as colunas: nome, descricao, categoria_id 
 */
function abrirModalEditar(id) {
    const btn = event.currentTarget;
    const form = document.getElementById('formProduto');
    
    // Altera a rota do formulário para o método UPDATE do Laravel
    form.action = `/produtos/${id}`;
    
    // Cria ou atualiza o campo _method para PUT (necessário no Laravel)
    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
    }

    form.querySelector('input[name="nome"]').value = btn.getAttribute('data-nome');
    form.querySelector('textarea[name="descricao"]').value = btn.getAttribute('data-descricao');
    form.querySelector('select[name="categoria_id"]').value = btn.getAttribute('data-categoria-id');

    abrirModal('modal-produto');
}

/**
 * Preenche e abre o modal de Exclusão
 * Exibe o preço formatado e o nome do produto [cite: 16, 21]
 */
function abrirModalExcluir(id) {
    const btn = event.currentTarget;
    const formDeletar = document.getElementById('formDeletar');
    
    // Define a rota de exclusão dinâmica
    formDeletar.action = `/produtos/${id}`;
    
    document.getElementById('del_nome').innerText = btn.getAttribute('data-nome');
    
    // Formata o preço (REAL no banco) para moeda brasileira 
    const preco = parseFloat(btn.getAttribute('data-preco'));
    document.getElementById('del_preco').innerText = preco.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });

    abrirModal('modal-deletar-produto');
}