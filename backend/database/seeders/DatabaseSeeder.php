<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user — explicit UUID, bypasses Eloquent HasUuids
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@aakhikh.org'],
            [
                'id'           => (string) Str::uuid(),
                'supabase_uid' => (string) Str::uuid(),
                'name'         => 'Admin User',
                'role'         => UserRole::ADMIN->value,
                'is_active'    => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]
        );

        // Editor user
        DB::table('users')->updateOrInsert(
            ['email' => 'editor@aakhikh.org'],
            [
                'id'           => (string) Str::uuid(),
                'supabase_uid' => (string) Str::uuid(),
                'name'         => 'Editor User',
                'role'         => UserRole::EDITOR->value,
                'is_active'    => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]
        );

        $this->call([
            LeaderSeeder::class,
            ServiceScheduleSeeder::class,
            AnnouncementSeeder::class,
            DailyBreadSeeder::class,
            TestimonialSeeder::class,
            MinistryTeamSeeder::class,
        ]);

        $this->command->info('All seeders ran successfully.');
    }
}