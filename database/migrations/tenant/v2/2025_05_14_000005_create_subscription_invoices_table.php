<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('subscription_id');
            $table->unsignedBigInteger('vehiculo_id')->nullable();
            $table->decimal('monto', 11, 4);
            $table->date('fecha_cobro');
            $table->string('estado')->default('pagado'); // ej. pagado, pendiente, fallido
            $table->json('data')->nullable(); // almacena estructura de años y meses pagados [2024 => [1,2,3,4]]
            $table->string('tipo')->default('recurring'); // ej. recurring, one-time
            $table->decimal('descuento', 11, 4)->nullable(); // descuento aplicado a la factura
            $table->string('moneda', 3)->default('USD'); // moneda de la factura
            $table->string('metodo_pago')->nullable(); // método de pago utilizado
            $table->boolean('payed_total')->default(false); // indica si la factura ha sido pagada
            $table->foreign('vehiculo_id')
                ->references('id')
                ->on('vehiculos')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_invoices');
    }
};
