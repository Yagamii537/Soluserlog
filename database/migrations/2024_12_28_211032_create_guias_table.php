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
        Schema::create('guias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manifiesto_id'); // Relación con manifiestos
            $table->unsignedBigInteger('conductor_id'); // Relación con conductores
            $table->string('empresa'); // Empresa responsable
            $table->string('origen'); // Origen
            $table->date('fecha_emision'); // Fecha de emisión
            $table->string('ayudante')->nullable();
            $table->timestamps();

            // Relaciones
            $table->foreign('manifiesto_id')->references('id')->on('manifiestos')->onDelete('cascade');
            $table->foreign('conductor_id')->references('id')->on('conductores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guias');
    }
};
