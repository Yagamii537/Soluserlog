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

    // Relación con el modelo order (muchos a uno)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
