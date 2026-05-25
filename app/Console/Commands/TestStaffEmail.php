<?php

namespace App\Console\Commands;

use App\Models\StaffMember;
use App\Notifications\StaffWelcomeNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestStaffEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:staff-email {email? : Email address to test}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Test staff welcome email notification';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'test@example.com';
        
        $this->info('Testing Staff Welcome Email...');
        $this->line('Mail Configuration:');
        $this->line('- Mailer: ' . config('mail.default'));
        $this->line('- Host: ' . config('mail.mailers.smtp.host'));
        $this->line('- Port: ' . config('mail.mailers.smtp.port'));
        $this->line('- Encryption: ' . config('mail.mailers.smtp.encryption'));
        $this->line('- From: ' . config('mail.from.address'));
        $this->line('');

        // Create a test staff member object
        $staffMember = new StaffMember();
        $staffMember->id = 999999;
        $staffMember->name = 'Test Staff Member';
        $staffMember->email = $email;
        $staffMember->user_type = 'staff_members';
        
        $testPassword = 'TestPassword123';

        try {
            $this->line('Sending test email to: ' . $email);
            
            // Send the notification
            $staffMember->notify(new StaffWelcomeNotification($staffMember, $testPassword));
            
            $this->info('✓ Email sent successfully!');
            $this->line('Check your email inbox for the welcome message.');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Failed to send email!');
            $this->error('Error: ' . $e->getMessage());
            $this->line('');
            $this->line('Stack trace:');
            $this->line($e->getTraceAsString());
            
            return 1;
        }
    }
}
