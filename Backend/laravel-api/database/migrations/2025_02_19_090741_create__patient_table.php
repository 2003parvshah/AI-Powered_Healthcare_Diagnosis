<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->foreignId('patient_id')->primary()->constrained('users')->onDelete('cascade');
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->text('medical_history')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
};
