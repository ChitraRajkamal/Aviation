<?php

use App\Enums\RecordedVideoType;
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
        Schema::create('recorded_video_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('description', 500)->nullable();
            $table->integer('sort_by')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->string('thumbnail')->nullable();
            $table->unsignedBigInteger('created_by_id')->default(0);
            $table->unsignedBigInteger('updated_by_id')->default(0);
            $table->timestamps();
        });

        Schema::create('recorded_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->default(0)->index();
            $table->foreignId('recorded_video_category_id')->constrained('recorded_video_categories')->onDelete('restrict');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->enum('type',  lms_enum_to_array(RecordedVideoType::class))->nullable();
            $table->string('link')->nullable();
            $table->tinyInteger('is_paid')->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->string('thumbnail')->nullable();
            $table->integer('sort_by')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->json('meta_data')->nullable();

            $table->unsignedBigInteger('created_by_id')->default(0);
            $table->unsignedBigInteger('updated_by_id')->default(0);
            $table->timestamps();

            $table->index('recorded_video_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recorded_videos', function (Blueprint $table) {
            $table->dropForeign(['recorded_video_category_id']);
        });
        Schema::dropIfExists('recorded_video_categories');
        Schema::dropIfExists('recorded_videos');
    }
};
