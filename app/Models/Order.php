<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    //? Relacion uno a uno
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
