<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWastereportTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('wastereport', function (Blueprint $table) {
            $table->id();
            $table->string('user_email'); // Assuming user_email is needed  
            $table->string('waste_type');
            $table->string('status')->default('pending');
            $table->float('amount');
            $table->string('unit');
            $table->string('location');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('wastereport');
    }
}
