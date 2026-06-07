<?php

namespace App\Console\Commands;

use App\Models\Sermon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncYoutubeSermons extends Command
{
    protected $signature   = 'sermons:sync-youtube
                                {--limit=10 : Max videos to fetch per run}';
    protected $description = 'Sync latest sermon videos from the church YouTube channel';

    public function handle(): int
    {
        $apiKey    = config('services.youtube.key');
        $channelId = config('services.youtube.channel_id');
        $limit     = (int) $this->option('limit');

        if (! $apiKey || ! $channelId) {
            $this->error('YOUTUBE_API_KEY or YOUTUBE_CHANNEL_ID not set.');
            return self::FAILURE;
        }

        $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
            'key'        => $apiKey,
            'channelId'  => $channelId,
            'part'       => 'snippet',
            'order'      => 'date',
            'type'       => 'video',
            'maxResults' => $limit,
        ]);

        if ($response->failed()) {
            $this->error('YouTube API request failed: ' . $response->body());
            Log::error('YouTube sync failed', ['body' => $response->body()]);
            return self::FAILURE;
        }

        $items   = $response->json('items', []);
        $created = 0;

        foreach ($items as $item) {
            $videoId = data_get($item, 'id.videoId');
            $snippet = $item['snippet'] ?? [];

            if (! $videoId) {
                continue;
            }

            $youtubeUrl = 'https://www.youtube.com/watch?v=' . $videoId;

            $sermon = Sermon::firstOrCreate(
                ['youtube_url' => $youtubeUrl],
                [
                    'title'       => $snippet['title'] ?? 'Untitled',
                    'description' => Str::limit($snippet['description'] ?? '', 1000),
                    'thumbnail'   => data_get($snippet, 'thumbnails.high.url'),
                    'preached_at' => isset($snippet['publishedAt'])
                        ? \Carbon\Carbon::parse($snippet['publishedAt'])->toDateString()
                        : now()->toDateString(),
                    'is_published' => false,
                ],
            );

            if ($sermon->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->info("Sync complete. {$created} new sermon(s) imported.");
        Log::info('YouTube sync complete', ['created' => $created, 'total' => count($items)]);

        return self::SUCCESS;
    }
}
