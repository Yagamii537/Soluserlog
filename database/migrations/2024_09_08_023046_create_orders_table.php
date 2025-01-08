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
            $table->unsignedBigInteger('remitente_direccion_id'); // Clave foránea para la dirección del remitente
            $table->unsignedBigInteger('direccion_id'); // Clave foránea para la dirección del destinatario
            $table->dateTime('fechaCreacion');
            $table->dateTime('fechaConfirmacion')->nullable();
            $table->string('horario');
            $table->date('fechaEntrega');
            $table->string('observacion')->nullable();
            $table->integer('estado')->default(0);
            $table->integer('totaBultos')->nullable();
            $table->decimal('totalKgr')->nullable();
            $table->string('tracking_number')->nullable();


            // Relaciones con las direcciones de remitente y destinatario
            $table->foreign('remitente_direccion_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('direccion_id')
                ->references('id')
                ->on('addresses')
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
