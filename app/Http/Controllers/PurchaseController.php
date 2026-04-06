<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('historico-compras', compact('purchases'));
    }

    public function gerarPDF()
    {
        $purchases = Purchase::where('user_id', auth()->id())->get();
        $pdf = Pdf::loadView('pdf.relatorio-compras', compact('purchases'));
        return $pdf->stream('relatorio-compras.pdf');
    }
}