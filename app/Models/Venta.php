<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\ProductoController;

class Venta extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ventas';

    protected $fillable = [
        'fecha',
        'cliente_id',
        'usuario_id',
        'descripcion',
        'tipo_venta',
        'tipo_comprobante',
        'serie',
        'correlativo',
        'moneda_id',
        'tc_usado',
        'subtotal',
        'descuento_total',
        'recargo_total',
        'total',
        'estado',
        'omitir_fe',
        'observaciones',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Asegura que Laravel gestione created_at/updated_at
    public $timestamps = true;

    // Casts y formatos
    protected $casts = [
        'fecha' => 'datetime',
        'tc_usado' => 'decimal:4',
        'subtotal' => 'decimal:2',
        'descuento_total' => 'decimal:2',
        'recargo_total' => 'decimal:2',
        'total' => 'decimal:2',
        'omitir_fe' => 'boolean',
    ];

    protected $dates = [
        'fecha',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relaciones (con claves foráneas explícitas)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class, 'venta_id');
    }

    // Valores por defecto y lógica al crear
    protected static function booted()
    {
        static::creating(function ($venta) {
            if (empty($venta->estado)) {
                $venta->estado = 'pendiente';
            }
        });
    }
}

