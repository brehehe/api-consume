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
        Schema::table('api_collections', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('api_collections')->onDelete('cascade');
        });

        Schema::table('api_requests', function (Blueprint $table) {
            // Drop existing foreign key and column first if needed, or make nullable
            // Since we're using SQLite/Postgres/MySQL, modification might vary.
            // Simplest way is modifying the column.

            // Add workspace_id for root requests
            $table->foreignUuid('workspace_id')->nullable()->constrained()->onDelete('cascade');
        });

        // Make collection_id nullable
        Schema::table('api_requests', function (Blueprint $table) {
             $table->foreignId('collection_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_collections', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });

        Schema::table('api_requests', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
            // Reverting nullable is tricky without knowing data state
        });
    }
};
