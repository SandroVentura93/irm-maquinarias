<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public $timestamps = false;
    protected $table = 'clientes';
    protected $fillable = [
        'dni',
        'ruc',
        'nombre',
        'apellido',
        'direccion',
        'region_id',
        'provincia_id',
        'distrito_id',
        'tipo_cliente',
        'telefono',
        'email'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    public function distrito()
    {
        return $this->belongsTo(Distrito::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}