<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartasPoderTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cartas_poder', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('otorgante_nombre');
            $table->string('otorgante_dni');
            $table->string('otorgante_direccion')->nullable();
            $table->string('otorgante_telefono')->nullable();
            $table->string('apoderado_nombre');
            $table->string('apoderado_dni');
            $table->unsignedBigInteger('vehiculo_id');
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->onDelete('cascade');
            $table->json('vehiculo')->nullable();
            $table->text('motivo');
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
        Schema::dropIfExists('cartas_poder');
    }
}
