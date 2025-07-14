<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAjustesWebTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ajustes_web', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->text('valor')->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        // Insertar ajustes predeterminados
        DB::table('ajustes_web')->insert([
            [
                'nombre' => 'logo_web',
                'valor' => null,
                'descripcion' => 'Logo para el sitio web',
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'colores_primarios',
                'valor' => '#3E4095',
                'descripcion' => 'Color primario para el sitio web',
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'colores_secundarios',
                'valor' => '#FF9E1B',
                'descripcion' => 'Color secundario para el sitio web',
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
        Schema::dropIfExists('ajustes_web');
    }
}
