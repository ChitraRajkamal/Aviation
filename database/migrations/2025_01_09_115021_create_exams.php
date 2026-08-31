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
        Schema::create('exam_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('description', 500)->nullable();
            $table->integer('sort_by')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->string('icon')->nullable();
            $table->string('thumbnail')->nullable();
            $table->unsignedBigInteger('created_by_id')->default(0);
            $table->unsignedBigInteger('updated_by_id')->default(0);
            $table->timestamps();
        });

        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->default(0)->index();
            $table->foreignId('exam_category_id')->constrained('exam_categories')->onDelete('restrict');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('is_paid')->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->string('duration', 50)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('total_mark')->nullable();
            $table->integer('pass_mark')->nullable();
            $table->integer('retake')->nullable();
            $table->integer('negative_mark')->nullable();
            $table->json('qbank_ids')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('sort_by')->default(0);
            $table->json('meta_data')->nullable();

            $table->unsignedBigInteger('created_by_id')->default(0);
            $table->unsignedBigInteger('updated_by_id')->default(0);
            $table->timestamps();

            $table->index('exam_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['exam_category_id']);
        });
        Schema::dropIfExists('exam_categories');
        Schema::dropIfExists('exams');
    }
};
