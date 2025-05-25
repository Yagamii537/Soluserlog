<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleBitacoraChecksTable extends Migration
{
    public function up()
    {
        Schema::create('detalle_bitacora_checks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalle_bitacora_id');
            $table->enum('tipo', ['carga', 'destino']);
            $table->string('opcion');
            $table->timestamps();

            $table->foreign('detalle_bitacora_id')
                ->references('id')
                ->on('detalle_bitacoras')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalle_bitacora_checks');
    }
}
