<?php

use App\Http\Controllers\PaginaInicialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;


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





    