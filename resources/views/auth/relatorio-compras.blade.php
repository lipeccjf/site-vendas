<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Compras</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f4f4f4; font-weight: bold; }
        .total { font-weight: bold; font-size: 1.1em; }
    </style>
</head>
<body>

<?php include 'sidebar.blade.php'; ?>
    <h1>Relatório de Compras - {{ now()->format('d/m/Y') }}</h1>
    <p>Usuário: {{ auth()->user()->name ?? 'Usuário' }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Data</th>
                <th>Valor</th>
                <th>Categoria</th>
                <th>Método Pagto</th>
                <th>Vendedor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as $purchase)
            <tr>
                <td>{{ $purchase->product_name }}</td>
                <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                <td>R$ {{ number_format($purchase->value, 2, ',', '.') }}</td>
                <td>{{ $purchase->category }}</td>
                <td>{{ $purchase->payment_method }}</td>
                <td>{{ $purchase->seller }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @if($purchases->isNotEmpty())
    <p class="total">Total: R$ {{ number_format($purchases->sum('value'), 2, ',', '.') }}</p>
    @endif
</body>
</html>