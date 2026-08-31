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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->string('type');
            $table->bigInteger('type_id');
            $table->dateTime('date');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('order_id');
            $table->string('payment_id')->nullable();
            $table->string('signature')->nullable();
            $table->string('status');
            $table->string('source')->nullable();
            $table->text('remarks')->nullable();
            $table->text('request_data')->nullable();
            $table->text('request_1_data')->nullable();
            $table->text('response_data')->nullable();
            $table->text('response_1_data')->nullable();
            $table->index('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('payments');
    }
};
