<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SalesController extends Controller
{
    public function graficoVendas()
    {
        $userId = Auth::id();
        
        $salesData = DB::table('venda as v')
            ->join('usuario as u', 'v.vendedorid', '=', 'u.id')
            ->join('produto as p', 'v.produtoid', '=', 'p.id')
            ->selectRaw('DATE_FORMAT(v.datavenda, "%Y-%m") as mes, SUM(v.valorunitario * v.quantidade) as total')
            ->where('v.vendedorid', $userId)
            ->where('v.datavenda', '>=', Carbon::now()->subMonths(12))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->pluck('total', 'mes')
            ->toArray();

        $meses = [];
        $totais = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $data = Carbon::now()->subMonths($i)->format('Y-m');
            $meses[] = $data;
            $totais[] = $salesData[$data] ?? 0;
        }

        $totalVendas = DB::table('venda')
            ->where('vendedorid', $userId)
            ->sum(DB::raw('valorunitario * quantidade'));

        return view('grafico-vendas', compact('meses', 'totais', 'totalVendas'));
    }
}