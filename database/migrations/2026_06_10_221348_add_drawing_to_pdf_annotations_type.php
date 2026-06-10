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
        Schema::table('pdf_annotations', function (Blueprint $table) {
            DB::statement("ALTER TABLE pdf_annotations MODIFY COLUMN type ENUM('highlight', 'comment', 'drawing') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pdf_annotations', function (Blueprint $table) {
            DB::statement("ALTER TABLE pdf_annotations MODIFY COLUMN type ENUM('highlight', 'comment') NOT NULL");
        });
    }
};
