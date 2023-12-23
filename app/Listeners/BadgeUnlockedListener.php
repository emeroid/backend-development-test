<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\BadgeUnlocked;
use Illuminate\Support\Facades\Log;

class BadgeUnlockedListener
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BadgeUnlocked $event): void
    {
        // Perform actions when a badge is unlocked
        $badgeName = $event->badgeName;
        $user = $event->user;
 
        // Example: Send a notification, update user's profile, etc.
        Log::info("Badge Unlocked: {$badgeName} for User ID: {$user->id}");
 
    }
}
