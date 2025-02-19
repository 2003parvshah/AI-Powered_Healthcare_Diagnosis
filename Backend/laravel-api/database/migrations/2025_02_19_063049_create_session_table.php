<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id(); // This is equivalent to bigIncrements('id') and sets AUTO_INCREMENT
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
            
            // $table->string('id')->primary();
            // // $table->foreignId('user_id')->nullable()->index();
            // $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            // $table->string('ip_address', 45)->nullable();
            // $table->text('user_agent')->nullable();
            // $table->text('payload');
            // $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
};