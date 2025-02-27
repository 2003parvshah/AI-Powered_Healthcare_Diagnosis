<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('weeks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Monday, Tuesday, etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('weeks');
    }
};
