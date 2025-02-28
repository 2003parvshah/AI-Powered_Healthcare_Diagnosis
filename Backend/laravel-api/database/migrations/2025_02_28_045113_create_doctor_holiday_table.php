<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('doctor_holiday', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->unsignedBigInteger('doctor_id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('timezone');
            $table->boolean('byDoctor')->default(true);
            // Foreign Key Constraint
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');

            $table->timestamps(); // Created_at & Updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
