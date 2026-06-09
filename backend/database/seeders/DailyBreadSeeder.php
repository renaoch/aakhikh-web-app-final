<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DailyBreadSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            [
                'title'          => 'Walking in Faith',
                'body'           => 'Faith is not the absence of doubt, but the courage to move forward despite it. When God calls us, He does not promise a smooth road — He promises His presence on every step of the journey. Today, take one step of faith, however small, and trust that He goes before you.',
                'bible_reference' => 'Hebrews 11:1',
                'image_url'      => null,
                'published_date' => now()->toDateString(),
                'is_published'   => true,
            ],
            [
                'title'          => 'The Lord is My Shepherd',
                'body'           => 'In every season of life, God leads us beside still waters. Even in the valley of the shadow of death, His rod and staff are a comfort. Rest today in the knowledge that you are not walking alone — your Shepherd is with you.',
                'bible_reference' => 'Psalm 23:1-4',
                'image_url'      => null,
                'published_date' => now()->subDay()->toDateString(),
                'is_published'   => true,
            ],
            [
                'title'          => 'Strength in His Word',
                'body'           => 'The Word of God is living and active, sharper than any two-edged sword. When you feel weak, open His Word. When you feel lost, let it be a lamp to your feet. Scripture is not just text — it is the voice of the living God speaking directly to your heart.',
                'bible_reference' => 'Hebrews 4:12',
                'image_url'      => null,
                'published_date' => now()->subDays(2)->toDateString(),
                'is_published'   => true,
            ],
            [
                'title'          => 'Be Still and Know',
                'body'           => 'In a world full of noise and haste, God invites us to be still. Not passive, but intentionally quiet before Him. In that stillness, He reminds us who He is — God over every storm, every fear, every uncertainty. Be still today and let Him speak.',
                'bible_reference' => 'Psalm 46:10',
                'image_url'      => null,
                'published_date' => now()->subDays(3)->toDateString(),
                'is_published'   => true,
            ],
            [
                'title'          => 'New Mercies Every Morning',
                'body'           => 'No matter how yesterday looked, this morning carries fresh mercy from God. His compassions never fail. They are new every morning. Whatever burden you carried yesterday, lay it down today. His mercies are waiting for you right now.',
                'bible_reference' => 'Lamentations 3:22-23',
                'image_url'      => null,
                'published_date' => now()->addDay()->toDateString(),
                'is_published'   => true,
            ],
        ];

        foreach ($entries as $entry) {
            DB::table('daily_breads')->insertOrIgnore([
                ...$entry,
                'id'         => (string) Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}