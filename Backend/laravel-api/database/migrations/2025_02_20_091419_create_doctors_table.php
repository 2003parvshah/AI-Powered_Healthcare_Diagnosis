<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique();
            $table->enum('specialization', [
                'Cardiology',
                'Neurology',
                'Dermatology',
                'Orthopedics',
                'Pediatrics',
                'Gynecology',
                'Oncology',
                'Psychiatry',
                'Ophthalmology',
                'ENT',
                'Endocrinology',
                'Gastroenterology',
                'Nephrology',
                'Pulmonology',
                'Urology',
                'Rheumatology',
                'Hematology',
                'Anesthesiology',
                'Radiology',
                'Pathology',
                'General Surgery',
                'Plastic Surgery'
            ]);
            $table->enum('degree', [
                'MBBS',
                'MD',
                'DO',
                'DM',
                'DNB',
                'MS',
                'MCh',
                'BDS',
                'MDS',
                'BAMS',
                'BHMS',
                'BUMS',
                'PhD'
            ]);
            $table->string('license_number')->unique();
            $table->timestamps();

            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}
