<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('numero_interno')->unique();
            $table->string('flota')->nullable(); // Flota del vehículo
            $table->string('placa')->unique();
            $table->decimal('largo', 8, 2)->nullable(); // Largo del vehículo
            $table->decimal('ancho', 8, 2)->nullable();
            $table->decimal('alto', 8, 2); // Ancho del vehículo
            $table->decimal('peso', 8, 2)->nullable(); // Peso del vehículo
            $table->decimal('carga_util', 10, 2)->nullable(); // VIN del vehículo
            $table->string('ccn')->nullable(); // CCN del vehículo (si aplica)
            $table->string('numero_motor')->nullable(); // Número de motor
            $table->string('ejes')->nullable();
            $table->integer('asientos')->nullable();
            $table->string('categoria')->nullable();
            $table->unsignedBigInteger('marca_id')->nullable();
            $table->unsignedBigInteger('modelo_id')->nullable();
            $table->string('color')->nullable();
            $table->year('year')->nullable();
            $table->date('fecha_ingreso')->nullable(); // Fecha de ingreso del vehículo
            $table->unsignedBigInteger('estado_tuc_id'); // ID del estado TUC
            $table->foreign('estado_tuc_id')->references('id')->on('condiciones');
            //$table->enum('estado_tuc', ['TUC', 'RECIBO', 'TRAMITE BAJA', 'PAGO LOGO', 'NO REGISTRADO', 'DE BAJA', 'LIBRE'])->nullable(); // Estado del vehículo en el sistema TUC
            $table->enum('estado', ['ACTIVO', 'DE BAJA', 'DE BAJA POR PAGO', 'SUSPECION POR TRABAJO', 'RETIRO']); // Ejemplo: ACTIVO, INACTIVO
            $table->unsignedBigInteger('propietario_id');
            $table->foreign('propietario_id')->references('id')->on('propietarios');
            $table->unsignedBigInteger('plan_id')->nullable(); // ID del plan de suscripción
            $table->unsignedBigInteger('subscription_id')->nullable(); // ID de la suscripción
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('conductor_id')->nullable(); // ID del conductor asignado
            $table->foreign('conductor_id')->references('id')->on('conductores');
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
        Schema::dropIfExists('vehiculos');
    }
}
