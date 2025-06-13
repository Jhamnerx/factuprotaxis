<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasPasajesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas_pasajes', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('programacion_id');
            $table->foreign('programacion_id')->references('id')->on('programaciones')->onDelete('cascade');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes_pasajeros')->onDelete('cascade');
            $table->unsignedBigInteger('pasaje_id');
            $table->foreign('pasaje_id')->references('id')->on('pasajes')->onDelete('cascade');
            $table->integer('asiento');
            $table->decimal('precio', 10, 2);
            $table->enum('estado', ['reservado', 'vendido', 'cancelado'])->default('reservado');
            $table->json('menores')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas_pasajes');
    }
}
