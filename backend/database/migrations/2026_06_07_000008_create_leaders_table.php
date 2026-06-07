<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaders', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('name');
            $table->string('role_title');
            $table->text('bio')->nullable();
            $table->text('photo_url')->nullable();
            $table->string('email')->nullable();
            $table->smallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        DB::statement("ALTER TABLE leaders ADD COLUMN category leader_category NOT NULL DEFAULT 'staff'");
    }

    public function down(): void
    {
        Schema::dropIfExists('leaders');
    }
};