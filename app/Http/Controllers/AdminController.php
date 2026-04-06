<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{


public function estatisticasProdutos()
{
   

public function admins()
{
    $admins = Usuario::where('isadmin', 1)
        ->select('id', 'nome', 'email', 'telefone', 'cpf', 'saldo')
        ->orderBy('nome')
        ->get();

    return view('gerenciamento-admins', compact('admins'));
}

// Demais métodos (storeAdmin, showAdmin, etc.) similares ao UsuarioController
public function storeAdmin(Request $request)
{
    $request->validate([
        'nome' => 'required|string|max:255',
        'email' => 'required|email|unique:usuario,email',
        'senha' => 'required|min:6',
        'cpf' => 'required|unique:usuario,cpf',
        'telefone' => 'nullable|string|max:15',
    ]);

    Usuario::create(array_merge($request->only(['nome', 'email', 'senha', 'telefone', 'cpf']), [
        'isadmin' => 1
    ]));

    return response()->json(['success' => true]);
}

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

