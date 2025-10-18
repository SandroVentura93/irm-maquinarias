<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compra extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'proveedor_id',
        'tipo_documento',
        'numero_documento',
        'fecha_documento',
        'fecha_recepcion',
        'subtotal',
        'igv',
        'total',
        'estado',
        'observaciones',
        'created_by'
    ];

    protected $casts = [
        'fecha_documento' => 'date',
        'fecha_recepcion' => 'date',
        'subtotal' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    // Relaciones
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function detalles()
    {
        return $this->hasMany(CompraDetalle::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function movimientos()
    {
        return $this->morphMany(MovimientoInventario::class, 'documentable');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    public function scopeAnuladas($query)
    {
        return $query->where('estado', 'anulada');
    }

    // Métodos
    public function calcularTotales()
    {
        $subtotal = $this->detalles->sum(function($detalle) {
            return $detalle->cantidad * $detalle->precio_unitario;
        });
        
        $igv = $subtotal * config('app.igv', 0.18);
        $total = $subtotal + $igv;

        $this->subtotal = $subtotal;
        $this->igv = $igv;
        $this->total = $total;
    }

    public function registrarMovimientos()
    {
        foreach ($this->detalles as $detalle) {
            $producto = $detalle->producto;
            $stockAnterior = $producto->stock_actual;
            $producto->stock_actual += $detalle->cantidad;
            $producto->save();

            MovimientoInventario::create([
                'producto_id' => $producto->id,
                'tipo_movimiento' => 'entrada',
                'cantidad' => $detalle->cantidad,
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $producto->stock_actual,
                'documentable_type' => 'App\Models\Compra',
                'documentable_id' => $this->id,
                'motivo' => 'Compra',
                'created_by' => auth()->id()
            ]);
        }
    }
}