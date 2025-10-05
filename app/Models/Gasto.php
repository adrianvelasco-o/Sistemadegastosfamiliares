<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $fillable = ['tipo', 'categoria_id', 'monto', 'fecha']; // ← categoria_id en lugar de categoria

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relación con categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}