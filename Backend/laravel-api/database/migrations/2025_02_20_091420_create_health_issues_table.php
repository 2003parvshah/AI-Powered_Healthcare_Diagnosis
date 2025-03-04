<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthIssuesTable extends Migration
{
    public function up()
    {
        Schema::create('health_issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('patient_id');
            $table->string('diagnosis')->nallable();
            $table->json('solution')->nullable();
            $table->text('symptoms')->nullable();
            $table->string('report_pdf')->nullable();
            $table->string('report_image')->nullable();
            $table->string('doctor_type')->nullable();
            $table->text('other_info')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_issues');
    }
}
