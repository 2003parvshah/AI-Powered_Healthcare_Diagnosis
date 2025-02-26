<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentFeedbackTable extends Migration
{
    public function up()
    {
        Schema::create('appointment_feedback', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appointment_id')->unique();
            $table->integer('rating')->check('rating BETWEEN 1 AND 5');
            $table->text('feedback')->nullable();

            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointment_feedback');
    }
}
