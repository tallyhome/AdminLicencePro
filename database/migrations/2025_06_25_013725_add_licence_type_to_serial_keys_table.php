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
        Schema::table('serial_keys', function (Blueprint $table) {
            // Vérifier si la colonne licence_type n'existe pas déjà
            if (!Schema::hasColumn('serial_keys', 'licence_type')) {
                $table->enum('licence_type', ['single', 'multi'])->default('single')->after('status');
            }
            
            // Vérifier si la colonne max_accounts n'existe pas déjà
            if (!Schema::hasColumn('serial_keys', 'max_accounts')) {
                $table->unsignedInteger('max_accounts')->default(1)->after('licence_type');
            }
            
            // Vérifier si la colonne used_accounts n'existe pas déjà
            if (!Schema::hasColumn('serial_keys', 'used_accounts')) {
                $table->unsignedInteger('used_accounts')->default(0)->after('max_accounts');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('serial_keys', function (Blueprint $table) {
            // Supprimer les colonnes si elles existent
            if (Schema::hasColumn('serial_keys', 'licence_type')) {
                $table->dropColumn('licence_type');
            }
            if (Schema::hasColumn('serial_keys', 'max_accounts')) {
                $table->dropColumn('max_accounts');
            }
            if (Schema::hasColumn('serial_keys', 'used_accounts')) {
                $table->dropColumn('used_accounts');
            }
        });
    }
};
