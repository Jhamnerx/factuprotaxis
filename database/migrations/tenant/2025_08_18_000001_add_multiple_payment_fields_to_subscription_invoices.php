<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultiplePaymentFieldsToSubscriptionInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_invoices', function (Blueprint $table) {
            $table->boolean('es_pago_multiple')->default(false)->after('payed_total');
            $table->string('grupo_pago_id')->nullable()->after('es_pago_multiple');
            $table->integer('cantidad_meses')->nullable()->after('grupo_pago_id');
            $table->json('payment_details')->nullable()->after('cantidad_meses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_invoices', function (Blueprint $table) {
            $table->dropColumn('es_pago_multiple');
            $table->dropColumn('grupo_pago_id');
            $table->dropColumn('cantidad_meses');
            $table->dropColumn('payment_details');
        });
    }
}
