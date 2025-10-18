<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuraciones';

    protected $fillable = [
        'clave',
        'valor',
        'tipo',
        'descripcion'
    ];

    // Obtener valor según el tipo
    public function getValorAttribute($value)
    {
        switch ($this->tipo) {
            case 'numero':
                return floatval($value);
            case 'booleano':
                return $value === 'true' || $value === '1' || $value === true;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    // Establecer valor según el tipo
    public function setValorAttribute($value)
    {
        switch ($this->tipo) {
            case 'json':
                $this->attributes['valor'] = is_string($value) ? $value : json_encode($value);
                break;
            default:
                $this->attributes['valor'] = (string) $value;
        }
    }

    // Métodos estáticos para acceder fácilmente a las configuraciones
    public static function obtener($clave, $porDefecto = null)
    {
        $config = static::where('clave', $clave)->first();
        return $config ? $config->valor : $porDefecto;
    }

    public static function establecer($clave, $valor)
    {
        $config = static::firstOrNew(['clave' => $clave]);
        $config->valor = $valor;
        $config->save();
        return $config;
    }
}