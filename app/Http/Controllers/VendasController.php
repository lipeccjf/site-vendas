<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Usuario;

class VendasController extends Controller
{
    public function historico()
    {
        $user = Auth::user();

        // Carrega as vendas do usuário (se for comum) ou todas (se for admin)
        $query = Venda::with(['produto', 'comprador']);

        if (! $user->is_admin) {
            $query->where('comprador_id', $user->id);
        }

        $vendas = $query->latest()->paginate(10);

        // Dados para o gráfico mensal (últimos 12 meses)
        $meses = collect(range(11, 0))->map(function ($monthsAgo) {
            return now()->subMonths($monthsAgo)->format('Y-m');
        });

        $labels = $meses->map(function ($ym) {
            $dt = Carbon::createFromFormat('Y-m', $ym);
            return $dt->format('M/Y');
        });

        $queryGrafico = Venda::selectRaw('DATE_FORMAT(data_venda, "%Y-%m") as mes, SUM(valor_unitario * quantidade) as total')
            ->where('data_venda', '>=', now()->subYear());

        if (! $user->is_admin) {
            $queryGrafico->where('comprador_id', $user->id);
        }

        $dataPorMes = $queryGrafico
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $valores = $meses->map(fn($m) => (float) ($dataPorMes[$m] ?? 0));

        return view('admin.vendas.historico', [
            'vendas'    => $vendas,
            'labels'    => $labels,
            'valores'   => $valores,
        ]);
    }
}
