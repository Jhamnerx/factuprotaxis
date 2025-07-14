<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTerminosCondicionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminos_condiciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->text('contenido');
            $table->string('version')->nullable();
            $table->date('fecha_actualizacion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        // Insertar términos y condiciones predeterminados
        DB::table('terminos_condiciones')->insert([
            [
                'titulo' => 'Términos y Condiciones',
                'contenido' => 'Aquí va el contenido de los términos y condiciones...',
                'version' => '1.0',
                'fecha_actualizacion' => now(),
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terminos_condiciones');
    }
}
