<?php
// 1. Conexão com o Banco de Dados
try {
    $db = new PDO('sqlite:database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco: " . $e->getMessage());
}

// Simulação de usuário logado (ID 2 conforme consta no seu dump de vendas)
$usuario_id = 2; 

// 2. Lógica para remover item do carrinho
if (isset($_GET['remover'])) {
    $id_carrinho = $_GET['remover'];
    $stmt = $db->prepare("DELETE FROM carrinho WHERE id = :id AND usuario_id = :user");
    $stmt->bindParam(':id', $id_carrinho);
    $stmt->bindParam(':user', $usuario_id);
    $stmt->execute();
    
    header("Location: carrinho.php");
    exit;
}

// 3. Buscar dados do usuário (para o "Olá, Nome")
$stmtUser = $db->prepare("SELECT nome FROM usuario WHERE id = :id");
$stmtUser->execute([':id' => $usuario_id]);
$usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);
$nomeUsuario = $usuario ? $usuario['nome'] : 'Visitante';

// 4. Buscar itens do carrinho com JOIN na tabela produto
$query = "SELECT c.id as carrinho_id, c.quantidade, p.nome, p.preco, p.foto 
          FROM carrinho c 
          JOIN produto p ON c.produto_id = p.id 
          WHERE c.usuario_id = :user_id";

$stmt = $db->prepare($query);
$stmt->execute([':user_id' => $usuario_id]);
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 5. Cálculo do Total Geral
$totalGeral = 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce - Carrinho</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="carrinho.css">
</head>
<body>
<?php include 'sidebar.blade.php'; ?>
    <div class="pagina-usuarios">
        <div id="sidebar-container"></div>

        <section class="conteiner-dir">
            <header class="topo-pagina">
                <div class="titulo-topo">Finalizar Pedido</div>
                <div class="usuario-topo">Olá, <?= htmlspecialchars($nomeUsuario) ?></div>
            </header>

            <main class="conteudo-pagina">
                <div class="card-carrinho">
                    <div class="card-header-azul">Meus Itens</div>
                    
                    <div class="tabela-scroll">
                        <table class="tabela-carrinho">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Preço Unit.</th>
                                    <th>Subtotal</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="itens-lista">
                                <?php if (count($itens) > 0): ?>
                                    <?php foreach ($itens as $item): 
                                        $subtotal = $item['preco'] * $item['quantidade'];
                                        $totalGeral += $subtotal;
                                    ?>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <?php if($item['foto']): ?>
                                                    <img src="uploads/<?= $item['foto'] ?>" width="40" style="border-radius: 5px;">
                                                <?php endif; ?>
                                                <strong><?= htmlspecialchars($item['nome']) ?></strong>
                                            </div>
                                        </td>
                                        <td><?= $item['quantidade'] ?>x</td>
                                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                                        <td><strong>R$ <?= number_format($subtotal, 2, ',', '.') ?></strong></td>
                                        <td>
                                            <a href="?remover=<?= $item['carrinho_id'] ?>" 
                                               style="color: #e53e3e;" 
                                               onclick="return confirm('Remover este item?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 20px;">
                                            Seu carrinho está vazio.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer-carrinho">
                        <div class="total-info">
                            <span>Total Geral:</span>
                            <strong id="total-valor">R$ <?= number_format($totalGeral, 2, ',', '.') ?></strong>
                        </div>
                        <button class="btn-checkout" onclick="irParaCheckout()" <?= $totalGeral <= 0 ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : '' ?>>
                            Fechar Carrinho <i class="fas fa-credit-card"></i>
                        </button>
                    </div>
                </div>
            </main>
        </section>
    </div>

    <script src="sidebar.js"></script>
    <script>
        fetch('sidebar.html')
            .then(res => res.text())
            .then(data => document.getElementById('sidebar-container').innerHTML = data);

        function irParaCheckout() {
            window.location.href = 'checkout.php'; 
</body>
</html>