<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Moneda extends Model
{
    use HasFactory;

    protected $table = 'monedas';
    
    protected $fillable = [
        'nombre',
        'codigo',
        'simbolo',
        'formato',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean'
    ];

    // Tipos de cambio donde esta moneda es el origen
    public function tiposCambioOrigen()
    {
        return $this->hasMany(TipoCambio::class, 'moneda_origen_id');
    }

    // Tipos de cambio donde esta moneda es el destino
    public function tiposCambioDestino()
    {
        return $this->hasMany(TipoCambio::class, 'moneda_destino_id');
    }

    // Formatear un valor según el formato de la moneda
    public function formatearValor($valor)
    {
        return str_replace(
            ['%s', '%v'],
            [$this->simbolo, number_format($valor, 2)],
            $this->formato
        );
    }
}