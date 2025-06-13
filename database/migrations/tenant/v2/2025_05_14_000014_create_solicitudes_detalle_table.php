<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudesDetalleTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solicitudes_detalle', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('solicitud_id');
            $table->foreign('solicitud_id')->references('id')->on('solicitudes')->onDelete('cascade');
            $table->unsignedBigInteger('vehiculo_id')->nullable();
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->onDelete('cascade'); // Relación con vehículos
            $table->json('propietario')->nullable();
            $table->json('vehiculo')->nullable(); // Almacenar datos del vehículo en formato JSON
            $table->json('correcciones')->nullable(); // JSON para guardar correcciones (campo, valor anterior, valor nuevo)
            $table->boolean('unidad_laborando')->default(false); // Indica si la unidad está laborando

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_detalle');
    }
}
