@extends('layouts.admin')

@section('content')
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Vendas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    @vite([
        'resources/css/global.css', 
        'resources/css/minhasvendas.css',
        'resources/js/minhasvendas.js',
        'resources/js/estatisticaproduto.js'


    ])
</head>
<body>
    <?php include 'sidebar.blade.php'; ?>
    <script>
        window.graficoLabels = @json($labels ?? []);
        window.graficoDados = @json($valores ?? []);
    </script>

    <div class="pagina-usuarios">
        <main class="conteudo-pagina">
            <div class="cabecalho-pagina">
                <h1 class="titulo-pagina">Histórico de Vendas</h1>
                <p class="texto-pagina">Acompanhe sua performance de vendas, {{ Auth::user()->nome }}.</p>
            </div>

            <section class="card-lista" style="margin-bottom: 25px;">
                <div class="cabecalho-card">
                    <h2 class="titulo-card"><i class="fas fa-chart-line"></i> Desempenho Mensal de Vendas</h2>
                </div>
                <div class="container-grafico" style="position: relative; height:300px; width:100%">
                    <canvas id="graficoVendasLinha"></canvas>
                </div>
            </section>

            <section class="card-lista">
                <h2 class="titulo-card">Últimas Transações</h2>
                
                <div class="tabela-admins">
                    <div class="linha-cabecalho">
                        <span>Produto</span>
                        <span>Comprador</span>
                        <span>Qtd</span>
                        <span>V. Unitário</span>
                        <span>Total</span>
                        <span>Data</span>
                    </div>

                    @forelse($vendas as $venda)
                        <div class="linha-admin">
                            {{-- Nome extraído da tabela 'produto'  --}}
                            <span>{{ $venda->produto->nome }}</span> 
                            
                            {{-- Nome extraído da tabela 'usuario' via 'comprador_id'  --}}
                            <span>{{ $venda->comprador->nome }}</span> 
                            
                            {{-- Coluna 'quantidade' da tabela 'venda'  --}}
                            <span>{{ $venda->quantidade }}</span> 
                            
                            {{-- Coluna 'valor_unitario' da tabela 'venda'  --}}
                            <span>R$ {{ number_format($venda->valor_unitario, 2, ',', '.') }}</span> 
                            
                            {{-- Cálculo dinâmico baseado nos dados da transação  --}}
                            <span>R$ {{ number_format($venda->quantidade * $venda->valor_unitario, 2, ',', '.') }}</span> 
                            
                            {{-- Coluna 'data_venda' da tabela 'venda'  --}}
                            <span>{{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y') }}</span>
                        </div>
                    @empty
                        <div class="linha-admin" style="justify-content: center; padding: 20px;">
                            <span>Nenhuma venda registrada até o momento.</span>
                        </div>
                    @endforelse
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
@endsection