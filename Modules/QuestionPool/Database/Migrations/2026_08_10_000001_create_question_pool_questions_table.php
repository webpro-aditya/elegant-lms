<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionPoolQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_pool_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->unsigned()->nullable();
            $table->integer('chapter_id')->unsigned()->nullable();
            $table->integer('lesson_id')->unsigned()->nullable();
            $table->string('type', 2)->comment('M = MCQ, T = True/False, F = Fill in the blanks');
            $table->text('question')->nullable();
            $table->integer('marks')->default(1);
            $table->string('true_false', 1)->nullable()->comment('T or F');
            $table->text('suitable_words')->nullable();
            $table->text('explanation')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->integer('user_id')->unsigned()->nullable();
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
        Schema::dropIfExists('question_pool_questions');
    }
}
