<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->unsignedBigInteger('id')->unique();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->text('medical_history')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_photo')->nullable();
            $table->text('past_medical_conditions')->nullable();
            $table->text('allergies')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->double('weight')->nullable();
            $table->string('blood_group')->nullable();
            $table->timestamps();

            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
