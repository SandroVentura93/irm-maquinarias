<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioMovimiento extends Model
{
    public $timestamps = false;
    protected $table = 'inventario_movimientos';
    protected $fillable = [
        'fecha',
        'producto_id',
        'tipo',
        'referencia_tipo',
        'referencia_id',
        'cantidad',
        'stock_resultante',
        'observacion'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}