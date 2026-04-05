<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
  
    @vite(['resources/css/login.css'])
</head>
<body>

    <div class="login-container">
        
       
        <div class="login-header">
            <h1>Bem-vindo</h1>
            <p>Faça login para acessar sua conta</p>
        </div>

   
        @if ($message = session('messagem'))
            <div class="message">
                {{ $message }}
            </div>
        @endif

     
        <form action="/login" method="POST">
     

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" placeholder="Digite seu e-mail" required>
            </div>

            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" placeholder="Digite sua senha" required>
            </div>

            <button type="button" onclick="window.location.href='{{ route('paginainicial') }}'">Entrar</button>
        </form>

      
        <div class="login-footer">
            <p>Não tem uma conta? <a href="/register">Cadastre-se</a></p>
        </div>

    </div>

</body>
</html>