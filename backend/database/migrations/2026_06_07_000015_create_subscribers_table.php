<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('confirmation_token')->nullable()->unique();
            $table->timestampTz('confirmed_at')->nullable();
            $table->timestampTz('unsubscribed_at')->nullable();
            $table->string('unsubscribe_token')->nullable()->unique();
            $table->string('source')->nullable();
            $table->string('ses_message_id')->nullable();
            $table->timestampsTz();
        });

        DB::statement("ALTER TABLE subscribers ADD COLUMN status subscriber_status NOT NULL DEFAULT 'pending_confirmation'");
    }

    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};