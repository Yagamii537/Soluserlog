<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('remitente');
            $table->string('localidad');
            $table->date('fechaCreacion');
            $table->date('fechaConfirmacion')->nullable();
            $table->string('horario');
            $table->date('fechaEntrega');
            $table->string('observacion');
            $table->integer('estado');
            $table->integer('totaBultos')->nullable();
            $table->integer('totalKgr')->nullable();

            //? RELACION UNO A UNO CON EL CLIENTE
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')
                ->references('id')
                ->on('clientes')
                ->onDelete('cascade')
                ->onUpdate('cascade');



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
        Schema::dropIfExists('orders');
    }
};
