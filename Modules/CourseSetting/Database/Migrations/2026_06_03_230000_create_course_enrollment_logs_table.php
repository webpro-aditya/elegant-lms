<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseEnrollmentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_enrollment_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('user_id')->comment('Student being acted upon');
            $table->unsignedInteger('performed_by')->nullable()->comment('Admin/user who performed the action');
            $table->string('action', 50)->comment('enrolled, updated, removed');
            $table->text('details')->nullable()->comment('JSON-encoded details of the change');
            $table->integer('lms_id')->nullable();
            $table->timestamps();

            $table->index(['course_id', 'created_at']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_enrollment_logs');
    }
}
