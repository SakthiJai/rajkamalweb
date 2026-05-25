<?php

namespace App\Observers;

use App\Classes\Common;
use App\Models\User;
use App\Notifications\StaffWelcomeNotification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    public function created(User $user)
    {
        $plainPassword = $user->plain_text_password ?? null;

        if (!empty($plainPassword) && !empty($user->email) && $user->user_type === 'staff_members') {
            try {
                Log::info('Sending welcome email to user: ' . $user->email);
                $user->notify(new StaffWelcomeNotification($user, $plainPassword));
                Log::info('Welcome email sent successfully to: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Failed to send staff welcome email to ' . $user->email . ': ' . $e->getMessage());
                Log::error($e->getTraceAsString());
            }
        }
    }

    public function saving(User $user)
    {
        $company = company();

        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if ($company && !$company->is_global) {
            $user->company_id = $company->id;
        }
    }

    public function updating(User $user)
    {
        $original = $user->getOriginal();
        if ($user->isDirty('image')) {
            $userImagePath = Common::getFolderPath('userImagePath');

            File::delete($userImagePath . $original['image']);
        }
    }

    public function deleting(User $user)
    {
        $userImagePath = Common::getFolderPath('userImagePath');

        File::delete($userImagePath . $user->image);
    }
}
