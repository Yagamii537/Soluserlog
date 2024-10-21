<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camion extends Model
{
    use HasFactory;
    protected  $table="camiones";

    protected $guarded = [];

    // Relación muchos a muchos con conductores
    public function conductores()
    {
        return $this->belongsToMany(Conductor::class, 'camion_conductor');
    }
}
