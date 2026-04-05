<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VendasController extends Controller
{
    /**
     * Exibe o histórico de vendas do usuário logado (Vendedor).
     * Prepara também os dados para o gráfico de desempenho mensal.
     */
    public function index()
    {
        $usuarioId = Auth::id();

        // Recupera as vendas onde o usuário atual é o vendedor 
        $vendas = Venda::with(['produto', 'comprador'])
            ->where('vendedor_id', $usuarioId)
            ->orderBy('data_venda', 'desc')
            ->get();

        // Agrupamento para o gráfico: Contagem de vendas por mês nos últimos 12 meses
        $dadosGrafico = Venda::where('vendedor_id', $usuarioId)
            ->where('data_venda', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw("strftime('%m/%Y', data_venda) as mes_ano, count(*) as total")
            ->groupBy('mes_ano')
            ->orderBy('data_venda', 'asc')
            ->get();

        $labels = $dadosGrafico->pluck('mes_ano');
        $valores = $dadosGrafico->pluck('total');

        return view('vendas.minhas-vendas', compact('vendas', 'labels', 'valores'));
    }

    /**
     * Registra uma nova venda no sistema.
     * Corresponde à inserção de dados na tabela 'venda'[cite: 23, 24].
     */
    public function store(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|exists:produto,id',
            'quantidade' => 'required|integer|min:1',
            'comprador_id' => 'required|exists:usuario,id',
        ]);

        $produto = Produto::findOrFail($request->produto_id);

        // Verifica se há estoque disponível 
        if ($produto->quantidade < $request->quantidade) {
            return back()->with('error', 'Estoque insuficiente para esta operação.');
        }

        try {
            DB::beginTransaction();

            // 1. Cria o registro da venda 
            $venda = Venda::create([
                'valor_unitario' => $produto->preco, // Preço atual do produto 
                'quantidade'     => $request->quantidade,
                'data_venda'     => Carbon::now()->format('Y-m-d'),
                'comprador_id'   => $request->comprador_id,
                'vendedor_id'    => $produto->usuario_id, // O dono do produto é o vendedor 
                'produto_id'     => $produto->id,
            ]);

            // 2. Atualiza o estoque do produto 
            $produto->decrement('quantidade', $request->quantidade);

            // 3. Lógica de Saldo (Opcional): Transfere o valor do comprador para o vendedor [cite: 21]
            $valorTotal = $venda->valor_unitario * $venda->quantidade;
            
            // Debita do comprador
            DB::table('usuario')->where('id', $venda->comprador_id)->decrement('saldo', $valorTotal);
            // Credita ao vendedor
            DB::table('usuario')->where('id', $venda->vendedor_id)->increment('saldo', $valorTotal);

            DB::commit();
            return redirect()->route('vendas.index')->with('success', 'Venda registrada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Falha ao processar a venda: ' . $e->getMessage());
        }
    }

    /**
     * Exibe os detalhes de uma venda específica.
     */
    public function show($id)
    {
        $venda = Venda::with(['produto', 'comprador', 'vendedor'])
            ->where(function($query) {
                $query->where('vendedor_id', Auth::id())
                      ->orWhere('comprador_id', Auth::id());
            })
            ->findOrFail($id);

        return response()->json($venda);
    }

    /**
     * Remove um registro de venda (Estorno).
     */
    public function destroy($id)
    {
        $venda = Venda::where('vendedor_id', Auth::id())->findOrFail($id);

        try {
            DB::beginTransaction();

            // Devolve a quantidade ao estoque 
            Produto::where('id', $venda->produto_id)->increment('quantidade', $venda->quantidade);
            
            $venda->delete();

            DB::commit();
            return redirect()->route('vendas.index')->with('success', 'Venda estornada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Não foi possível estornar a venda.');
        }
    }
}