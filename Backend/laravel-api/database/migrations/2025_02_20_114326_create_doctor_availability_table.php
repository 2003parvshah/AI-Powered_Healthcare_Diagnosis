<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorAvailabilityTable extends Migration
{
    public function up()
    {
        Schema::create('doctor_availability', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id')->unique();
            $table->text('consultation_hours')->nullable();
            $table->boolean('online_consultation_availability')->default(false);
            $table->boolean('walk_in_availability')->default(false);
            $table->boolean('appointment_booking_required')->default(true);
            $table->integer('time_of_one_appointment')->nullable();
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_availability');
    }
}
