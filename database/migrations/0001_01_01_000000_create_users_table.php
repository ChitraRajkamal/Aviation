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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->default(0)->index();
            $table->unsignedBigInteger('role_id')->default(0);
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('role', ['admin', 'organization', 'student', 'tutor'])->default('student');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->string('gender', 16)->nullable();
            $table->string('image', 512)->nullable();
            $table->date('dob')->nullable();
            $table->string('current_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->text('skills')->nullable();
            $table->string('occupation', 128)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('fp_token')->nullable();
            $table->datetime('fp_date')->nullable();
            
            $table->string('google_id', 100)->nullable();
            $table->text('google_token')->nullable();
            $table->string('facebook_id', 100)->nullable();
            $table->text('facebook_token')->nullable();
            $table->string('login_from', 20)->nullable();

            $table->rememberToken();
            $table->unsignedBigInteger('created_by_id')->default(0);
            $table->unsignedBigInteger('updated_by_id')->default(0);
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
    }
};
