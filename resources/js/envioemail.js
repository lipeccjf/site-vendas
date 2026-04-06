// envio-email.js

document.getElementById('formEnvioEmail').addEventListener('submit', function(e) {
    // 1. Impede o recarregamento padrão da página
    e.preventDefault();

    const form = this;
    const btn = document.getElementById('btnEnviarEmail');
    const feedback = document.getElementById('mensagemSucessoEmail');
    
    // 2. Coleta os dados reais do formulário
    const formData = new FormData(form);

    // 3. Feedback visual: Desativa o botão e mostra o spinner
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
    feedback.style.display = 'none';

    // 4. ENVIO REAL: Envia os dados para o arquivo PHP
    fetch('envio_email.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // 5. SUCESSO REAL: O servidor respondeu positivamente
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar E-mail';
            btn.disabled = false;
            
            // Exibe o feedback de sucesso
            feedback.style.display = 'block';
            feedback.innerHTML = '<i class="fas fa-check-circle"></i> E-mail enviado com sucesso para o banco de dados!';
            
            // Limpa os campos do formulário
            form.reset();
        } else {
            throw new Error('Erro no processamento do servidor');
        }
    })
    .catch(error => {
        // 6. TRATAMENTO DE ERRO: Caso a conexão falhe ou o PHP retorne erro
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Tentar Novamente';
        btn.disabled = false;
        alert("Ocorreu um erro ao tentar processar o envio. Verifique a conexão com o banco.");
        console.error('Erro:', error);
    });
});