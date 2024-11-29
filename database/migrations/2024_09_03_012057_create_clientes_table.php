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
            $table->string('codigoCliente', 5);
            $table->string('ruc', 25);
            $table->string('razonSocial', 150);
            $table->string('tipoInstitucion', 150);
            $table->string('tipoCliente', 50);
            $table->string('publicoPrivado', 50);
            $table->string('telefono', 15);
            $table->string('correo', 150);
            $table->date('fechaCreacion');
            $table->integer('estado')->default(1); // 1 para Activo, 0 para Inactivo
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
