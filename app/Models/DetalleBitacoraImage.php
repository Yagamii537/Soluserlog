<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleBitacoraImage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function detalleBitacora()
    {
        return $this->belongsTo(DetalleBitacora::class);
    }
}
