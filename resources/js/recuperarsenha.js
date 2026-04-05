document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formRecuperacao');
    
    if (form) {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('button');
            
            // Desativa o botão para evitar cliques duplos durante o processamento do Laravel
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
        });
    }
});