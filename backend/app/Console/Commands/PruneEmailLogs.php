<?php

namespace App\Console\Commands;

use App\Models\EmailLog;
use Illuminate\Console\Command;

class PruneEmailLogs extends Command
{
    protected $signature   = 'email-logs:prune {--days=90 : Delete logs older than N days}';
    protected $description = 'Prune old email log records';

    public function handle(): int
    {
        $days    = (int) $this->option('days');
        $deleted = EmailLog::where('created_at', '<', now()->subDays($days))->delete();

        $this->info("Deleted {$deleted} email log record(s) older than {$days} days.");

        return self::SUCCESS;
    }
}
