<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ayudante extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Relación con Guias
    public function guias()
    {
        return $this->hasMany(Guia::class);
    }
}
