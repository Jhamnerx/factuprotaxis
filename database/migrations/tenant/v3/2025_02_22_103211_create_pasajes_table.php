<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasajesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pasajes', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('ruta_id');
            $table->foreign('ruta_id')->references('id')->on('rutas')->onDelete('cascade');
            $table->unsignedBigInteger('terminal_origen_id');
            $table->foreign('terminal_origen_id')->references('id')->on('terminales')->onDelete('cascade');
            $table->unsignedBigInteger('terminal_destino_id');
            $table->foreign('terminal_destino_id')->references('id')->on('terminales')->onDelete('cascade');
            $table->unsignedBigInteger('vehiculo_id');
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->onDelete('cascade');
            $table->unsignedBigInteger('conductor_id');
            $table->foreign('conductor_id')->references('id')->on('conductores')->onDelete('cascade');
            $table->unsignedBigInteger('programacion_id');
            $table->foreign('programacion_id')->references('id')->on('programaciones')->onDelete('cascade');
            $table->dateTime('fecha_salida');
            $table->integer('asiento');
            $table->decimal('precio', 10, 2);
            $table->enum('estado', ['reservado', 'vendido', 'cancelado']); // Reservado, Vendido, Cancelado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasajes');
    }
};
