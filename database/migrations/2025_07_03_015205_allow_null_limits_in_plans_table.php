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
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('max_licenses')->nullable()->change();
            $table->integer('max_projects')->nullable()->change();
            $table->integer('max_clients')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('max_licenses')->nullable(false)->change();
            $table->integer('max_projects')->nullable(false)->change();
            $table->integer('max_clients')->nullable(false)->change();
        });
    }
};
