<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Personal Details
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('nationality')->nullable();
            $table->text('languages_spoken')->nullable();
            
            // Contact Information
            $table->string('primary_phone_number');
            $table->text('home_address')->nullable();
            $table->text('clinic_hospital_address')->nullable();
            
            // Professional Information   Backend/laravel-api/database/migrations/2025_02_19_090722_create__docter_table.php
            $table->enum('specialization', [
                'Cardiology', // Heart Specialist
                'Neurology', // Brain and Nervous System
                'Dermatology', // Skin Specialist
                'Orthopedics', // Bone and Joint Specialist
                'Pediatrics', // Child Specialist
                'Gynecology', // Women's Health
                'Oncology', // Cancer Specialist
                'Psychiatry', // Mental Health
                'Ophthalmology', // Eye Specialist
                'ENT', // Ear, Nose, and Throat
                'Endocrinology', // Hormonal Disorders
                'Gastroenterology', // Digestive System
                'Nephrology', // Kidney Specialist
                'Pulmonology', // Lung and Respiratory Diseases
                'Urology', // Urinary Tract and Male Reproductive System
                'Rheumatology', // Autoimmune and Joint Diseases
                'Hematology', // Blood Disorders
                'Anesthesiology', // Pain Management and Anesthesia
                'Radiology', // Imaging and Diagnosis
                'Pathology', // Laboratory and Diagnostic Testing
                'General Surgery', // Broad Surgical Procedures
                'Plastic Surgery', // Cosmetic and Reconstructive Surgery
            ])->nullable();
            
            $table->string('license_number')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->enum('medical_degrees', [
                'MBBS', // Bachelor of Medicine, Bachelor of Surgery
                'MD', // Doctor of Medicine
                'DO', // Doctor of Osteopathic Medicine
                'DM', // Doctorate of Medicine (Super Specialization)
                'DNB', // Diplomate of National Board
                'MS', // Master of Surgery
                'MCh', // Master of Chirurgiae (Super Specialization)
                'BDS', // Bachelor of Dental Surgery
                'MDS', // Master of Dental Surgery
                'BAMS', // Bachelor of Ayurvedic Medicine and Surgery
                'BHMS', // Bachelor of Homeopathic Medicine and Surgery
                'BUMS', // Bachelor of Unani Medicine and Surgery
                'PhD', // Doctor of Philosophy in Medicine
            ])->nullable();
            
            $table->string('board_certifications')->nullable();
            $table->string('university_college_attended')->nullable();
            $table->string('medical_council_registration_number')->nullable();
            $table->text('professional_memberships')->nullable();
            $table->text('research_publications')->nullable();
            
            // Work Experience
            $table->string('current_hospital_clinic')->nullable();
            $table->text('previous_workplaces')->nullable();
            $table->text('internship_residency_details')->nullable();
            
            // Availability & Consultation
            $table->text('consultation_hours')->nullable();
            $table->boolean('online_consultation_availability')->default(false);
            $table->boolean('walk_in_availability')->default(false);
            $table->boolean('appointment_booking_required')->default(true);
            // $table->integer('max_patients_per_day')->nullable();
            $table->integer('time_of_one_appointment')->nullable();
            
            // Treatment & Expertise
            $table->text('surgical_expertise')->nullable();
            $table->text('treatment_approach')->nullable();
            
            // Fee & Insurance Details
            $table->decimal('consultation_fees', 10, 2)->nullable();
            $table->text('payment_methods_accepted')->nullable();
            
            // Reviews & Ratings
            $table->float('average_rating')->default(0);
            // $table->text('patient_reviews')->nullable();
            
            // Additional Details
            $table->text('awards_recognitions')->nullable();
            
            // Digital Presence & Social Links
            $table->string('website_url')->nullable();
            $table->string('linkedin_profile')->nullable();
            $table->string('twitter_x_handle')->nullable();
            $table->string('youtube_channel')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctors');
    }
};
