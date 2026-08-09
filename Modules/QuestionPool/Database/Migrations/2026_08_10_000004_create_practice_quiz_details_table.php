<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePracticeQuizDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practice_quiz_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('practice_quiz_id')->unsigned();
            $table->integer('question_pool_question_id')->unsigned();
            $table->text('given_answer')->nullable();
            $table->tinyInteger('is_correct')->nullable()->comment('0 = wrong, 1 = correct');
            $table->integer('marks_obtained')->default(0);
            $table->timestamps();

            $table->foreign('practice_quiz_id')->references('id')->on('practice_quizzes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practice_quiz_details');
    }
}
