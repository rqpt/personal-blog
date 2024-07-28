<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->tinyInteger('type')->default(0);
            $table->longText('html');
            $table->longText('markdown');
            $table->tinyInteger('contains_code');
            $table->tinyInteger('contains_toc')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }
};
