<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tag_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions', 'id')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags', 'id')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tag_question');
    }
};
