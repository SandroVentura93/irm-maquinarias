<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    public $timestamps = false;
    protected $table = 'provincias';
    protected $fillable = ['region_id', 'nombre'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function distritos()
    {
        return $this->hasMany(Distrito::class);
    }
}