<?php
namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaginaInicialController extends Controller
{
   public function index(Request $request)
{
    $query = Produto::with(['categoria', 'usuario'])
        ->where('usuarioid', '!=', Auth::id());

    if ($request->filled('busca')) {
        $query->where('nome', 'like', '%' . $request->busca . '%');
    }
    if ($request->filled('categoria')) {
        $query->where('categoriaid', $request->categoria);
    }

    $produtos = $query->paginate(12);
    $categorias = Categoria::all();
    
  
    $produtos = Produto::latest()->limit(6)->get();
    $categorias = Categoria::all();
    $totalProdutos = Produto::count();
    $totalVendas = Venda::count();
    $totalCategorias = $categorias->count();

    return view('paginainicial', compact(
        'produtos', 'categorias', 'totalProdutos', 
        'totalVendas', 'totalCategorias'
    ));
}
}