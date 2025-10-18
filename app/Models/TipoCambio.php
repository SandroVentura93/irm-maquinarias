<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoCambio extends Model
{
    use HasFactory;

    protected $table = 'tipos_cambio';

    protected $fillable = [
        'moneda_origen_id',
        'moneda_destino_id',
        'tipo_cambio',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'date',
        'tipo_cambio' => 'decimal:4'
    ];

    // Moneda origen
    public function monedaOrigen()
    {
        return $this->belongsTo(Moneda::class, 'moneda_origen_id');
    }

    // Moneda destino
    public function monedaDestino()
    {
        return $this->belongsTo(Moneda::class, 'moneda_destino_id');
    }

    // Método para obtener el tipo de cambio más reciente entre dos monedas
    public static function obtenerTipoCambio($monedaOrigenId, $monedaDestinoId)
    {
        return static::where('moneda_origen_id', $monedaOrigenId)
            ->where('moneda_destino_id', $monedaDestinoId)
            ->orderBy('fecha', 'desc')
            ->first();
    }
}