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
        Schema::create('qbank', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->default(0)->index();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('created_by_id')->default(0);
            $table->unsignedBigInteger('updated_by_id')->default(0);
            $table->timestamps();
        });

        Schema::create('qbank_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qbank_id')->constrained('qbank')->onDelete('restrict');
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

            $table->index('qbank_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qbank_questions', function (Blueprint $table) {
            $table->dropForeign(['qbank_id']);
        });
        Schema::dropIfExists('qbank');
        Schema::dropIfExists('qbank_questions');
    }
};
