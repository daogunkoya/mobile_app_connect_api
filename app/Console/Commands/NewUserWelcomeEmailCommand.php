<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserWelcomeEmail; // Assuming you have a custom notification class

class NewUserWelcomeEmailCommand extends Command
{
    protected $signature = 'notifications:email_user';

    protected $description = 'Send notifications to users 10 minutes before project deadlines';

    public function handle()
    {
        $deadlineThreshold = Carbon::now()->addMinutes(10);

        $users = User::
            where('user_role_type', 1)
            ->get();

        foreach ($users as $user) {
            Log::info('Notiication loop.');
                Log::info('Notiication individual sent');
                Notification::send($user, new NewUserWelcomeEmail($user));

                $this->info('Notification sent for Project ID: ' . $user->id_user);

        }
    }
}

