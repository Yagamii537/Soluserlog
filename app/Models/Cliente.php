<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'razonSocial',
    //     'ruc',
    //     'direccion',
    //     'pisos',
    //     'CodigoPostal',
    //     'ampliado',
    //     'celular',
    //     'telefono',
    //     'correo',
    //     'contribuyente',
    //     'latitud',
    //     'longitud'
    // ];
    protected $guarded = [];

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    // Relación uno a muchos con addresses
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

}
