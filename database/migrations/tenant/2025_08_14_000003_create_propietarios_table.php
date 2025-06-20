<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropietariosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('propietarios', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('identity_document_type_id');
            $table->string('number')->index();
            $table->string('name')->index();
            $table->string('trade_name')->nullable();

            $table->char('country_id', 2);
            $table->char('department_id', 2)->nullable();
            $table->char('province_id', 4)->nullable();
            $table->char('district_id', 6)->nullable();
            $table->string('address_type_id')->nullable();

            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->boolean('enabled')->default(true)->index();
            $table->text('website')->nullable()->comment('Sitio Web');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('person_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('person_id')->references('id')->on('persons');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('identity_document_type_id')->references('id')->on('cat_identity_document_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propietarios');
    }
}
