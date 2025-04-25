<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id('id_course');
            $table->string('nama_course');
            $table->text('deskripsi');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('kat_bidang');
            $table->integer('kapasitas');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
}; 