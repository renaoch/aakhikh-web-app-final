<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('supabase_uid')->unique()->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('avatar_url')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestampTz('email_verified_at')->nullable();
            $table->timestampTz('last_login_at')->nullable();
            $table->rememberToken();
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        DB::statement("ALTER TABLE users ADD COLUMN role user_role NOT NULL DEFAULT 'member'");
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};