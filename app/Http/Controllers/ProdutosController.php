<?php
// app/Http/Controllers/Admin/ProdutosController.php
namespace App\Http\Controllers\Admin;

use App\Models\Produto;
use App\Models\Categoria;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProdutosController extends \App\Http\Controllers\Controller
{
    /**
   
     */
    public function index(Request $request): View
    {
        $query = Produto::with(['categoria', 'usuario'])
            ->select('produtos.*', \DB::raw('SUM(carrinho.quantidade) as vendas_totais'))
            ->leftJoin('carrinho', 'produtos.id', '=', 'carrinho.produtoid')
            ->groupBy('produtos.id');

        // Filtro por categoria (opcional)
        if ($request->filled('categoria')) {
            $query->where('categoriaid', $request->categoria);
        }

        // Busca por nome
        if ($request->filled('busca')) {
            $query->where('nome', 'LIKE', '%' . $request->busca . '%');
        }

        $produtos = $query->latest()->paginate(12);

        $categorias = Categoria::pluck('nome', 'id');

        return view('admin.produtos.index', compact('produtos', 'categorias'));
    }

    /**
     * Exibe formulário para criar novo produto
     */
    public function create(): View
    {
        $categorias = Categoria::pluck('nome', 'id');
        return view('admin.produtos.create', compact('categorias'));
    }

    /**
     * Salva novo produto
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nome' => 'required|max:255',
            'preco' => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:0',
            'descricao' => 'nullable|max:1000',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categoriaid' => 'required|exists:categoria,id'
        ]);

        $dados = $request->only([
            'nome', 'preco', 'quantidade', 'descricao', 'categoriaid'
        ]);

        $dados['usuarioid'] = Auth::id();
        $dados['datacriacao'] = now()->format('Y-m-d');

        // Upload de foto
        if ($request->hasFile('foto')) {
            $dados['foto'] = $request->file('foto')->store('produtos', 'public');
        }

        Produto::create($dados);

        return redirect()->route('produtos.index')
            ->with('success', 'Produto criado com sucesso!');
    }

    /**
     * Exibe formulário para editar produto
     */
    public function edit(Produto $produto): View
    {
        // Só permite editar produtos próprios ou se for admin
        if ($produto->usuarioid !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        $categorias = Categoria::pluck('nome', 'id');
        return view('admin.produtos.edit', compact('produto', 'categorias'));
    }

    /**
     * Atualiza produto existente
     */
    public function update(Request $request, Produto $produto): RedirectResponse
    {
        // Verificação de permissão
        if ($produto->usuarioid !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        $request->validate([
            'nome' => 'required|max:255',
            'preco' => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:0',
            'descricao' => 'nullable|max:1000',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categoriaid' => 'required|exists:categoria,id'
        ]);

        $dados = $request->only([
            'nome', 'preco', 'quantidade', 'descricao', 'categoriaid'
        ]);

        // Deleta foto antiga se nova for enviada
        if ($request->hasFile('foto')) {
            if ($produto->foto) {
                Storage::disk('public')->delete($produto->foto);
            }
            $dados['foto'] = $request->file('foto')->store('produtos', 'public');
        }

        $produto->update($dados);

        return redirect()->route('produtos.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove produto
     */
    public function destroy(Produto $produto): RedirectResponse
    {
        // Só permite deletar produtos próprios ou se for admin
        if ($produto->usuarioid !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        // Deleta foto se existir
        if ($produto->foto) {
            Storage::disk('public')->delete($produto->foto);
        }

        $produto->delete();

        return redirect()->route('produtos.index')
            ->with('success', 'Produto deletado com sucesso!');
    }

    /**
     * Detalhes do produto
     */
    public function show(Produto $produto): View
    {
        $produto->load(['categoria', 'usuario']);
        return view('admin.produtos.show', compact('produto'));
    }

    /**
     * API para busca rápida (usado em autocomplete)
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        $produtos = Produto::where('nome', 'LIKE', "%{$query}%")
            ->orWhere('descricao', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'nome', 'preco', 'foto']);

        return response()->json($produtos);
    }
}