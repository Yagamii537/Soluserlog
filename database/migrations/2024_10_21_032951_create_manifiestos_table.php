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
            $table->foreignId('conductor_id')->constrained('conductores')->onDelete('cascade'); // Relación con conductores
            $table->string('descripcion')->nullable(); // Descripción adicional (opcional)
            $table->date('fecha_inicio_traslado')->nullable(); // Fecha de inicio de traslado (opcional)
            $table->date('fecha_fin_traslado')->nullable(); // Fecha de fin de traslado (opcional)
            $table->string('numero_manifiesto')->unique(); // Número de manifiesto único
            $table->integer('bultos')->nullable(); // Cantidad total de bultos
            $table->decimal('kilos', 8, 2)->nullable(); // Peso total en kilos (formato decimal)
            $table->integer('estado');
            $table->string('tipoFlete')->nullable();
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
