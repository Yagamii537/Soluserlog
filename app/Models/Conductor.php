<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    use HasFactory;
    protected $table = 'conductores';

    protected $guarded = [];

    // Relación muchos a muchos con camiones
    public function camiones()
    {
        return $this->belongsToMany(Camion::class, 'camion_conductor');
    }
}
