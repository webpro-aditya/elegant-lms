<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePracticeQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practice_quizzes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->integer('chapter_id')->unsigned()->nullable();
            $table->integer('lesson_id')->unsigned()->nullable();
            $table->enum('scope', ['lesson', 'chapter', 'course']);
            $table->integer('total_questions')->default(0);
            $table->integer('total_marks')->default(0);
            $table->integer('obtained_marks')->nullable();
            $table->integer('correct_answers')->default(0);
            $table->integer('wrong_answers')->default(0);
            $table->integer('estimated_time')->default(0)->comment('In minutes');
            $table->integer('time_taken')->nullable()->comment('In seconds');
            $table->tinyInteger('status')->default(0)->comment('0 = in-progress, 1 = completed');
            $table->tinyInteger('pass')->default(0)->comment('0 = failed, 1 = passed');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('lms_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practice_quizzes');
    }
}
