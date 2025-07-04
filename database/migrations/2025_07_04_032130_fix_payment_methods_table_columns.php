<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            // Supprimer les colonnes obsolètes si elles existent
            if (Schema::hasColumn('payment_methods', 'provider')) {
                $table->dropColumn('provider');
            }
            
            if (Schema::hasColumn('payment_methods', 'provider_id')) {
                $table->dropColumn('provider_id');
            }
            
            if (Schema::hasColumn('payment_methods', 'card_brand')) {
                $table->dropColumn('card_brand');
            }
            
            if (Schema::hasColumn('payment_methods', 'card_last_four')) {
                $table->dropColumn('card_last_four');
            }
            
            if (Schema::hasColumn('payment_methods', 'paypal_email')) {
                $table->dropColumn('paypal_email');
            }
            
            if (Schema::hasColumn('payment_methods', 'expires_at')) {
                $table->dropColumn('expires_at');
            }
            
            if (Schema::hasColumn('payment_methods', 'billing_details')) {
                $table->dropColumn('billing_details');
            }
            
            // Modifier la colonne type si elle n'a pas les bonnes valeurs
            if (Schema::hasColumn('payment_methods', 'type')) {
                $table->enum('type', ['stripe', 'paypal'])->default('stripe')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            // Restaurer les colonnes supprimées si nécessaire
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->string('paypal_email')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('billing_details')->nullable();
        });
    }
};
