<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MinistryTeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            ['name' => 'Worship Ministry',    'description' => 'Leading the congregation in praise and worship.',         'icon' => 'music',        'display_order' => 1],
            ['name' => 'Youth Ministry',      'description' => 'Discipling and empowering the next generation.',          'icon' => 'users',        'display_order' => 2],
            ['name' => 'Children Ministry',   'description' => 'Nurturing children in the love and knowledge of Christ.', 'icon' => 'heart',        'display_order' => 3],
            ['name' => 'Outreach Ministry',   'description' => 'Sharing the gospel and serving the community.',           'icon' => 'globe',        'display_order' => 4],
            ['name' => 'Prayer Ministry',     'description' => 'Interceding for the church, nation, and world.',          'icon' => 'hands-pray',   'display_order' => 5],
            ['name' => 'Media Ministry',      'description' => 'Handling audio, video, and online content.',              'icon' => 'video',        'display_order' => 6],
        ];

        foreach ($teams as $team) {
            DB::table('ministry_teams')->insertOrIgnore([
                ...$team,
                'id'         => (string) Str::uuid(),
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}