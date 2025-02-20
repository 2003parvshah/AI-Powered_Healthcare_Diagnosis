<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalDegreesTable extends Migration
{
    public function up()
    {
        Schema::create('medical_degrees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_degrees');
    }
}
