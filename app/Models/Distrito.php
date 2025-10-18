<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    public $timestamps = false;
    protected $table = 'distritos';
    protected $fillable = ['provincia_id', 'nombre'];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }
}