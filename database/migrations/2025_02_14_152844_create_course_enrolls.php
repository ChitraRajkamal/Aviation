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
        Schema::create('course_enrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->default(0)->index();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('course_id')->constrained('courses')->onDelete('restrict');
            $table->boolean('is_enrolled')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->dateTime('completed_date')->nullable();
            $table->json('lesson_ids')->nullable();
            $table->bigInteger('current_lesson_id')->default(0);
            $table->index('user_id');
            $table->index('course_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_enrolls', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['course_id']);
        });
        Schema::dropIfExists('course_enrolls');
    }
};
