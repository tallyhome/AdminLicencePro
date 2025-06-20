<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_role', function (Blueprint $table) {
            $table->foreignId('admin_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->primary(['admin_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_role');
    }
};