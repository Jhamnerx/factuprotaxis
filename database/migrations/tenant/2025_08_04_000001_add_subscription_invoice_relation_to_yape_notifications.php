<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubscriptionInvoiceRelationToYapeNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yape_notifications', function (Blueprint $table) {
            // Agregar campo para relacionar con subscription_invoices
            // Cambiado a unsignedBigInteger para compatibilidad con Laravel moderno
            $table->unsignedBigInteger('subscription_invoice_id')->nullable()->after('title');
            $table->string('codigo_seguridad')->nullable()->after('subscription_invoice_id');

            // Agregar campo para marcar si la notificación ya fue utilizada
            $table->boolean('is_used')->default(false)->after('subscription_invoice_id');

            // Agregar timestamp de cuando fue marcada como utilizada
            $table->timestamp('used_at')->nullable()->after('is_used');

            // Índices para optimizar las búsquedas
            $table->index('subscription_invoice_id');
            $table->index('is_used');
            $table->index(['is_used', 'amount', 'notification_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yape_notifications', function (Blueprint $table) {
            // Eliminar índices
            $table->dropIndex(['subscription_invoice_id']);
            $table->dropIndex(['is_used']);
            $table->dropIndex(['is_used', 'amount', 'notification_date']);

            // Eliminar columnas
            $table->dropColumn(['subscription_invoice_id', 'is_used', 'used_at']);
        });
    }
}
