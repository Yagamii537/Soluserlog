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
        Schema::create('manifiestos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha'); // Fecha del manifiesto
            $table->foreignId('camion_id')->constrained('camiones')->onDelete('cascade'); // Relación con camiones
            $table->string('descripcion')->nullable(); // Descripción adicional (opcional)
            $table->integer('estado');
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
        Schema::dropIfExists('manifiestos');
    }
};
