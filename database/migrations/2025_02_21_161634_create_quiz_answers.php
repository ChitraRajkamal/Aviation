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
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('course_id')->constrained('courses')->onDelete('restrict');
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('restrict');
            $table->integer('total_questions')->default(0);
            $table->integer('total_mark')->default(0);
            $table->integer('pass_mark')->default(0);
            $table->integer('obtained_mark')->default(0);
            $table->integer('attempted')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->string('time_taken', 50);
            $table->boolean('is_pass')->default(false);
            $table->index('user_id');
            $table->index('course_id');
            $table->index('lesson_id');
            $table->timestamps();
        });

        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('course_id')->constrained('courses')->onDelete('restrict');
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('restrict');
            $table->foreignId('question_id')->constrained('questions')->onDelete('restrict');
            $table->foreignId('quiz_result_id')->constrained('quiz_results')->onDelete('restrict');
            $table->longText('answer')->nullable();
            $table->longText('question')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->index('user_id');
            $table->index('course_id');
            $table->index('lesson_id');
            $table->index('question_id');
            $table->index('quiz_result_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_results', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['course_id']);
            $table->dropForeign(['lesson_id']);
        });
        Schema::table('quiz_answers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['course_id']);
            $table->dropForeign(['lesson_id']);
            $table->dropForeign(['question_id']);
            $table->dropForeign(['quiz_result_id']);
        });
        Schema::dropIfExists('quiz_results');
        Schema::dropIfExists('quiz_answers');
    }
};
