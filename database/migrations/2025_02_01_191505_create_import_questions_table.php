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
        Schema::create('import_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qbank_id')->constrained('qbank')->onDelete('cascade');
            $table->string('grouping');
            $table->longText('question');
            $table->longText('option_a')->nullable();
            $table->longText('option_b')->nullable();
            $table->longText('option_c')->nullable();
            $table->longText('option_d')->nullable();
            $table->longText('option_e')->nullable();
            $table->longText('correct_answer')->nullable();
            $table->longText('explanation')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('created_by_id')->default(0);
            $table->unsignedBigInteger('updated_by_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('import_questions', function (Blueprint $table) {
            $table->dropForeign(['qbank_id']);
        });
        Schema::dropIfExists('import_questions');
    }
};
