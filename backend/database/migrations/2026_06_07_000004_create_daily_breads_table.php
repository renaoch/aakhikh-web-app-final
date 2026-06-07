<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_breads', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('title');
            $table->text('body');
            $table->string('bible_reference')->nullable();
            $table->text('image_url')->nullable();
            $table->date('published_date')->unique();
            $table->timestampTz('scheduled_sent_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->uuid('created_by')->nullable();
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_breads');
    }
};