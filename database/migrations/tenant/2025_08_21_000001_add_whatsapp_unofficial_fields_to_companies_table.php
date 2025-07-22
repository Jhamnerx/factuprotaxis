<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWhatsappUnofficialFieldsToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('ws_unofficial_api_key')->nullable()->after('ws_api_phone_number_id');
            $table->string('ws_unofficial_sender')->nullable()->after('ws_unofficial_api_key');
            $table->string('ws_unofficial_url')->nullable()->after('ws_unofficial_sender');
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
            $table->dropColumn(['ws_unofficial_api_key', 'ws_unofficial_sender', 'ws_unofficial_url']);
        });
    }
}
