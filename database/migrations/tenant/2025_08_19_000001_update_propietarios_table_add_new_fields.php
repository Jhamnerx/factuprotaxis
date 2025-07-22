<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePropietariosTableAddNewFields extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('propietarios', function (Blueprint $table) {
            // Añadir fecha de nacimiento
            $table->date('fecha_nacimiento')->nullable()->after('name');

            // Añadir los nuevos campos de teléfono
            $table->string('telephone_1', 20)->nullable()->after('email');
            $table->string('telephone_2', 20)->nullable()->after('telephone_1');
            $table->string('telephone_3', 20)->nullable()->after('telephone_2');

            // Quitar el campo telephone antiguo
            $table->dropColumn('telephone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('propietarios', function (Blueprint $table) {
            // Restaurar el campo telephone
            $table->string('telephone')->nullable()->after('email');

            // Quitar los nuevos campos
            $table->dropColumn(['fecha_nacimiento', 'telephone_1', 'telephone_2', 'telephone_3']);
        });
    }
}
