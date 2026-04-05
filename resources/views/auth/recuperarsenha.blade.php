@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - E-Commerce</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    {{-- Carregamento de estilos via Vite --}}
    @vite(['resources/css/global.css', 'resources/css/recuperarsenha.css'])
</head>
<body class="fundo-autenticacao">
    <div class="container-central">
        <section class="card-login">
            <header class="cabecalho-auth">
                <div class="logo-auth">E-Commerce</div>
                <h1 class="titulo-auth">Recuperar Senha</h1>
                <p class="descricao-auth">Esqueceu sua senha? Basta nos informar seu e-mail e enviaremos um link de redefinição.</p>
            </header>

            {{-- Exibição de Status de Sucesso (Laravel Session) --}}
            @if (session('status'))
                <div class="alerta-sucesso">
                    <i class="fas fa-check-circle"></i> {{ session('status') }}
                </div>
            @endif

            <form id="formRecuperacao" class="form-auth" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="campo-auth">
                    <label class="etiqueta" for="email">E-mail de Cadastro</label>
                    <div class="caixa-input @error('email') erro-input @enderror">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="seu-email@exemplo.com" required autocomplete="email" autofocus>
                    </div>
                    @error('email')
                        <span class="mensagem-erro">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-base btn-acao-principal btn-largo">
                    Enviar Link de Redefinição
                </button>
            </form>

            <footer class="rodape-auth">
                <a href="{{ route('login') }}" class="link-voltar"><i class="fas fa-arrow-left"></i> Voltar para o Login</a>
            </footer>
        </section>
    </div>

    @vite(['resources/js/recuperarsenha.js'])
</body>
</html>
@endsection