<?php

return [
    /*
    |--------------------------------------------------------------------------
    | YouTube Data API v3
    |--------------------------------------------------------------------------
    | Used by the SyncYoutubeSermons artisan command to pull the latest sermon
    | videos from the church YouTube channel.
    |
    | Set YOUTUBE_API_KEY and YOUTUBE_CHANNEL_ID in your .env file.
    */

    'key'        => env('YOUTUBE_API_KEY'),
    'channel_id' => env('YOUTUBE_CHANNEL_ID'),
];
