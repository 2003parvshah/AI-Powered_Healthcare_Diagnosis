<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorFeesTable extends Migration
{
    public function up()
    {
        Schema::create('doctor_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id')->unique();
            $table->decimal('consultation_fees', 10, 2)->nullable();
            $table->text('payment_methods_accepted')->nullable();
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_fees');
    }
}
