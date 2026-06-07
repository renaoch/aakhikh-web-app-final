<?php

namespace App\Console\Commands;

use App\Jobs\SendNewsletterJob;
use App\Models\Subscriber;
use Illuminate\Console\Command;

class SendNewsletter extends Command
{
    protected $signature   = 'newsletter:send
                                {subject : Email subject line}
                                {--html= : Path to HTML file or raw HTML string}';
    protected $description = 'Dispatch newsletter to all active subscribers';

    public function handle(): int
    {
        $subject = $this->argument('subject');
        $html    = $this->option('html') ?? '';

        if (is_file($html)) {
            $html = file_get_contents($html);
        }

        if (! $html) {
            $this->error('Provide --html="<p>content</p>" or --html=/path/to/file.html');
            return self::FAILURE;
        }

        $subscribers = Subscriber::where('status', 'subscribed')->get();

        if ($subscribers->isEmpty()) {
            $this->warn('No active subscribers found.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($subscribers->count());
        $bar->start();

        foreach ($subscribers as $subscriber) {
            SendNewsletterJob::dispatch($subject, $html, $subscriber);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Queued newsletter for {$subscribers->count()} subscriber(s).");

        return self::SUCCESS;
    }
}
