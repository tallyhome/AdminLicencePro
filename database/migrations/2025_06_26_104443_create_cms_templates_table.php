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
        Schema::create('cms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // modern, classic
            $table->string('display_name'); // Moderne, Classique
            $table->text('description')->nullable();
            $table->string('preview_image')->nullable();
            $table->json('config')->nullable(); // Configuration du template (colors, fonts, etc.)
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_templates');
    }
};
