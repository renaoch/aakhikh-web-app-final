<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sermons', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('youtube_video_id')->nullable()->unique();
            $table->string('title');
            $table->string('speaker');
            $table->string('topic')->nullable();
            $table->text('description')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->timestampTz('published_at')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_manual_override')->default(false);
            $table->uuid('created_by')->nullable();
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sermons');
    }
};