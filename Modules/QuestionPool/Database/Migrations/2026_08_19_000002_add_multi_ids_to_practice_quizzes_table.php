<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultiIdsToPracticeQuizzesTable extends Migration
{
    public function up()
    {
        Schema::table('practice_quizzes', function (Blueprint $table) {
            $table->text('chapter_ids')->nullable()->after('lesson_id')->comment('Comma-separated chapter IDs used in this quiz');
            $table->text('lesson_ids')->nullable()->after('chapter_ids')->comment('Comma-separated lesson IDs used in this quiz');
        });
    }

    public function down()
    {
        Schema::table('practice_quizzes', function (Blueprint $table) {
            $table->dropColumn(['chapter_ids', 'lesson_ids']);
        });
    }
}
