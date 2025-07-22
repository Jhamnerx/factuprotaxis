<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailPasswordToConductoresTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('conductores', function (Blueprint $table) {
            $table->string('email')->nullable()->after('telephone_3');
            $table->string('password')->nullable()->after('email');
            $table->timestamp('email_verified_at')->nullable()->after('password');
            $table->rememberToken()->after('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conductores', function (Blueprint $table) {
            $table->dropColumn(['email', 'password', 'email_verified_at', 'remember_token']);
        });
    }
}
