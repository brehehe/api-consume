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
        Schema::create('workspace_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('workspace_id')->constrained()->onDelete('cascade');
            $table->string('email');
            $table->string('token')->unique();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'expired'])->default('pending');
            $table->foreignUuid('invited_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['email', 'status']);
            $table->index(['token', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_invitations');
    }
};
