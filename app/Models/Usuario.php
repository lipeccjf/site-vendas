<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use App\Models\Endereco;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Tabela correta do banco SQLite
     */
    protected $table = 'usuario';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * Campos do SEU BANCO (do dump)
     */
    protected $fillable = [
        'nome',        // ← CORRIGIDO: era 'name'
        'email',
        'senha',       // ← CORRIGIDO: era 'password'
        'telefone',
        'datanascimento',
        'cpf',
        'saldo',
        'foto',
        'datacriacao',
        'isadmin',     // ← Campo admin do seu banco
    ];

      protected $casts = [
        'datanascimento' => 'date',
        'saldo' => 'decimal:2',
        'datacriacao' => 'date',
        'isadmin' => 'boolean',
    ];


     public function endereco()
    {
        return $this->hasOne(Endereco::class, 'usuarioid');
    }

     public function setSenhaAttribute($value)
    {
        $this->attributes['senha'] = Hash::make($value);
    }n
    /**
     * Campos ocultos
     */
    protected $hidden = [
        'senha',       // ← CORRIGIDO: nome do banco
        'remember_token',
    ];



    /**
     * Casts corretos
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'senha' => 'hashed',
            'isadmin' => 'boolean',
            'saldo' => 'decimal:2',
            'datanascimento' => 'date',
            'datacriacao' => 'date',
        ];
    }

    public function getEmailAttribute($value)
{
    return $value;
}

public function getPasswordAttribute($value)
{
    return $value;
}

public function setPasswordAttribute($value)
{
    $this->attributes['senha'] = bcrypt($value);
}


public function setEmailAttribute($value)
{
    $this->attributes['email'] = $value;
}
    /**
     * Relacionamentos
     */
    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class, 'usuarioid');
    }

    public function vendas(): HasMany
    {
        return $this->hasMany(Venda::class, 'vendedorid');
    }

    /**
     * Verifica se é admin
     */
    public function isAdmin(): bool
    {
        return $this->isadmin === 1;
    }

    /**
     * Scope: apenas admins
     */
    public function scopeAdmins($query)
    {
        return $query->where('isadmin', 1);
    }

    /**
     * Scope: usuários comuns
     */
    public function scopeUsuarios($query)
    {
        return $query->where('isadmin', 0);
    }
}