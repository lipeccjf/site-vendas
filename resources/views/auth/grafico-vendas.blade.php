<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'E-Commerce') }} - Estatísticas</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
 
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/graficovendas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>
<?php include 'sidebar.blade.php'; ?>
    <div class="pagina-container">
        <aside class="conteiner-esq">
            <div class="logo-sistema">E-SHOP</div>
            <nav class="navegacao-sidebar">
                <ul class="menu-lateral">
                    [cite_start]
                    @if(Auth::user()->is_admin)
                    <li class="item-menu">
                        <div class="link-menu" onclick="toggleSubmenu('sub-gerenciamento')">
                            <i class="fas fa-tasks"></i> Gerenciamento <i class="fas fa-chevron-down seta-icone"></i>
                        </div>
                        <ul id="sub-gerenciamento" class="submenu">
                            <li><a href="{{ route('usuarios.index') }}">Usuários</a></li>
                            <li><a href="{{ route('admin.index') }}">Administradores</a></li>
                            <li><a href="{{ route('produtos.index') }}">Produtos</a></li>
                        </ul>
                    </li>
                    @endif

                    <li class="item-menu">
                        <div class="link-menu" onclick="toggleSubmenu('sub-relatorios')">
                            <i class="fas fa-history"></i> Relatórios <i class="fas fa-chevron-down seta-icone"></i>
                        </div>
                        <ul id="sub-relatorios" class="submenu">
                            <li><a href="{{ route('compras.historico') }}">Minhas Compras</a></li>
                            <li><a href="{{ route('vendas.historico') }}">Minhas Vendas</a></li>
                        </ul>
                    </li>

                    <li class="item-menu">
                        <a href="{{ route('email.create') }}" class="link-menu"><i class="fas fa-paper-plane"></i> Enviar E-mail</a>
                    </li>

                    <li class="item-menu">
                        <div class="link-menu aberto" onclick="toggleSubmenu('sub-estatisticas')">
                            <i class="fas fa-chart-line"></i> Estatísticas <i class="fas fa-chevron-down seta-icone"></i>
                        </div>
                        <ul id="sub-estatisticas" class="submenu" style="display: block;">
                            <li><a href="{{ route('estatisticas.produtos') }}">Novos Cadastros</a></li>
                            <li><a href="{{ route('estatisticas.vendas') }}" class="ativo">Performance de Vendas</a></li>
                        </ul>
                    </li>

                    <li class="item-menu">
                        <a href="{{ route('perfil.edit') }}" class="link-menu"><i class="fas fa-user-shield"></i> Perfil e Senha</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <section class="conteiner-dir">
            <header class="topo-pagina">
                <div class="titulo-topo">Performance de Vendas</div>
               
                <div class="usuario-topo">{{ Auth::user()->nome ?? 'Visitante' }}</div>
            </header>

            <main class="conteudo-pagina">
                <div class="cabecalho-pagina">
                    <h1 class="titulo-pagina">Histórico de Vendas</h1>
                    <p class="texto-pagina">Visualização do volume de vendas realizadas nos últimos 12 meses.</p>
                </div>

                <div class="card-grafico">
                    <canvas id="vendasCanvas"></canvas>
                </div>
            </main>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    
    <script>
        const dadosVendas = @json($dadosGrafico); 
       

        const ctx = document.getElementById('vendasCanvas').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dadosVendas.meses, 
                datasets: [{
                    label: 'Volume de Vendas',
                    data: dadosVendas.valores, 
                }]
            }
        });
    </script>

    <script src="{{ asset('js/graficos.js') }}"></script>
</body>
</html>