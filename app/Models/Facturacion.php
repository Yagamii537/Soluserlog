<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturacion extends Model
{
    use HasFactory;
    protected $table = 'facturaciones';

    protected $guarded = [];

    /**
     * Relación con Manifiesto.
     */
    public function manifiesto()
    {
        return $this->belongsTo(Manifiesto::class);
    }

    // Relación con Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relación con Document
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
