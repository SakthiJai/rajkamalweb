<?php

namespace App\Observers;

use App\Models\StaffMember;
use App\Notifications\StaffWelcomeNotification;
use Illuminate\Support\Facades\Log;

class StaffMemberObserver
{
    public function created(StaffMember $staffMember)
    {
        Log::info('StaffMemberObserver@created triggered for: ' . $staffMember->email);

        $plainPassword = $staffMember->plain_text_password ?? null;

        Log::info('Plain password captured: ' . ($plainPassword ? 'YES' : 'NO'));
        Log::info('Staff member type: ' . $staffMember->user_type);
        Log::info('Staff member email: ' . $staffMember->email);

        if (!empty($plainPassword) && !empty($staffMember->email) && $staffMember->user_type === 'staff_members') {
            try {
                Log::info('Sending welcome email to staff member: ' . $staffMember->email);
                $staffMember->notify(new StaffWelcomeNotification($staffMember, $plainPassword));
                Log::info('Welcome email sent successfully to: ' . $staffMember->email);
            } catch (\Exception $e) {
                Log::error('Failed to send staff welcome email to ' . $staffMember->email . ': ' . $e->getMessage());
                Log::error($e->getTraceAsString());
            }
        } else {
            Log::warning('Email not sent - Missing conditions. Password: ' . ($plainPassword ? 'yes' : 'no') . ', Email: ' . ($staffMember->email ?? 'empty') . ', Type: ' . $staffMember->user_type);
        }
    }

    public function saving(StaffMember $staffMember)
    {
        $company = company();

        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if ($company && !$company->is_global) {
            $staffMember->company_id = $company->id;
        }
    }
}
