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
        Schema::create('pdf_annotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedBigInteger('course_id');
            $table->enum('type', ['highlight', 'comment']);
            $table->integer('annot_id');          // client-side id
            $table->integer('page_num');
            $table->json('rects')->nullable();    // for highlights: [{left,top,width,height}]
            $table->text('text')->nullable();     // selected text (highlight) or comment body
            $table->string('color', 20)->nullable(); // yellow|green|blue|pink
            $table->float('pos_x')->nullable();   // comment pin X
            $table->float('pos_y')->nullable();   // comment pin Y
            $table->timestamps();

            $table->index(['user_id', 'lesson_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pdf_annotations');
    }
};
