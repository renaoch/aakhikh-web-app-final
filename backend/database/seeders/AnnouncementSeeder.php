<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $announcements = [
            [
                'title'        => 'Church Annual Convention 2026',
                'body'         => 'We are excited to announce our Annual Church Convention from July 10–13, 2026. All members are encouraged to attend. Registration opens soon.',
                'is_active'    => true,
                'published_at' => now(),
                'expires_at'   => now()->addDays(30),
            ],
            [
                'title'        => 'Baptism Service — June 22',
                'body'         => 'A baptism service will be held on Sunday, June 22nd after the morning worship. Contact the church office to register.',
                'is_active'    => true,
                'published_at' => now(),
                'expires_at'   => now()->addDays(14),
            ],
            [
                'title'        => 'New Sunday School Batches Starting',
                'body'         => 'Sunday School classes for children aged 5–15 will begin from the first Sunday of July. Parents, please enroll your children at the church office.',
                'is_active'    => true,
                'published_at' => now(),
                'expires_at'   => now()->addDays(21),
            ],
            [
                'title'        => 'Church Building Fund Update',
                'body'         => 'Praise God! We have reached 60% of our building fund goal. Thank you for your generous contributions. We continue to receive donations toward the new sanctuary.',
                'is_active'    => true,
                'published_at' => now(),
                'expires_at'   => null,
            ],
        ];

        foreach ($announcements as $announcement) {
            DB::table('announcements')->insertOrIgnore([
                ...$announcement,
                'id'         => (string) Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}