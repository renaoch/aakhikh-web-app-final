<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@aakhikh.org'],
            [
                'supabase_uid' => (string) Str::uuid(),
                'name' => 'Admin User',
'role' => UserRole::ADMIN,
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'editor@aakhikh.org'],
            [
                'supabase_uid' => (string) Str::uuid(),
                'name' => 'Editor User',
'role' => UserRole::EDITOR,
                'is_active' => true,
            ]
        );

        $this->command->info('✅ Dev users seeded (admin + editor)');
    }
}