<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateWebPageTaxisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_page_taxis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('services')->nullable();
            $table->json('about')->nullable();
            $table->string('contact_image')->nullable();
            $table->json('client_says')->nullable();
            $table->json('why_choose')->nullable();
            $table->timestamps();
        });
        // Insertar un registro inicial vacío
        DB::table('web_page_taxis')->insert([
            'services' => [],
            'about' => ['text' => '', 'image' => null],
            'client_says' => [],
            'why_choose' => [
                ['title' => 'Mejores Conductores', 'description' => 'Contamos con conductores profesionales y vehículos en excelente estado.'],
                ['title' => 'Seguro y Confiable', 'description' => 'Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido
                            legible de una página cuando la mira.'],
                ['title' => 'Soporte 24x7', 'description' => 'Ofrecemos las mejores tarifas del mercado sin comprometer la calidad.']
            ],
            'contact_image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_page_taxis');
    }
}
