<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cotizacion extends Model
{
    use SoftDeletes;
    protected $table = 'cotizaciones';
    protected $fillable = [
        'cliente_id', 'fecha', 'total', 'estado', 'observaciones'
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function detalles()
    {
        return $this->hasMany(CotizacionDetalle::class);
    }
}
