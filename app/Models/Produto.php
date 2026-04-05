<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Produto extends Model
{
    use HasFactory;

    /**
     * Nome exato da tabela no banco SQLite
     */
    protected $table = 'produto';

    /**
     * Chave primária
     */
    protected $primaryKey = 'id';

    /**
     * Campos que podem ser preenchidos em massa
     */
    protected $fillable = [
        'nome',
        'preco',
        'foto',
        'quantidade',
        'descricao',
        'datacriacao',
        'usuarioid',
        'categoriaid',
    ];

    /**
     * Casts para tipos corretos
     */
    protected $casts = [
        'preco' => 'decimal:2',
        'quantidade' => 'integer',
        'datacriacao' => 'date',
    ];

    /**
     * Não usa timestamps automáticos (seu dump não tem)
     */
    public $timestamps = false;

    /**
     * Relacionamentos
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuarioid');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoriaid');
    }

    /**
     * Scopes úteis
     */
    public function scopeDoOutroUsuario($query)
    {
        return $query->where('usuarioid', '!=', auth()->id());
    }

    public function scopePorCategoria($query, $categoriaId)
    {
        return $query->where('categoriaid', $categoriaId);
    }

    public function scopeBuscaNome($query, $termo)
    {
        return $query->where('nome', 'like', '%' . $termo . '%');
    }

    /**
     * Accessors formatados
     */
    public function getPrecoFormatadoAttribute()
    {
        return 'R$ ' . number_format((float) $this->preco, 2, ',', '.');
    }

    public function getTemEstoqueAttribute()
    {
        return $this->quantidade > 0;
    }
}