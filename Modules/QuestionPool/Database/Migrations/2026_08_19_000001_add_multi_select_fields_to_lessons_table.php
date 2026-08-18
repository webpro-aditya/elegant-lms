<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultiSelectFieldsToLessonsTable extends Migration
{
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->text('practice_quiz_chapter_ids')->nullable()->after('practice_quiz_lesson_id')->comment('Comma-separated chapter IDs for multi-select');
            $table->text('practice_quiz_lesson_ids')->nullable()->after('practice_quiz_chapter_ids')->comment('Comma-separated lesson IDs for multi-select');
        });
    }

    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['practice_quiz_chapter_ids', 'practice_quiz_lesson_ids']);
        });
    }
}
