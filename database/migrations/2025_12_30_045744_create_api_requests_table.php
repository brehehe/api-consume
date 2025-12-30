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
        Schema::create('api_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('api_collections')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('method')->default('GET'); // GET, POST, PUT, DELETE, PATCH, etc.
            $table->text('url');
            $table->json('headers')->nullable(); // [{key: '', value: '', enabled: true}]
            $table->json('query_params')->nullable(); // [{key: '', value: '', enabled: true}]
            $table->enum('body_type', ['none', 'json', 'form-data', 'x-www-form-urlencoded', 'raw'])->default('none');
            $table->text('body')->nullable();
            $table->enum('auth_type', ['none', 'bearer', 'basic', 'api-key'])->default('none');
            $table->json('auth_data')->nullable(); // {token: '', username: '', password: '', etc}
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['collection_id', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_requests');
    }
};
