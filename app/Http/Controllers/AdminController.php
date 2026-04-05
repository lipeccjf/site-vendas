<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{


public function estatisticasProdutos()
{
   
   $estatisticas = Produto::selectRaw("strftime('%m/%Y', data_criacao) as mes_ano, count(*) as total")
            ->where('data_criacao', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('mes_ano')
            ->orderBy('data_criacao', 'asc')
            ->get();

        $labels = $estatisticas->pluck('mes_ano');
        $valores = $estatisticas->pluck('total');

   
        return view('admin.estatisticas', compact('labels', 'valores'));
}
    //
}
