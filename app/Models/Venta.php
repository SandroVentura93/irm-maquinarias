<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'fecha','cliente_id','usuario_id','descripcion','tipo_venta','tipo_comprobante',
        'serie','correlativo','moneda_id','tc_usado','subtotal','descuento_total',
        'recargo_total','total','estado','omitir_fe','observaciones'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function cliente() { return $this->belongsTo(Cliente::class); }
    public function usuario() { return $this->belongsTo(User::class,'usuario_id'); }
    public function detalles() { return $this->hasMany(VentaDetalle::class,'venta_id'); }
}

