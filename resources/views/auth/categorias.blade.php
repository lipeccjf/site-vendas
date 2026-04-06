<?php
// 1. Conexão com o Banco de Dados SQLite
try {
    $db = new PDO('sqlite:database.sqlite'); // Certifique-se que o arquivo .sqlite está na mesma pasta
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco: " . $e->getMessage());
}

// 2. Lógica para Processar Ações (Criar, Editar, Deletar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ação: CRIAR
    if (isset($_POST['acao']) && $_POST['acao'] === 'criar') {
        $nome = $_POST['nome'];
        if (!empty($nome)) {
            $stmt = $db->prepare("INSERT INTO categoria (nome) VALUES (:nome)");
            $stmt->bindParam(':nome', $nome);
            $stmt->execute();
        }
    }

    // Ação: EDITAR
    if (isset($_POST['acao']) && $_POST['acao'] === 'editar') {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $stmt = $db->prepare("UPDATE categoria SET nome = :nome WHERE id = :id");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Ação: EXCLUIR
    if (isset($_POST['acao']) && $_POST['acao'] === 'excluir') {
        $id = $_POST['id'];
        // O banco está com RESTRICT, então se houver produtos, o SQLite impedirá a exclusão automaticamente
        try {
            $stmt = $db->prepare("DELETE FROM categoria WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (Exception $e) {
            echo "<script>alert('Não é possível excluir: existem produtos vinculados a esta categoria.');</script>";
        }
    }
    
  
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$query = "SELECT c.id, c.nome,
          (SELECT COUNT(*) FROM produto p WHERE p.categoria_id = c.id) as total_produtos 
          FROM categoria c 
          ORDER BY c.id DESC";
$categorias = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Categorias </title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="categorias.css">
</head>
<body>
    <?php include 'sidebar.blade.php'; ?>

    <div class="pagina-usuarios">
        <div id="sidebar-container"></div>

        <section class="conteiner-dir">
            <header class="topo-pagina">
                <div class="titulo-topo">Configurações do Sistema</div>
                <div class="usuario-topo">Administrador Logado</div>
            </header>

            <main class="conteudo-pagina">
                <div class="cabecalho-pagina">
                    <h1 class="titulo-pagina">Categorias de Produtos</h1>
                    <p class="texto-pagina">Organize os departamentos do seu e-commerce.</p>
                </div>

                <div class="card-lista">
                    <div class="acoes-topo">
                        <h2 class="titulo-sessao">Listagem de Categorias</h2>
                        <button class="btn-adicionar" onclick="abrirModal('modal-criar')">
                            <i class="fas fa-plus"></i> Nova Categoria
                        </button>
                    </div>

                    <table class="tabela-padrao">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome da Categoria</th>
                                <th>Qtd. Produtos</th>
                                <th class="centralizado">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-categorias">
                            <?php foreach ($categorias as $cat): ?>
                            <tr>
                                <td><?= $cat['id'] ?></td>
                                <td><strong><?= htmlspecialchars($cat['nome']) ?></strong></td>
                                <td><?= $cat['total_produtos'] ?> itens</td>
                                <td class="centralizado">
                                    <button class="btn-acao editar" 
                                            onclick="prepararEdicao(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['nome']) ?>')" 
                                            title="Editar Categoria">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-acao excluir" 
                                            onclick="prepararExclusao(<?= $cat['id'] ?>)" 
                                            title="Excluir Categoria">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </section>
    </div>

    <div id="modal-criar" class="modal-camada">
        <div class="modal-caixa">
            <div class="modal-cabecalho">
                <h3>Cadastrar Nova Categoria</h3>
                <span class="fechar-x" onclick="fecharModal('modal-criar')">&times;</span>
            </div>
            <form class="modal-corpo" method="POST">
                <input type="hidden" name="acao" value="criar">
                <div class="campo-grupo">
                    <label>Nome da Categoria</label>
                    <input type="text" name="nome" placeholder="Ex: Informática" class="input-padrao" required>
                </div>
                <div class="modal-rodape">
                    <button type="button" class="btn-cancelar" onclick="fecharModal('modal-criar')">Cancelar</button>
                    <button type="submit" class="btn-confirmar">Salvar Categoria</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-editar" class="modal-camada">
        <div class="modal-caixa">
            <div class="modal-cabecalho">
                <h3>Editar Categoria</h3>
                <span class="fechar-x" onclick="fecharModal('modal-editar')">&times;</span>
            </div>
            <form class="modal-corpo" method="POST">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id" id="edit-id">
                <div class="campo-grupo">
                    <label>Nome da Categoria</label>
                    <input type="text" name="nome" id="edit-nome" class="input-padrao" required>
                </div>
                <div class="modal-rodape">
                    <button type="button" class="btn-cancelar" onclick="fecharModal('modal-editar')">Cancelar</button>
                    <button type="submit" class="btn-confirmar">Atualizar Dados</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-deletar" class="modal-camada">
        <div class="modal-caixa modal-alerta">
            <div class="modal-cabecalho cabecalho-perigo">
                <h3>Confirmar Exclusão</h3>
                <span class="fechar-x" onclick="fecharModal('modal-deletar')">&times;</span>
            </div>
            <form class="modal-corpo centralizado" method="POST">
                <input type="hidden" name="acao" value="excluir">
                <input type="hidden" name="id" id="delete-id">
                <i class="fas fa-trash-alt icone-aviso" style="font-size: 50px; color: #e53e3e; margin-bottom: 15px;"></i>
                <p>Você tem certeza que deseja excluir esta categoria? Esta ação removerá o vínculo com os produtos associados.</p>
                <div class="modal-rodape">
                    <button type="button" class="btn-cancelar" onclick="fecharModal('modal-deletar')">Voltar</button>
                    <button type="submit" class="btn-perigo" style="background: #c53030; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer;">Sim, Excluir</button>
                </div>
            </form>
        </div>
    </div>

    <script src="sidebar.js"></script>
    <script src="modal.js"></script>
    <script>
        // Funções auxiliares para popular os modais dinamicamente
        function prepararEdicao(id, nome) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nome').value = nome;
            abrirModal('modal-editar');
        }

        function prepararExclusao(id) {
            document.getElementById('delete-id').value = id;
            abrirModal('modal-deletar');
        }

        function abrirModal(id) {
            document.getElementById(id).style.display = 'flex';
        }

        function fecharModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        // Inclusão da sidebar
        fetch('sidebar.html')
            .then(res => res.text())
            .then(data => {
                document.getElementById('sidebar-container').innerHTML = data;
            });

        window.onclick = function(event) {
            if (event.target.className === 'modal-camada') {
                event.target.style.display = "none";
            }
        }
    </script>
</body>
</html>