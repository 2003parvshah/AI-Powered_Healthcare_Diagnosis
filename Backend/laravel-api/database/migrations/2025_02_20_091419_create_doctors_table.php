<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->unsignedBigInteger('id')->unique();
            $table->unsignedBigInteger('specialization_id');
            $table->unsignedBigInteger('degree_id');
            $table->string('license_number')->unique();
            $table->timestamps();

            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('specialization_id')->references('id')->on('specializations');
            $table->foreign('degree_id')->references('id')->on('medical_degrees');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}
