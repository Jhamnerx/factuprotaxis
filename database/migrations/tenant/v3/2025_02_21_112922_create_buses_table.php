<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('placa');
            $table->string('marca');
            $table->string('modelo');
            $table->integer('cantidad_asientos');
            $table->string('tipo')->default('normal'); // Tipos: normal, cama, semicama
            $table->string('estado')->default('activo'); // Estados: activo, inactivo
            $table->integer('pisos')->default(1);
            $table->string('numero_habilitacion_vehicular')->nullable();
            $table->timestamps();
        });


        Schema::create('asientos', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('bus_id');
            $table->foreign('bus_id')->references('id')->on('buses')->onDelete('cascade');
            $table->integer('numero');  // Número o posición del asiento
            $table->string('tipo');     // Ejemplo: VIP, Regular, etc.
            $table->decimal('costo', 11, 4); // Costo asociado al asiento
            $table->integer('posicion_x')->nullable(); // Opcional: coordenada x
            $table->integer('posicion_y')->nullable(); // Opcional: coordenada y
            $table->timestamps();
        });

        //    $asientos_config = [
        //             {
        //                 "numero": 6,
        //                 "x": 280,
        //                 "y": 85,
        //                 "tipo": "1"
        //             },
        //             {
        //                 "numero": 6,
        //                 "x": 280,
        //                 "y": 85,
        //                 "tipo": "1"
        //             },
        //             {
        //                 "numero": 66,
        //                 "x": 182,
        //                 "y": 78,
        //                 "tipo": "1"
        //             }
        //         ];          
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
