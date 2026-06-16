<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('payment_methods')->insert([
            'method' => 'Skiply',
            'type' => 'System',
            'active_status' => 0,
            'module_status' => 1,
            'logo' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::table('payment_method_credentials', function (Blueprint $table) {
            $table->string('SKIPLY_CLIENT_ID')->nullable();
            $table->string('SKIPLY_CLIENT_SECRET')->nullable();
            $table->string('SKIPLY_SALT')->nullable();
            $table->string('SKIPLY_ENVIRONMENT')->nullable()->default('Sandbox');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('payment_methods')->where('method', 'Skiply')->delete();

        Schema::table('payment_method_credentials', function (Blueprint $table) {
            $table->dropColumn(['SKIPLY_CLIENT_ID', 'SKIPLY_CLIENT_SECRET', 'SKIPLY_SALT', 'SKIPLY_ENVIRONMENT']);
        });
    }
};
