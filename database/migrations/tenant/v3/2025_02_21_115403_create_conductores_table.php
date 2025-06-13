<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConductoresTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conductores', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('numero_documento');
            $table->string('licencia_conducir');
            $table->string('categoria_licencia'); // Categoria de licencia: A1, A2, B1, B2, etc.
            $table->string('tipo_licencia'); // Tipo de licencia: A, B, C, etc.
            $table->date('fecha_vencimiento_licencia')->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('fecha_nacimiento')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conductores');
    }
}
