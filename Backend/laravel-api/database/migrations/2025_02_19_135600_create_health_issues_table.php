<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('health_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->text('symptoms');
            $table->string('report_pdf')->nullable(); // Path to uploaded PDF report
            $table->string('report_image')->nullable(); // Path to uploaded image
            $table->string('doctor_type'); // Type of doctor to consult
            $table->string('diagnosis')->nullable(); // Diagnosed disease
            $table->text('solution')->nullable(); // Treatment/solution
            $table->text('other_info')->nullable(); // Extra details
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_issues');
    }
};
