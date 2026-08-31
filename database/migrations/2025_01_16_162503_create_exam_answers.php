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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('restrict');
            $table->integer('total_questions')->default(0);
            $table->integer('total_mark')->default(0);
            $table->integer('pass_mark')->default(0);
            $table->integer('obtained_mark')->default(0);
            $table->integer('negative_mark')->default(0);
            $table->integer('attempted')->default(0);
            $table->integer('correct')->default(0);
            $table->integer('wrong')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->string('time_taken', 50);
            $table->boolean('is_pass')->default(false);
            
            $table->index('user_id');
            $table->index('exam_id');
            $table->timestamps();
        });

        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('restrict');
            $table->foreignId('qbank_id')->constrained('qbank')->onDelete('restrict');
            $table->foreignId('qbank_question_id')->constrained('qbank_questions')->onDelete('restrict');
            $table->foreignId('exam_result_id')->constrained('exam_results')->onDelete('restrict');
            $table->longText('answer')->nullable();
            $table->longText('question')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->boolean('is_fully_attended')->default(false);
            
            $table->index('user_id');
            $table->index('exam_id');
            $table->index('qbank_id');
            $table->index('qbank_question_id');
            $table->index('exam_result_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['exam_id']);
        });
        Schema::table('exam_answers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['exam_id']);
            $table->dropForeign(['qbank_id']);
            $table->dropForeign(['qbank_question_id']);
            $table->dropForeign(['exam_result_id']);
        });
        Schema::dropIfExists('exam_answers');
        Schema::dropIfExists('exam_results');
    }
};
