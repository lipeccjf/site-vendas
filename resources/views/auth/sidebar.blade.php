<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar- @yield('title', 'Painel Administrativo')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    @vite(['resources/css/global.css', 'resources/css/sidebar.css'])
</head>
<body>
    <div class="pagina-usuarios">
        <aside class="conteiner-esq">
            <div class="logo-sistema">  E-SHOP</div>
            
            <nav class="navegacao-sidebar">
                <ul class="menu-lateral">
                    <li class="item-menu">
                        <a href="{{ route('dashboard') }}" class="link-menu">
                            <i class="fas fa-home"></i> Painel Geral
                        </a>
                    </li>

                    <li class="item-menu">
                        <div class="link-menu" onclick="toggleSubmenu('sub-gerenciamento')">
                            <i class="fas fa-tasks"></i> Gerenciamento 
                            <i class="fas fa-chevron-down seta-icone"></i>
                        </div>
                        <ul id="sub-gerenciamento" class="submenu">
                            <li><a href="{{ route('usuarios.index') }}">Usuários</a></li>
                            <li><a href="{{ route('administradores.index') }}">Administradores</a></li>
                            <li><a href="{{ route('produtos.index') }}">Produtos</a></li>
                            <li><a href="{{ route('categorias.index') }}">Categorias</a></li>
                        </ul>
                    </li>

                    <li class="item-menu">
                        <div class="link-menu" onclick="toggleSubmenu('sub-relatorios')">
                            <i class="fas fa-history"></i> Relatórios 
                            <i class="fas fa-chevron-down seta-icone"></i>
                        </div>
                        <ul id="sub-relatorios" class="submenu">
                            <li><a href="{{ route('minhas.compras') }}">Minhas Compras</a></li>
                            <li><a href="{{ route('minhas.vendas') }}">Minhas Vendas</a></li>
                        </ul>
                    </li>

                    <li class="item-menu">
                        <a href="{{ route('enviar.email') }}" class="link-menu">
                            <i class="fas fa-paper-plane"></i> Enviar E-mail
                        </a>
                    </li>

                    <li class="item-menu">
                        <div class="link-menu" onclick="toggleSubmenu('sub-estatisticas')">
                            <i class="fas fa-chart-line"></i> Estatísticas 
                            <i class="fas fa-chevron-down seta-icone"></i>
                        </div>
                        <ul id="sub-estatisticas" class="submenu">
                            <li><a href="{{ route('grafico.cadastros') }}">Novos Cadastros</a></li>
                            <li><a href="{{ route('grafico.vendas') }}">Performance de Vendas</a></li>
                        </ul>
                    </li>

                    <li class="item-menu">
                        <a href="{{ route('perfil.edit') }}" class="link-menu">
                            <i class="fas fa-user-shield"></i> Perfil e Senha
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <section class="conteiner-dir">
            <header class="topo-pagina">
                <div class="titulo-topo">@yield('titulo-topo', 'Título da Página')</div>
                <div class="usuario-topo">{{ Auth::user()->nome }} Logado</div>
            </header>

            <main class="conteudo-pagina">
                @yield('content')
            </main>
        </section>
    </div>

    @vite(['resources/js/sidebar.js'])
    @stack('scripts')
</body>
</html>