<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsPracticeQuizToLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->tinyInteger('is_practice_quiz')->default(0)->after('is_assignment');
            $table->integer('practice_quiz_question_count')->default(10)->after('is_practice_quiz');
            $table->integer('practice_quiz_lesson_id')->unsigned()->nullable()->after('practice_quiz_question_count')->comment('Lesson scope for question pool');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['is_practice_quiz', 'practice_quiz_question_count', 'practice_quiz_lesson_id']);
        });
    }
}
