<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConductoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conductores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('number', 20)->unique(); // DNI o número de licencia
            $table->json('licencias')->nullable(); // Array de licencias
            $table->string('address')->nullable();
            $table->string('telephone_1', 20)->nullable();
            $table->string('telephone_2', 20)->nullable();
            $table->string('telephone_3', 20)->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conductores');
    }
}
