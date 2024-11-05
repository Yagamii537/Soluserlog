<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relación inversa con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
