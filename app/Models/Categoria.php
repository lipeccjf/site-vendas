<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categoria';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = ['nome', 'datacriacao'];
    
    public function produtos()
    {
        return $this->hasMany(Produto::class, 'categoriaid');
    }
}