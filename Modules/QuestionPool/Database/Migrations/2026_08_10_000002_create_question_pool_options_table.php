<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionPoolOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_pool_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('question_pool_question_id')->unsigned();
            $table->text('title')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 = wrong, 1 = correct');
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->foreign('question_pool_question_id')->references('id')->on('question_pool_questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_pool_options');
    }
}
