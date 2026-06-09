<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeaderSeeder extends Seeder
{
    public function run(): void
    {
        $leaders = [
            [
                'name'          => 'Pastor Samuel Aakhikh',
                'role_title'    => 'Senior Pastor',
                'bio'           => 'Pastor Samuel has led Aakhikh Church for over 20 years with a heart for community and the Word of God.',
                'photo_url'     => null,
                'email'         => 'pastor@aakhikh.org',
                'display_order' => 1,
                'is_active'     => true,
                'category'      => 'pastor',
            ],
            [
                'name'          => 'Rev. Grace Teron',
                'role_title'    => 'Associate Pastor',
                'bio'           => 'Rev. Grace oversees women\'s ministry and counselling at Aakhikh Church.',
                'photo_url'     => null,
                'email'         => 'grace@aakhikh.org',
                'display_order' => 2,
                'is_active'     => true,
                'category'      => 'pastor',
            ],
            [
                'name'          => 'Deacon Joseph Boro',
                'role_title'    => 'Head Deacon',
                'bio'           => 'Joseph coordinates all deacon activities and outreach programs.',
                'photo_url'     => null,
                'email'         => 'joseph@aakhikh.org',
                'display_order' => 3,
                'is_active'     => true,
                'category'      => 'deacon',
            ],
            [
                'name'          => 'Mary Basumatary',
                'role_title'    => 'Worship Director',
                'bio'           => 'Mary leads the worship team and has been serving in music ministry for 10 years.',
                'photo_url'     => null,
                'email'         => 'mary@aakhikh.org',
                'display_order' => 4,
                'is_active'     => true,
                'category'      => 'staff',
            ],
            [
                'name'          => 'Elder Thomas Narzary',
                'role_title'    => 'Church Elder',
                'bio'           => 'Elder Thomas provides spiritual oversight and guidance to the congregation.',
                'photo_url'     => null,
                'email'         => null,
                'display_order' => 5,
                'is_active'     => true,
                'category'      => 'elder',
            ],
        ];

        foreach ($leaders as $leader) {
            DB::table('leaders')->insertOrIgnore([
                ...$leader,
                'id'         => (string) Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}