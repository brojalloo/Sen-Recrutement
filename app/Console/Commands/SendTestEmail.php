<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email : Recipient email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to the given address';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        try {
            Mail::raw('This is a test email from Sen-Recrutement.', function ($message) use ($email) {
                $message->to($email)->subject('Sen-Recrutement - Test Email');
            });

            $this->info("Test email sent to {$email}");
            return 0;
        } catch (\Throwable $e) {
            $this->error('Failed to send test email: ' . $e->getMessage());
            return 1;
        }
    }
}
