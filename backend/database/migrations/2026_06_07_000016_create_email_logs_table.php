<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('daily_bread_id')->nullable();
            $table->string('subject');
            $table->unsignedInteger('recipients_count')->default(0);
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->string('ses_message_id')->nullable();
            $table->timestampTz('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestampsTz();

            $table->foreign('daily_bread_id')->references('id')->on('daily_breads')->nullOnDelete();
        });

        DB::statement("ALTER TABLE email_logs ADD COLUMN type email_log_type NOT NULL DEFAULT 'announcement'");
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};