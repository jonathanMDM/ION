<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\PasswordChanged;
use Illuminate\Support\Facades\Mail;

class TestPasswordChangedEmail extends Command
{
    protected $signature = 'test:password-changed-email {email}';
    protected $description = 'Send a test password changed email';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }

        try {
            Mail::to($user->email)->send(new PasswordChanged(
                $user->name,
                now()->format('d/m/Y H:i:s'),
                '192.168.1.1',
                'Mozilla/5.0 (Test Browser)'
            ));

            $this->info("✅ Password changed email sent to {$user->email}");
            return 0;
        } catch (\Exception $e) {
            $this->error("✗ Failed to send email: " . $e->getMessage());
            return 1;
        }
    }
}
