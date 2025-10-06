<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $fillable = ['nombre', 'tipo_gasto', 'descripcion'];

    // Relación con gastos
    public function gastos()
    {
        return $this->hasMany(Gasto::class);
    }
}