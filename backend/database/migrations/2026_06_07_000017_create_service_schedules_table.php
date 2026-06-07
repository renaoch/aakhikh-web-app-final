<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('title');
            $table->text('description')->nullable();
            $table->tinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->text('livestream_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        DB::statement("ALTER TABLE service_schedules ADD COLUMN format service_format NOT NULL DEFAULT 'in_person'");
        DB::statement("ALTER TABLE service_schedules ADD CONSTRAINT service_schedules_day_of_week_check CHECK (day_of_week BETWEEN 0 AND 6)");
    }

    public function down(): void
    {
        Schema::dropIfExists('service_schedules');
    }
};