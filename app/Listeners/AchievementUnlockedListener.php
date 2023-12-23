<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\AchievementUnlocked;
use App\Services\AchievementService;
use Illuminate\Support\Facades\Log;

class AchievementUnlockedListener
{

    /**
     * Create the event listener.
    */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(AchievementUnlocked $event): void
    {
        // Perform actions when an achievement is unlocked
        $achievementName = $event->achievementName;
        $user = $event->user;
 
        // Example: Log the achievement
        Log::info("Achievement Unlocked: {$achievementName} for User ID: {$user->id}");
 
    }
}
