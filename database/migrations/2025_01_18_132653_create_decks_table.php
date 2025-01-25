<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('decks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();

            $table->index('title', 'idx_title');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('decks');
    }
};
