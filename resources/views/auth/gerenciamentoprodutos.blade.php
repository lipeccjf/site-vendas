<!DOCTYPE html>
<html lang="pt-BR">

<head>
   @extends('layouts.admin')

@section('content')
    {{-- Carregamento de Assets via Vite --}}
    @vite([
        'resources/css/global.css',
        'resources/css/modal.css',
        'resources/css/gerenciamentoadm.css',
        'resources/css/historicodevendas.css',
        'resources/js/vendas.js'
    ])

    {{-- Script de ponte entre PHP e JavaScript --}}
    <script>
        window.graficoLabels = @json($labels ?? []);
        window.graficoDados = @json($valores ?? []);
        window.is_admin = {{ Auth::user()->is_admin ? 'true' : 'false' }};
    </script>

    <?php include 'sidebar.blade.php'; ?>
    

    <div class="pagina-admins">
        {{-- Menu Lateral --}}
        <aside class="conteiner-esq">
            <div class="logo-sistema">E-Commerce</div>
            <ul class="menu-lateral">
                <li class="item-menu">
                    <a href="{{ route('admin.dashboard') }}" class="link-menu">Dashboard</a>
                </li>
                <li class="item-menu">
                    <a href="{{ route('vendas.historico') }}" class="link-menu ativo">Minhas Vendas</a>
                </li>
                <li class="item-menu">
                    <a href="{{ route('produtos.index') }}" class="link-menu">Produtos</a>
                </li>
            </ul>
        </aside>

        {{-- Área de Conteúdo --}}
        <section class="conteiner-dir">
            <header class="topo-pagina">
                <div class="titulo-topo">Histórico de Vendas</div>
                <div class="usuario-topo">Olá, <span id="nome-usuario">{{ Auth::user()->nome }}</span></div>
            </header>

            <main class="conteudo-pagina">
                <div class="cabecalho-pagina">
                    <h1 class="titulo-pagina">Painel de Vendas</h1>
                    <p class="texto-pagina">Acompanhe seus lucros e gere relatórios de desempenho.</p>
                </div>

                {{-- Regra: Apenas usuários comuns visualizam o gráfico de desempenho individual --}}
                @if(!Auth::user()->is_admin)
                    <div id="container-grafico-vendas" class="card-lista" style="margin-bottom: 20px;">
                        <h2 class="titulo-card">Desempenho de Vendas (Linha)</h2>
                        <div style="height: 200px;">
                            <canvas id="graficoVendasMensal"></canvas>
                        </div>
                    </div>
                @endif

                <section class="card-lista">
                    <div class="cabecalho-card-acoes" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 class="titulo-card">Relatórios de Transações</h2>
                        <div class="grupo-botoes-relatorio">
                            {{-- Exportação PDF (Acessível a todos) --}}
                            <a href="{{ route('relatorios.vendas', ['formato' => 'pdf']) }}" class="btn-base btn-cancelar">
                                <i class="fas fa-file-pdf"></i> Exportar PDF
                            </a>
                            
                            {{-- Regra: Apenas administradores exportam XLSX --}}
                            @if(Auth::user()->is_admin)
                                <a href="{{ route('relatorios.vendas', ['formato' => 'xlsx']) }}" class="btn-base btn-acao-principal">
                                    <i class="fas fa-file-excel"></i> Exportar Excel
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Tabela de Transações --}}
                    <div class="tabela-admins">
                        <div class="linha-cabecalho">
                            <span>Foto</span>
                            <span>Produto</span>
                            <span>Data</span>
                            <span>Valor Total</span>
                            <span>Comprador</span>
                        </div>

                        @forelse($vendas as $venda)
                            <div class="linha-admin">
                                <span>
                                    <img src="{{ asset('storage/' . $venda->produto->foto) }}" class="foto-venda-tabela" alt="Produto">
                                </span>
                                <span>{{ $venda->produto->nome }}</span>
                                <span>{{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y') }}</span>
                                <span class="valor-venda">
                                    R$ {{ number_format($venda->valor_unitario * $venda->quantidade, 2, ',', '.') }}
                                </span>
                                <span>{{ $venda->comprador->nome }} ({{ $venda->comprador->email }})</span>
                            </div>
                        @empty
                            <div class="linha-admin" style="justify-content: center; padding: 40px;">
                                <span class="texto-pagina">Nenhuma transação encontrada no histórico.</span>
                            </div>
                        @endforelse
                    </div>
                </section>
            </main>
        </section>
    </div>

    {{-- Script Externo do Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </html>
@endsection