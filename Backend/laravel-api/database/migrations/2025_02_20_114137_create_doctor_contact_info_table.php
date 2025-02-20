<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorContactInfoTable extends Migration
{
    public function up()
    {
        Schema::create('doctor_contact_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id')->unique();
            $table->string('primary_phone_number');
            $table->text('home_address')->nullable();
            $table->text('clinic_hospital_address')->nullable();
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_contact_info');
    }
}
