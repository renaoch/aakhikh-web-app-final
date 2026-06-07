<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Sync latest sermons from YouTube every 6 hours
        $schedule->command('sermons:sync-youtube --limit=10')
            ->everySixHours()
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/youtube-sync.log'));

        // Prune email logs older than 90 days — runs on the 1st of each month
        $schedule->command('email-logs:prune --days=90')
            ->monthly()
            ->at('02:00');

        // Clear expired Sanctum tokens weekly
        $schedule->command('sanctum:prune-expired --hours=24')
            ->weekly()
            ->sundays()
            ->at('03:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
