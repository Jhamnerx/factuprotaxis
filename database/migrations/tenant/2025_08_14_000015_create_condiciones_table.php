<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCondicionesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('condiciones', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->text('descripcion')->nullable();  //TUC - RECIBO - TRAMITE BAJA - PAGO LOGO - NO REGISTRADO - DE BAJA - LIBRE
            $table->string('color')->nullable(); // Color de la condición
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modelos');
    }
};
