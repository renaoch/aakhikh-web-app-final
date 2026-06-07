<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('team_id');
            $table->string('name');
            $table->string('role_title')->nullable();
            $table->text('photo_url')->nullable();
            $table->string('email')->nullable();
            $table->smallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->foreign('team_id')->references('id')->on('ministry_teams')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};