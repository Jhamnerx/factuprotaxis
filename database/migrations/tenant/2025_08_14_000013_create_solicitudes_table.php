<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->enum('tipo', ['registro', 'baja', 'cambio_propietario', 'emision', 'correccion_datos']);
            $table->text('tipo_baja')->nullable();
            $table->unsignedBigInteger('constancia_id')->nullable();
            $table->foreign('constancia_id')->references('id')->on('constancias_baja')->onDelete('set null');
            $table->text('descripcion')->nullable(); // Descripción del motivo o contexto de la solicitud
            $table->text('motivo')->nullable(); // Motivo de la solicitud
            $table->unsignedInteger('usuario_id')->nullable(); // ID del usuario que realiza la solicitud
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->string('observaciones')->nullable();
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada'])->default('pendiente');
            $table->timestamp('fecha')->useCurrent();
            $table->json('documentos_adjuntos')->nullable(); // Almacenar referencias a archivos adjuntos.
            $table->unsignedInteger('user_id')->nullable(); // ID del usuario que crea la solicitud
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_registro');
    }
}
