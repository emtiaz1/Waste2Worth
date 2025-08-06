<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->string('username')->nullable(false)->change();
            $table->string('profile_picture')->nullable()->change();
            $table->string('location')->nullable()->change();
            $table->string('status')->nullable()->change();
            $table->text('achievements')->nullable()->change();
            $table->integer('contribution')->nullable()->default(0)->change();
            $table->integer('total_token')->nullable()->default(0)->change();
            $table->json('token_usages')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->string('username')->nullable(false)->change();
            $table->string('profile_picture')->nullable(false)->change();
            $table->string('location')->nullable(false)->change();
            $table->string('status')->nullable(false)->change();
            $table->text('achievements')->nullable(false)->change();
            $table->integer('contribution')->nullable(false)->default(0)->change();
            $table->integer('total_token')->nullable(false)->default(0)->change();
            $table->json('token_usages')->nullable(false)->change();
        });
    }
};
