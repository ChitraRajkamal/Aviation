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
        Schema::create('organization_menus', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_heading')->default(false);
            $table->bigInteger('parent_id')->default(0);
            $table->string('name');
            $table->json('routes')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('sort_by')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_menus');
    }
};
