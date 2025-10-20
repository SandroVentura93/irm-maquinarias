<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    // Allow mass assignment on common fields
    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'marca', 'unidad_medida', 'ubicacion',
        'precio_compra', 'precio_venta', 'stock_actual', 'stock_minimo', 'categoria_id', 'activo'
    ];

    // If there's a Categoria model/table, provide a relation. Otherwise this will be null-safe when accessed.
    public function categoria()
    {
        return $this->belongsTo(\App\Models\Categoria::class, 'categoria_id');
    }

    // scope to active products
    public function scopeActivo($q)
    {
        return $q->where('activo', 1);
    }
}
