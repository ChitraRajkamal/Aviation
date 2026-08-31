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
        Schema::create('wishlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->string('type');
            $table->bigInteger('type_id');
            $table->tinyInteger('status');
            $table->index('user_id');
            $table->timestamps();
        });
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->unsignedBigInteger('payment_id')->default(0);
            $table->string('type');
            $table->bigInteger('type_id');
            $table->decimal('amount', 10, 2)->default(0);
            $table->tinyInteger('status');
            $table->boolean('is_paid')->default(false);
            $table->index('user_id');
            $table->index('payment_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishlist', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::table('cart', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('wishlist');
        Schema::dropIfExists('cart');
    }
};
