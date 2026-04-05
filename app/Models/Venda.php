<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Venda extends Model
{
    use HasFactory;

    /** Tabela confirmada no dump */
    protected $table = 'venda';

    /** Chave primária */
    protected $primaryKey = 'id';

    /** Campos exatos do dump */
    protected $fillable = [
        'valorunitario',
        'quantidade', 
        'datavenda',
        'compradorid',
        'vendedorid',
        'produtoid'
    ];

    /** Casts corretos */
    protected $casts = [
        'valorunitario' => 'decimal:2',
        'quantidade' => 'integer',
        'datavenda' => 'date',
    ];

    /** Sem timestamps (confirmado dump) */
    public $timestamps = false;

    /** Relacionamentos EXATOS do dump */
    public function comprador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'compradorid');
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'vendedorid');
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produtoid');
    }

    /** Valor total venda */
    public function getValorTotalAttribute(): float
    {
        return $this->valorunitario * $this->quantidade;
    }

    /** Scopes para relatórios (RF008, RF009) */
    public function scopeVendasUsuario($query, $userId)
    {
        return $query->where('vendedorid', $userId);
    }

    public function scopeComprasUsuario($query, $userId)
    {
        return $query->where('compradorid', $userId);
    }

    /** Scope para gráficos mensais (RF014) */
    public function scopePorMes($query, $mes, $ano)
    {
        return $query->whereYear('datavenda', $ano)
                    ->whereMonth('datavenda', $mes);
    }
}