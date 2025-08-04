<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYapeNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yape_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamp('notification_date');
            $table->text('message');
            $table->json('raw_notification');
            $table->string('package_name');
            $table->string('title');
            $table->timestamps();

            // Índices para búsquedas
            $table->index(['sender', 'created_at']);
            $table->index(['amount', 'created_at']);
            $table->index('notification_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yape_notifications');
    }
}
