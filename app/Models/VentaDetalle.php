<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use HasFactory;

    protected $table = 'ventas_detalle';

    protected $fillable = [
        'venta_id','producto_id','codigo_producto','numero_parte','descripcion',
        'cantidad','precio_unitario','descuento','recargo','subtotal'
    ];

    public function venta() { return $this->belongsTo(Venta::class,'venta_id'); }
    public function producto() { return $this->belongsTo(Producto::class,'producto_id'); }
}