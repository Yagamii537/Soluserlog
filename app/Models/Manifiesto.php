<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manifiesto extends Model
{
    use HasFactory;


    protected $guarded = [];

    // Relación con el modelo Camión (muchos a uno)
    public function camion()
    {
        return $this->belongsTo(Camion::class);
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class);
    }

    public function ayudante()
    {
        return $this->belongsTo(Ayudante::class);
    }

    // Relación con los pedidos
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
    public function guias()
    {
        return $this->hasMany(Guia::class);
    }
}
