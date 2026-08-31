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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->enum('direction', ['ltr','rtl']);
            $table->timestamps();
        });

        Schema::create('language_phrases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained('languages')->onDelete('restrict');
            $table->text('phrase')->nullable();
            $table->text('translated')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('language_phrases', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
        });
        Schema::dropIfExists('language_phrases');
        Schema::dropIfExists('languages');
    }
};
