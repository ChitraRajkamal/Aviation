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
        Schema::create('recorded_video_enrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->default(0)->index();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('recorded_video_id')->constrained('recorded_videos')->onDelete('restrict');
            $table->boolean('is_enrolled')->default(false);
            $table->index('user_id');
            $table->index('recorded_video_id');
            $table->timestamps();
        });
        Schema::create('job_post_enrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->default(0)->index();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('job_post_id')->constrained('job_posts')->onDelete('restrict');
            $table->boolean('is_enrolled')->default(false);
            $table->index('user_id');
            $table->index('job_post_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recorded_video_enrolls', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['recorded_video_id']);
        });
        Schema::table('job_post_enrolls', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['job_post_id']);
        });
        Schema::dropIfExists('recorded_video_enrolls');
        Schema::dropIfExists('job_post_enrolls');
    }
};
