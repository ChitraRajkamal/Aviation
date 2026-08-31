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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('course_id')->constrained('courses')->onDelete('restrict');
            $table->string('title', 100)->nullable();
            $table->integer('sort_by')->default(0);
            $table->tinyInteger('status')->nullable();
            $table->unsignedBigInteger('created_by_id')->default(0);
            $table->unsignedBigInteger('updated_by_id')->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('course_id');
        });

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('course_id')->constrained('courses')->onDelete('restrict');
            $table->foreignId('section_id')->constrained('sections')->onDelete('restrict');
            $table->string('title')->nullable();
            $table->string('lesson_type', 50)->nullable();
            $table->string('document_type', 50)->nullable();
            $table->string('duration', 50)->nullable();
            $table->integer('total_mark')->nullable();
            $table->integer('pass_mark')->nullable();
            $table->integer('retake')->nullable();
            $table->string('lesson_src')->nullable();
            $table->string('caption')->nullable();
            $table->string('thumbnail')->nullable();
            $table->tinyInteger('is_free')->nullable();
            $table->tinyInteger('is_quiz')->default(0);
            $table->integer('sort_by')->default(0);
            $table->mediumText('description')->nullable();
            $table->mediumText('summary')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->unsignedBigInteger('created_by_id')->default(0);
            $table->unsignedBigInteger('updated_by_id')->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('course_id');
            $table->index('section_id');
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('course_id')->constrained('courses')->onDelete('restrict');
            $table->foreignId('section_id')->constrained('sections')->onDelete('restrict');
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('restrict');
            $table->string('type', 50)->nullable();
            $table->longText('title')->nullable();
            $table->longText('options')->nullable();
            $table->mediumText('answer')->nullable();
            $table->mediumText('description')->nullable();
            $table->integer('marks');
            $table->integer('sort_by')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('created_by_id')->default(0);
            $table->unsignedBigInteger('updated_by_id')->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('course_id');
            $table->index('section_id');
            $table->index('lesson_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['course_id']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['course_id']);
            $table->dropForeign(['section_id']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['course_id']);
            $table->dropForeign(['section_id']);
            $table->dropForeign(['lesson_id']);
        });

        Schema::dropIfExists('sections');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('questions');
    }
};
