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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->unique();

            $table->unsignedBigInteger('organization_id')->default(0)->index();
            $table->foreignId('course_category_id')->constrained('course_categories')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');

            $table->string('course_type', 50)->nullable();
            $table->string('status', 50)->nullable();
            $table->string('level', 50)->nullable();
            $table->string('language', 50)->nullable();

            $table->tinyInteger('is_paid')->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('discount_flag')->default(0);
            $table->decimal('discounted_price', 10, 2)->default(0);

            $table->json('meta_data')->nullable();

            $table->string('thumbnail')->nullable();
            $table->string('banner')->nullable();
            $table->tinyInteger('is_online_video')->default(0);
            $table->string('uploaded_video_url')->nullable();
            $table->string('online_video_url')->nullable();

            $table->text('short_description')->nullable();
            $table->mediumText('description')->nullable();
            $table->mediumText('requirements')->nullable();
            $table->mediumText('outcomes')->nullable();
            $table->mediumText('faqs')->nullable();
            $table->text('instructors')->nullable();

            $table->unsignedBigInteger('created_by_id')->default(0);
            $table->unsignedBigInteger('updated_by_id')->default(0);

            $table->timestamps();

            $table->index('user_id');
            $table->index('course_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['course_category_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('courses');
    }
};
