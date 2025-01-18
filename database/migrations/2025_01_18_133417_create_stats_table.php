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
            $table->unsignedInteger('count_ask');
            $table->unsignedInteger('count_easy');
            $table->unsignedInteger('count_good');
            $table->unsignedInteger('count_hard');
            $table->unsignedInteger('count_again');
            $table->foreignId('question_id')->constrained('questions', 'id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
