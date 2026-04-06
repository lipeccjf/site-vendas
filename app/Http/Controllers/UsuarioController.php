<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Endereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::select('id', 'nome', 'email', 'telefone', 'cpf', 'saldo')
            ->orderBy('nome')
            ->get();

        return view('gerenciamento-usuarios', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuario,email',
            'senha' => 'required|min:6',
            'cpf' => 'required|string|unique:usuario,cpf|max:14',
            'telefone' => 'nullable|string|max:15',
            'cep' => 'required|string|size:9',
            'numero' => 'required|string|max:10',
            'datanascimento' => 'nullable|date',
            'saldo' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|max:2048',
        ]);

        $usuario = Usuario::create($request->only([
            'nome', 'email', 'senha', 'telefone', 'datanascimento', 
            'cpf', 'saldo', 'foto'
        ]));

        Endereco::create([
            'cep' => $request->cep,
            'logradouro' => $request->logradouro,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'numero' => $request->numero,
            'complemento' => $request->complemento,
            'usuarioid' => $usuario->id,
        ]);

        return response()->json(['success' => 'Usuário criado com sucesso!']);
    }

    public function show($id)
    {
        $usuario = Usuario::with('endereco')->findOrFail($id);
        return response()->json($usuario);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('usuario', 'email')->ignore($id)],
            'cpf' => ['required', Rule::unique('usuario', 'cpf')->ignore($id)],
            'telefone' => 'nullable|string|max:15',
            'cep' => 'required|string|size:9',
            'numero' => 'required|string|max:10',
        ]);

        $usuario->update($request->only([
            'nome', 'email', 'telefone', 'datanascimento', 'saldo'
        ]));

        $usuario->endereco()->updateOrCreate([
            'usuarioid' => $id
        ], $request->only(['cep', 'logradouro', 'bairro', 'cidade', 'estado', 'numero', 'complemento']));

        return response()->json(['success' => 'Usuário atualizado!']);
    }

    public function destroy($id)
    {
        Usuario::findOrFail($id)->delete();
        return response()->json(['success' => 'Usuário excluído!']);
    }
}