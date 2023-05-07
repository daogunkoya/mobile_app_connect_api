<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;



use Illuminate\Auth\Events\UserCreated;
use Spatie\Permission\Models\Role;

class AssignDefaultRole
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $user = $event->user;
        $taskRole = Role::where('name', 'user')->first();
        $user->assignRole($taskRole);
    }
}
