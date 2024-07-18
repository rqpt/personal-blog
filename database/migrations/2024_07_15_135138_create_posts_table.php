<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\{
    Migrations\Migration,
    Schema\Blueprint,
};

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->text('body');
            $table->tinyInteger('published')->default(0); // cast as bool
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
