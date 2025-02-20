<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorProfessionalInfoTable extends Migration
{
    public function up()
    {
        Schema::create('doctor_professional_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id')->unique();
            $table->string('board_certifications')->nullable();
            $table->string('university_college_attended')->nullable();
            $table->string('medical_council_registration_number')->nullable();
            $table->text('professional_memberships')->nullable();
            $table->text('research_publications')->nullable();
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_professional_info');
    }
}
