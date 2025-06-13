<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermisosUnidadTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permisos_unidad', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('vehiculo_id');
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->onDelete('cascade');
            $table->json('vehiculo')->nullable();
            $table->json('propietario')->nullable();
            $table->string('tipo_permiso'); // Tipo de permiso: uso particular, uso institucional, etc.
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->text('motivo'); // Motivo del permiso
            $table->string('estado')->default('pendiente'); // Estado del permiso: pendiente, aprobado, rechazado
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('observaciones')->nullable(); // Observaciones adicionales sobre el permiso
            $table->json('personas_autorizadas')->nullable(); // json de personas [{id: 1, nombre: 'Juan Perez', documento: '12345678'}, ...]       
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisos_unidad');
    }
}
