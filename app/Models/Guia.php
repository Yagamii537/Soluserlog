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
}
