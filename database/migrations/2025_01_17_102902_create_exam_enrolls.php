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
        Schema::create('exam_enrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->default(0)->index();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('restrict');
            $table->boolean('is_enrolled')->default(false);
            $table->boolean('is_attended')->default(false);
            $table->index('user_id');
            $table->index('exam_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_enrolls', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['exam_id']);
        });
        Schema::dropIfExists('exam_enrolls');
    }
};
