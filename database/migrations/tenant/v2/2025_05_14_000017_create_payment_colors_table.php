<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentColorsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_colors', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('colorable_id');
            $table->string('colorable_type');
            $table->integer('year');
            $table->integer('month');
            $table->string('color', 7); // formato hexadecimal ej. "#FFFFFF"
            $table->timestamps();

            $table->unique(['colorable_id', 'colorable_type', 'year', 'month'], 'unique_color_per_month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_colors');
    }
}
