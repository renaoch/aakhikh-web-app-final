<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Users are managed by Supabase — we only seed a local DB record
     * for the admin so role-based middleware works during development.
     */
    public function run(): void
    {
        // Seed a dev admin user (supabase_id matches your actual Supabase UID in dev)
        User::firstOrCreate(
            ['email' => 'admin@aakhikh.org'],
            [
                'supabase_id' => (string) Str::uuid(), // replace with real Supabase UID if needed
                'name'        => 'Admin User',
                'role'        => UserRole::Admin,
                'is_active'   => true,
            ]
        );

        // Seed a dev editor user
        User::firstOrCreate(
            ['email' => 'editor@aakhikh.org'],
            [
                'supabase_id' => (string) Str::uuid(),
                'name'        => 'Editor User',
                'role'        => UserRole::Editor,
                'is_active'   => true,
            ]
        );

        $this->command->info('✅ Dev users seeded (admin + editor)');
    }
}
