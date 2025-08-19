<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('forum_discussions', function (Blueprint $table) {
            $table->id();
            $table->string('username'); // sync from profile username
            $table->text('message'); // discussion content
            $table->string('image')->nullable(); // image path
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_discussions');
    }
};
