<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantVehicleServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('device_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('expiration_by')->nullable();
            $table->integer('interval')->default(1);
            $table->string('last_service')->nullable();
            $table->integer('trigger_event_left')->unsigned()->default(0);
            $table->boolean('renew_after_expiration')->default(false);
            $table->double('expires')->unsigned()->default(0);
            $table->date('expires_date')->nullable();
            $table->double('remind')->unsigned()->default(0);
            $table->date('remind_date')->nullable();
            $table->boolean('event_sent')->default(false);
            $table->boolean('expired')->default(false);
            $table->text('email')->nullable();
            $table->text('mobile_phone')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_services');
    }
}
