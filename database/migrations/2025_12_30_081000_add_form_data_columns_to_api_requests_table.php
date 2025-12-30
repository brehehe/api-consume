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
        Schema::table('api_requests', function (Blueprint $table) {
            $table->json('form_data')->nullable()->after('body');
            $table->json('form_urlencoded_data')->nullable()->after('form_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_requests', function (Blueprint $table) {
            $table->dropColumn(['form_data', 'form_urlencoded_data']);
        });
    }
};
