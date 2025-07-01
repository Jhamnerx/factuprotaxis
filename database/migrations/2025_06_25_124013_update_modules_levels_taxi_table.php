<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateModulesLevelsTaxiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('module_levels')->insert([
            ['id' => '95', 'value' => 'unidades', 'description' => 'Unidades', 'module_id' => 53],
            ['id' => '96', 'value' => 'propietarios', 'description' => 'Propietarios', 'module_id' => 53],
            ['id' => '97', 'value' => 'marcas', 'description' => 'Marcas', 'module_id' => 53],
            ['id' => '98', 'value' => 'modelos', 'description' => 'Modelos', 'module_id' => 53],
            ['id' => '99', 'value' => 'planes', 'description' => 'Planes', 'module_id' => 53],
            ['id' => '100', 'value' => 'condiciones', 'description' => 'Condiciones', 'module_id' => 53],
            ['id' => '101', 'value' => 'solicitudes', 'description' => 'Solicitudes', 'module_id' => 53],
            ['id' => '102', 'value' => 'permiso_viaje', 'description' => 'Permiso de Viaje', 'module_id' => 53],
            ['id' => '103', 'value' => 'constancia_baja', 'description' => 'Constancia de Baja', 'module_id' => 53],
            ['id' => '104', 'value' => 'declaraciones', 'description' => 'Declaraciones', 'module_id' => 53],
            ['id' => '105', 'value' => 'pagos', 'description' => 'Pagos', 'module_id' => 53],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
