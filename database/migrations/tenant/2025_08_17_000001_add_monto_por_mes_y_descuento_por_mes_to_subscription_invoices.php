<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMontoPorMesYDescuentoPorMesToSubscriptionInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_invoices', function (Blueprint $table) {
            $table->decimal('monto_por_mes', 12, 2)->nullable()->after('monto');
            $table->decimal('descuento_por_mes', 12, 2)->nullable()->after('descuento');
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
            $table->dropColumn('monto_por_mes');
            $table->dropColumn('descuento_por_mes');
        });
    }
}
