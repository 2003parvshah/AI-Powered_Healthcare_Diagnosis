<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('doctor_timeTable', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade'); // Links to doctors table
            // $table->foreignId('day')->constrained('weeks')->onDelete('cascade'); // Links to weeks table
            $table->enum('day', [
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday'
            ]);
            // $table->string('time'); // Stores the time
            $table->time('start_time'); // Stores time in UTC
            $table->time('end_time'); // Stores time in UTC
            $table->string('timezone')->default('Asia/Kolkata'); // Stores only timezone
            $table->string('address'); // Clinic or hospital address
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_timeTable');
    }
};
