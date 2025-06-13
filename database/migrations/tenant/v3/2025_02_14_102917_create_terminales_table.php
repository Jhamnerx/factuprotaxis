<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerminalesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('terminales', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('codigo')->unique(); // Código único de la terminal
            $table->string('nombre');
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('encargado')->nullable();
            $table->text('departamento')->nullable();
            $table->text('provincia')->nullable();
            $table->boolean('estado')->default(true); // Estado de la terminal: activa o inactiva
            $table->timestamps();
            $table->softDeletes(); // Para manejar eliminaciones suaves
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminales');
    }
}
