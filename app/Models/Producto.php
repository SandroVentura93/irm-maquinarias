<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    // Allow mass assignment on common fields
    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'numero_parte', 'precio_compra', 'precio_venta',
        'stock_actual', 'stock_minimo', 'marca', 'categoria', 'unidad_medida', 'ubicacion', 'ubicacion_fisica', 'proveedor_id', 'modelo', 'peso', 'importado', 'activo'
    ];

    // If there's a Categoria model/table, provide a relation. Otherwise this will be null-safe when accessed.
    public function categoriaModel()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('categorias')) {
                return $this->belongsTo(\App\Models\Categoria::class, 'categoria', 'descripcion');
            }
        } catch (\Exception $e) {
            // ignore
        }
        return null;
    }

    // scope to active products
    public function scopeActivo($q)
    {
        return $q->where('activo', 1);
    }
}
