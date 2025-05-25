<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleBitacoraCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'detalle_bitacora_id',
        'tipo',
        'opcion',
    ];

    public function detalleBitacora()
    {
        return $this->belongsTo(DetalleBitacora::class);
    }
}
