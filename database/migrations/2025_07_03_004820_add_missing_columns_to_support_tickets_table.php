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
        Schema::table('support_tickets', function (Blueprint $table) {
            // Colonnes d'administration
            $table->foreignId('admin_id')->nullable()->constrained()->onDelete('set null')->after('client_id');
            $table->foreignId('assigned_to')->nullable()->constrained('admins')->onDelete('set null')->after('admin_id');
            
            // Colonnes de suivi temporel
            $table->timestamp('last_activity_at')->nullable()->after('closed_at');
            $table->timestamp('last_reply_by_client_at')->nullable()->after('last_activity_at');
            $table->timestamp('last_reply_by_admin_at')->nullable()->after('last_reply_by_client_at');
            $table->timestamp('last_read_by_client_at')->nullable()->after('last_reply_by_admin_at');
            $table->timestamp('last_read_by_admin_at')->nullable()->after('last_read_by_client_at');
            
            // Colonnes de fermeture
            $table->boolean('closed_by_client')->default(false)->after('closed_at');
            $table->boolean('closed_by_admin')->default(false)->after('closed_by_client');
            
            // Colonnes de résolution
            $table->text('resolution_notes')->nullable()->after('closed_by_admin');
            $table->json('tags')->nullable()->after('resolution_notes');
            $table->integer('estimated_resolution_time')->nullable()->after('tags'); // en minutes
            $table->integer('actual_resolution_time')->nullable()->after('estimated_resolution_time'); // en minutes
            
            // Supprimer les anciennes colonnes qui ne sont plus utilisées
            $table->dropColumn(['closed_by_id', 'closed_by_type', 'last_reply_at', 'attachments']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            // Supprimer les nouvelles colonnes
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['assigned_to']);
            $table->dropColumn([
                'admin_id',
                'assigned_to',
                'last_activity_at',
                'last_reply_by_client_at',
                'last_reply_by_admin_at',
                'last_read_by_client_at',
                'last_read_by_admin_at',
                'closed_by_client',
                'closed_by_admin',
                'resolution_notes',
                'tags',
                'estimated_resolution_time',
                'actual_resolution_time'
            ]);
            
            // Remettre les anciennes colonnes
            $table->json('attachments')->nullable();
            $table->timestamp('last_reply_at')->nullable();
            $table->unsignedBigInteger('closed_by_id')->nullable();
            $table->string('closed_by_type')->nullable();
        });
    }
};
