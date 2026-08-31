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
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('restrict');
            $table->string('type', 50)->nullable();
            $table->longText('title')->nullable();
            $table->longText('options')->nullable();
            $table->longText('answer')->nullable();
            $table->mediumText('description')->nullable();
            $table->integer('marks');
            $table->integer('sort_by')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('created_by_id')->default(0);
            $table->unsignedBigInteger('updated_by_id')->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('exam_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_questions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['exam_id']);
        });
        Schema::dropIfExists('exam_questions');
    }
};
