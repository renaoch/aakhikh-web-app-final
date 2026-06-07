<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('user_id');
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('currency', 3)->default('INR');
            $table->string('razorpay_order_id')->nullable()->unique();
            $table->string('razorpay_payment_id')->nullable()->unique();
            $table->json('shipping_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestampTz('paid_at')->nullable();
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
        });

        DB::statement("ALTER TABLE orders ADD COLUMN status order_status NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};