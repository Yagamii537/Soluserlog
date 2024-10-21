<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Asegurarnos de que Laravel trate 'fechaCreacion' como un campo de tipo fecha
    protected $casts = [
        'fechaCreacion' => 'date',
    ];

    //? Relacion uno a uno
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Relación con el modelo Manifiesto (uno a muchos)
    public function manifiestos()
    {
        return $this->hasMany(Manifiesto::class);
    }
}
