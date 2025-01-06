<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ayudante extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function manifiestos()
    {
        return $this->hasMany(Manifiesto::class);
    }
}
