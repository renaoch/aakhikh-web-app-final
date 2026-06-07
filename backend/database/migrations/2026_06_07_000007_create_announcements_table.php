<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('title');
            $table->text('body');
            $table->boolean('is_active')->default(true);
            $table->timestampTz('published_at')->useCurrent();
            $table->timestampTz('expires_at')->nullable();
            $table->uuid('created_by')->nullable();
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};