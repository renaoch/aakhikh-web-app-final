<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('author_name');
            $table->string('author_email')->nullable();
            $table->text('content');
            $table->uuid('submitted_by')->nullable();
            $table->uuid('reviewed_by')->nullable();
            $table->timestampTz('reviewed_at')->nullable();
            $table->text('rejection_note')->nullable();
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->foreign('submitted_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });

        DB::statement("ALTER TABLE testimonials ADD COLUMN status testimonial_status NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};