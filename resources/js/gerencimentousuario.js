document.addEventListener('DOMContentLoaded', () => {
    const cep = document.getElementById('cep');
    const cepEdit = document.getElementById('edit-cep');

    if (cep) {
        cep.addEventListener('blur', buscarCEP);
    }

    if (cepEdit) {
        cepEdit.addEventListener('blur', buscarCEP);
    }
});

async function buscarCEP(event) {
    const cepValue = event.target.value.replace(/\D/g, '');
    
    if (cepValue.length === 8) {
        try {
            const response = await fetch(`https://viacep.com.br/ws/${cepValue}/json/`);
            const data = await response.json();

            if (!data.erro) {
                const prefix = event.target.id.includes('edit') ? 'edit-' : '';
                document.getElementById(`${prefix}logradouro`).value = data.logradouro;
                document.getElementById(`${prefix}bairro`).value = data.bairro;
                document.getElementById(`${prefix}cidade`).value = data.localidade;
                document.getElementById(`${prefix}estado`).value = data.uf;
            }
        } catch (error) {
            console.error('Erro CEP:', error);
        }
    }
}

function abrirVisualizar(id) {
    fetch(`/admin/usuarios/${id}`)
        .then(r => r.json())
        .then(data => {
            ['nome', 'email', 'cpf', 'telefone'].forEach(campo => {
                document.getElementById(`view-${campo}`).textContent = data[campo] || 'Não informado';
            });
            document.getElementById('view-saldo').textContent = 'R$ ' + (data.saldo || 0).toLocaleString('pt-BR', {minimumFractionDigits: 2});
            document.getElementById('view-bairro').textContent = data.endereco?.bairro || 'Não informado';
            document.getElementById('modal-visualizar').style.display = 'block';
        });
}

function abrirEditar(id) {
    fetch(`/admin/usuarios/${id}`)
        .then(r => r.json())
        .then(data => {
            usuarioEditando = id;
            ['nome', 'email', 'cpf', 'telefone'].forEach(campo => {
                document.getElementById(`edit-${campo}`).value = data[campo] || '';
            });
            ['cep', 'numero', 'logradouro', 'bairro', 'cidade', 'estado'].forEach(campo => {
                document.getElementById(`edit-${campo}`).value = data.endereco?.[campo] || '';
            });
            document.getElementById('modal-editar').style.display = 'block';
        });
}

function abrirDeletar(id) {
    fetch(`/admin/usuarios/${id}`)
        .then(r => r.json())
        .then(data => {
            usuarioEditando = id;
            document.getElementById('delete-titulo').textContent = `Excluir ${data.nome}?`;
            document.getElementById('delete-dados').innerHTML = `
                <p><strong>Nome:</strong> ${data.nome}</p>
                <p><strong>E-mail:</strong> ${data.email}</p>
                <p><strong>CPF:</strong> ${data.cpf}</p>
            `;
            document.getElementById('btnDeletar').onclick = () => deletarUsuario(id);
            document.getElementById('modal-deletar').style.display = 'block';
        });
}

function deletarUsuario(id) {
    fetch(`/admin/usuarios/${id}`, { method: 'DELETE' })
        .then(() => location.reload());
}

function fecharModal(id) {
    document.getElementById(id).style.display = 'none';
}

function limparFormulario() {
    document.getElementById('formUsuario').reset();
    ['logradouro', 'bairro', 'cidade', 'estado'].forEach(id => {
        document.getElementById(id).value = '';
    });
}

window.onclick = (event) => {
    if (event.target.classList.contains('modal-janela')) {
        event.target.style.display = 'none';
    }
};

let usuarioEditando = null;