<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guia extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function manifiesto()
    {
        return $this->belongsTo(Manifiesto::class);
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class);
    }

    // Relación con Ayudante
    public function ayudante()
    {
        return $this->belongsTo(Ayudante::class);
    }

    public static function getNextNumeroGuia()
    {
        return self::max('numero_guia') + 1; // Obtiene el número máximo y suma 1
    }
}
