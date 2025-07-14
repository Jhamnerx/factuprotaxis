<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePoliticasPrivacidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('politicas_privacidad', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->text('contenido');
            $table->string('version')->nullable();
            $table->date('fecha_actualizacion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        // Insertar política de privacidad predeterminada
        DB::table('politicas_privacidad')->insert([
            [
                'titulo' => 'Política de Privacidad',
                'contenido' => 'Aquí va el contenido de la política de privacidad...',
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
        Schema::dropIfExists('politicas_privacidad');
    }
}
