<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique(); // FK to logins.email
            $table->string('username');
            $table->string('profile_picture')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->nullable();
            $table->string('achievements')->nullable();
            $table->integer('contribution')->default(0);
            $table->integer('total_token')->default(0);
            $table->json('token_usages')->nullable();
            $table->timestamps();
            $table->foreign('email')->references('email')->on('logins')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
};
