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
        Schema::create('workspace_members', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('workspace_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['owner', 'admin', 'member', 'viewer'])->default('member');
            $table->foreignUuid('invited_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table->unique(['workspace_id', 'user_id']);
            $table->index(['workspace_id', 'accepted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_members');
    }
};
