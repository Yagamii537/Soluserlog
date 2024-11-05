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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id'); // Relación con el cliente
            $table->string('nombre_sucursal')->nullable(); // Nombre de la sucursal (opcional)
            $table->string('direccion'); // Dirección de la sucursal
            $table->string('ciudad'); // Ciudad de la sucursal
            $table->string('provincia'); // provincia
            $table->string('zona'); // provincia

            // Llave foránea con clientes
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');

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
        Schema::dropIfExists('addresses');
    }
};
