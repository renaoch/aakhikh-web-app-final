<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ministry_teams', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->smallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletesTz();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ministry_teams');
    }
};