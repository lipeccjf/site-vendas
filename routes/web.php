<?php

use App\Http\Controllers\PaginaInicialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendasController;
use App\Http\Controllers\ProdutosController; 
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UsuarioController;





Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
//Route::get('/dashboard', fn() => 'dashboard :: ' . auth()->id())->middleware('auth')->name('dashboard');

Route::get('/', [PaginaInicialController::class, 'index'])->name('paginainicial');
Route::get('/paginainicial', [PaginaInicialController::class, 'index'])->name('paginainicial');

Route::get('/admin/estatisticas', [AdminController::class, 'estatisticasProdutos'])
    ->name('admin.estatisticas')
    ->middleware('auth');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard'); // view que você já tem
        })->name('dashboard');

        
        Route::get('/vendas/historico', [VendasController::class, 'historico'])
             ->name('vendas.historico');

      
        Route::get('/produtos', [ProdutosController::class, 'index'])
             ->name('produtos.index');
    });

    Route::get('/relatorios/vendas', [VendasController::class, 'relatorios'])
         ->name('relatorios.vendas');
});



Route::middleware('auth')->group(function () {
    Route::get('/historico-compras', [PurchaseController::class, 'index'])->name('historico.compras');
    Route::get('/purchase/detalhes/{id}', [PurchaseController::class, 'detalhes'])->name('purchase.detalhes');
    Route::get('/gerar-pdf-compras', [PurchaseController::class, 'gerarPDF'])->name('pdf.compras');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    
    Route::resource('produtos', ProdutosController::class)
        ->names([
            'index' => 'produtos.index',
            'create' => 'produtos.create',
            'store' => 'produtos.store',
            'show' => 'produtos.show',
            'edit' => 'produtos.edit',
            'update' => 'produtos.update',
            'destroy' => 'produtos.destroy'
        ]);
    
   
    Route::get('produtos/search', [ProdutosController::class, 'search'])->name('produtos.search');
});


Route::middleware('auth')->group(function () {
    Route::get('/historico-compras', [PurchaseController::class, 'index'])->name('historico.compras');
    Route::get('/api/gerar-pdf-compras', [PurchaseController::class, 'gerarPDF'])->name('pdf.compras');
});


Route::middleware('auth')->group(function () {
    Route::get('/grafico-vendas', [SalesController::class, 'graficoVendas'])
        ->name('grafico.vendas');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('usuarios', UsuarioController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
});

