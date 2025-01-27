<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('count_ask')->default(0);
            $table->unsignedInteger('count_easy')->default(0);
            $table->unsignedInteger('count_good')->default(0);
            $table->unsignedInteger('count_hard')->default(0);
            $table->unsignedInteger('count_again')->default(0);
            $table->foreignId('question_id')->constrained('questions', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
