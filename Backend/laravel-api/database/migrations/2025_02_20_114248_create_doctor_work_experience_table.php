<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorWorkExperienceTable extends Migration
{
    public function up()
    {
        Schema::create('doctor_work_experience', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id')->unique();
            $table->string('current_hospital_clinic')->nullable();
            $table->text('previous_workplaces')->nullable();
            $table->text('internship_residency_details')->nullable();
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_work_experience');
    }
}
