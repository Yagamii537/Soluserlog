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
        Schema::create('detalle_bitacora_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detalle_bitacora_id')->constrained('detalle_bitacoras')->onDelete('cascade'); // Relación con detalle_bitacoras
            $table->string('image_path'); // Ruta de la imagen almacenada
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
        Schema::dropIfExists('detalle_bitacora_images');
    }
};
