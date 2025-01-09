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
        Schema::create('facturaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manifiesto_id'); // Relación obligatoria con manifiestos
            $table->unsignedBigInteger('order_id')->nullable(); // Relación opcional con orders
            $table->unsignedBigInteger('document_id')->nullable(); // Relación opcional con documents
            $table->decimal('valor', 10, 2)->nullable(); // Campo para el valor
            $table->decimal('adicional', 10, 2)->nullable(); // Campo para adicional
            $table->decimal('total', 10, 2)->nullable(); // Campo para total
            $table->timestamps();

            // Relación con manifiestos
            $table->foreign('manifiesto_id')->references('id')->on('manifiestos')->onDelete('cascade');

            // Relación con orders
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            // Relación con documents
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturaciones');
    }
};
