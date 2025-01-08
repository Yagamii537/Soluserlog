<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function guia()
    {
        return $this->belongsTo(Guia::class);
    }

    // Relación con DetalleBitacora (uno a muchos)
    public function detalles()
    {
        return $this->hasMany(DetalleBitacora::class, 'bitacora_id');
    }
}
