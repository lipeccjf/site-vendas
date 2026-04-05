function fazerLogin() {
    // 1. Esconde o login
    document.getElementById('login-screen').style.display = 'none';
    
    // 2. Mostra a home
    document.getElementById('home-screen').style.display = 'flex';
    
    // 3. Ajusta o fundo do corpo (opcional, caso mude de cor)
    document.body.style.backgroundColor = "#f4f7f6";
}