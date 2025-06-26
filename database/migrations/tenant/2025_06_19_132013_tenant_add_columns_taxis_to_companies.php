<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsTaxisToCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('codigo')->after('number')->nullable();
            $table->string('partida_registral')->after('codigo')->nullable();
            $table->text('representante_legal_name')->after('partida_registral')->nullable();
            $table->string('representante_legal_dni')->after('representante_legal_name')->nullable();
            $table->string('representante_legal_email')->after('representante_legal_dni')->nullable();
            $table->string('representante_legal_phone')->after('representante_legal_email')->nullable();
            $table->unsignedInteger('planes_producto_id')->after('planes_producto_id')->nullable();

            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('certificate_due');
        });
    }
}
