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
        Schema::create('api_environment_variables', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('environment_id')->constrained('api_environments')->onDelete('cascade');
            $table->string('key');
            $table->text('value')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_secret')->default(false);
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->index(['environment_id', 'key']);
            $table->unique(['environment_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_environment_variables');
    }
};
