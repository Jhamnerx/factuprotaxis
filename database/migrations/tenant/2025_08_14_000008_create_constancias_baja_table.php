<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstanciasBajaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('constancias_baja', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('numero')->nullable(); // Número de la constancia
            $table->unsignedBigInteger('vehiculo_id');
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->onDelete('cascade');
            $table->json('vehiculo')->nullable();
            $table->string('estado')->default('emitida'); // Estado de la constancia: emitida, anulada
            $table->date('fecha_emision');
            $table->text('observaciones')->nullable();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('constancias_baja');
    }
}
