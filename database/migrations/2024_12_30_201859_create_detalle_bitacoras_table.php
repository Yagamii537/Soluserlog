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
        Schema::create('detalle_bitacoras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bitacora_id')->constrained('bitacoras')->onDelete('cascade'); // Relación con la bitácora
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Relación con la orden
            $table->date('fechaOrigen')->nullable();
            $table->date('fechaDestino')->nullable();
            $table->time('hora_origen_llegada')->nullable();
            $table->string('temperatura_origen')->nullable();
            $table->string('humedad_origen')->nullable();
            $table->time('hora_carga')->nullable();
            $table->time('hora_salida_origen')->nullable();
            $table->text('novedades_carga')->nullable();
            $table->string('temperatura_destino')->nullable();
            $table->string('humedad_destino')->nullable();
            $table->time('hora_destino_llegada')->nullable();
            $table->time('hora_descarga')->nullable();
            $table->time('hora_salida_destino')->nullable();
            $table->text('novedades_destino')->nullable();
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
        Schema::dropIfExists('detalle_bitacoras');
    }
};
