<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollTable extends Migration
{
    public function up()
    {
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id('id_enroll');
            $table->unsignedBigInteger('id_student');
            $table->unsignedBigInteger('id_teacher');
            $table->unsignedBigInteger('id_course');
            $table->enum('status', ['enroll', 'tidak'])->default('tidak');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_enrollments');
    }
}
