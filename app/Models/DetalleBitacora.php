<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleBitacora extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function bitacora()
    {
        return $this->belongsTo(Bitacora::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function images()
    {
        return $this->hasMany(DetalleBitacoraImage::class);
    }
}
