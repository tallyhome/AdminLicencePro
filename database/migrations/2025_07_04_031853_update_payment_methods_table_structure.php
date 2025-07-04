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
            // Vérifier si les colonnes n'existent pas déjà avant de les ajouter
            if (!Schema::hasColumn('payment_methods', 'stripe_payment_method_id')) {
                $table->string('stripe_payment_method_id')->nullable()->after('type');
            }
            
            if (!Schema::hasColumn('payment_methods', 'paypal_billing_agreement_id')) {
                $table->string('paypal_billing_agreement_id')->nullable()->after('stripe_payment_method_id');
            }
            
            if (!Schema::hasColumn('payment_methods', 'last_four')) {
                $table->string('last_four', 4)->nullable()->after('paypal_billing_agreement_id');
            }
            
            if (!Schema::hasColumn('payment_methods', 'brand')) {
                $table->string('brand')->nullable()->after('last_four');
            }
            
            if (!Schema::hasColumn('payment_methods', 'exp_month')) {
                $table->integer('exp_month')->nullable()->after('brand');
            }
            
            if (!Schema::hasColumn('payment_methods', 'exp_year')) {
                $table->integer('exp_year')->nullable()->after('exp_month');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_payment_method_id',
                'paypal_billing_agreement_id',
                'last_four',
                'brand',
                'exp_month',
                'exp_year'
            ]);
        });
    }
};
