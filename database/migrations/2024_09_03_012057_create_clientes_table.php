<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('razonSocial', 150);
            $table->string('ruc', 25);
            $table->string('localidad', 150);
            $table->string('direccion', 150);
            $table->string('pisos', 50);
            $table->string('CodigoPostal', 15);
            $table->string('ampliado', 150);
            $table->string('celular', 30);
            $table->string('telefono', 30);
            $table->string('correo', 150);
            $table->string('contribuyente', 100);
            $table->string('latitud', 150);
            $table->string('longitud', 150);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};
