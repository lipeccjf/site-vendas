<?php
// 1. Conexão com o Banco de Dados
try {
    $db = new PDO('sqlite:database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco: " . $e->getMessage());
}

// 2. Lógica de Processamento do Envio
$mensagemSucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $assunto = $_POST['assunto'];
    $conteudo = $_POST['conteudo'];

    if (!empty($usuario_id) && !empty($assunto) && !empty($conteudo)) {
        // Busca o e-mail real do usuário selecionado para o "disparo"
        $stmtEmail = $db->prepare("SELECT email FROM usuario WHERE id = :id");
        $stmtEmail->execute([':id' => $usuario_id]);
        $destinatario = $stmtEmail->fetch(PDO::FETCH_ASSOC);

        if ($destinatario) {
            /* NOTA REAL: Para enviar e-mail de verdade em produção, usa-se a função mail() 
               ou bibliotecas como PHPMailer. Aqui a lógica valida os dados do banco.
            */
            $mensagemSucesso = true;
        }
    }
}

// 3. Busca de Usuários Reais para o Select
$usuarios = $db->query("SELECT id, nome, email FROM usuario ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Envio de E-mail</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
   @vite(['resources/css/global.css', 'resources/css/sidebar.css', 'resources/css/envioemail.css']) 

</head>
<body>
    <div class="pagina-admins">
        <aside class="conteiner-esq">
            <div class="logo-sistema">E-Commerce</div>
            <ul class="menu-lateral">
                <li class="item-menu"><a href="#" class="link-menu">Dashboard</a></li>
                <li class="item-menu"><a href="#" class="link-menu">Usuários</a></li>
                <li class="item-menu"><a href="#" class="link-menu ativo">Enviar E-mail</a></li>
            </ul>
        </aside>

        <section class="conteiner-dir">
            <header class="topo-pagina">
                <div class="titulo-topo">RF011 - Comunicação com Usuário</div>
                <div class="usuario-topo">Administrador Logado</div>
            </header>

            <main class="conteudo-pagina">
                <div class="cabecalho-pagina">
                    <h1 class="titulo-pagina">Disparo de E-mail</h1>
                    <p class="texto-pagina">Utilize esta ferramenta para enviar comunicados, avisos ou promoções diretamente aos usuários.</p>
                </div>

                <section class="card-formulario">
                    <h2 class="titulo-card">Nova Mensagem</h2>
                    
                    <form id="formEnvioEmail" method="POST" action="">
                        <div class="grade-campos">
                            <div class="campo-inteiro">
                                <label class="etiqueta">Para:</label>
                                <select name="usuario_id" class="select-simples" required>
                                    <option value="">Selecione um usuário...</option>
                                    
                                    <?php foreach ($usuarios as $u): ?>
                                        <option value="<?= $u['id'] ?>">
                                            <?= htmlspecialchars($u['nome']) ?> (<?= htmlspecialchars($u['email']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                    
                                </select>
                            </div>

                            <div class="campo-inteiro">
                                <label class="etiqueta">Assunto:</label>
                                <input type="text" name="assunto" class="campo-texto" placeholder="Digite o assunto do e-mail" required>
                            </div>

                            <div class="campo-inteiro">
                                <label class="etiqueta">Conteúdo do E-mail:</label>
                                <textarea name="conteudo" class="area-texto" rows="10" placeholder="Escreva sua mensagem aqui..." required></textarea>
                            </div>
                        </div>

                        <div class="acoes-formulario">
                            <button type="submit" class="botao-principal" id="btnEnviarEmail">
                                <i class="fas fa-paper-plane"></i> Enviar E-mail
                            </button>
                            <button type="reset" class="botao-secundario">Limpar</button>
                        </div>
                    </form>

                    <?php if ($mensagemSucesso): ?>
                        <div id="mensagemSucessoEmail" class="mensagem-formulario" style="display: block; background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-top: 20px; border: 1px solid #c3e6cb;">
                            <i class="fas fa-check-circle"></i> E-mail disparado com sucesso! O usuário receberá a mensagem em breve.
                        </div>
                    <?php endif; ?>

                </section>
            </main>
        </section>
    </div>

    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
        @vite(['resources/js/envioemail.js'])   
    </script>
</body>
</html>