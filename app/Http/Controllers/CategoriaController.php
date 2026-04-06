<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount('produtos')
            ->orderBy('nome')
            ->get();

        return view('gerenciamento-categorias', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:categoria,nome'
        ]);

        Categoria::create($request->only('nome'));

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        return response()->json(Categoria::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);
        $request->validate([
            'nome' => 'required|string|max:255|unique:categoria,nome,' . $id
        ]);

        $categoria->update($request->only('nome'));

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Categoria::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}