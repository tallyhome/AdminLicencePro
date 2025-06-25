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
        Schema::create('licence_accounts', function (Blueprint $table) {
            $table->id();
            
            // Référence vers la clé de série
            $table->unsignedBigInteger('serial_key_id');
            $table->foreign('serial_key_id')->references('id')->on('serial_keys')->onDelete('cascade');
            
            // Informations du compte/domaine
            $table->string('domain')->nullable();
            $table->string('ip_address')->nullable();
            
            // Statut du compte
            $table->enum('status', ['active', 'suspended', 'revoked'])->default('active');
            
            // Dates importantes
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            
            $table->timestamps();
            
            // Index pour optimiser les recherches
            $table->index(['serial_key_id', 'domain']);
            $table->index(['serial_key_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licence_accounts');
    }
};
