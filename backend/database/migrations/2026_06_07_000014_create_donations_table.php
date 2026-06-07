<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('user_id')->nullable();
            $table->string('donor_name');
            $table->string('donor_email')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('INR');
            $table->string('razorpay_order_id')->nullable()->unique();
            $table->string('razorpay_payment_id')->nullable()->unique();
            $table->boolean('is_anonymous')->default(false);
            $table->text('message')->nullable();
            $table->timestampTz('paid_at')->nullable();
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        DB::statement("ALTER TABLE donations ADD COLUMN type donation_type NOT NULL DEFAULT 'one_time'");
        DB::statement("ALTER TABLE donations ADD COLUMN category donation_category NOT NULL DEFAULT 'general'");
        DB::statement("ALTER TABLE donations ADD COLUMN status order_status NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};