<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('passed_quiz_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passed_quiz_id')
                ->constrained('passed_quizzes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('question');
            $table->unsignedTinyInteger('time');
            $table->boolean('correct');
            $table->json('chosen_answers');
            $table->json('correct_answers');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passed_quiz_items');
    }
};
