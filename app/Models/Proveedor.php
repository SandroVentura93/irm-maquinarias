<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    public $timestamps = false;
    protected $table = 'proveedores';
    protected $fillable = [
        'dni_ruc',
        'nombre_razon_social',
        'lugar',
        'rubro',
        'telefono',
        'email'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}