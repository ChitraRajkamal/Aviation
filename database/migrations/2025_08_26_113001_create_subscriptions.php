<?php

use App\Enums\SubscriptionPaymentType;
use App\Enums\SubscriptionStatus;
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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('restrict');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('restrict');
            $table->integer('no_of_students')->default(-1);
            $table->boolean('allow_courses')->default(false);
            $table->integer('no_of_courses')->default(-1);
            $table->boolean('allow_exams')->default(false);
            $table->integer('no_of_exams')->default(-1);
            $table->boolean('allow_job_posts')->default(false);
            $table->integer('no_of_job_posts')->default(-1);
            $table->boolean('allow_recorded_videos')->default(false);
            $table->integer('no_of_recorded_videos')->default(-1);
            $table->enum('type',  lms_enum_to_array(SubscriptionPaymentType::class))->nullable();
            $table->date('expiry')->nullable();
            $table->decimal('price', 10, 2);
            $table->text('remarks')->nullable();
            $table->enum('status',  lms_enum_to_array(SubscriptionStatus::class))->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
};
